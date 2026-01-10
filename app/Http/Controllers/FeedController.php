<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Page;
use App\Models\Space;
use App\Models\RecentView;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $filter = $request->get('filter', 'following');
        $sort = $request->get('sort', 'relevant');

        // Get starred space IDs for "following" filter
        $starredSpaceIds = $user->starredSpaces()->pluck('spaces.id');

        // Build the feed query based on filter
        $query = Activity::with(['user', 'subject']);

        switch ($filter) {
            case 'following':
                // Activities from starred spaces
                $query->where(function ($q) use ($starredSpaceIds) {
                    $q->whereHasMorph('subject', [Page::class], function ($q) use ($starredSpaceIds) {
                        $q->whereIn('space_id', $starredSpaceIds);
                    });
                });
                break;

            case 'popular':
                // Pages with most views/likes in last 7 days
                $popularPageIds = Page::where('updated_at', '>=', now()->subDays(7))
                    ->orderByDesc('view_count')
                    ->limit(50)
                    ->pluck('id');

                $query->whereHasMorph('subject', [Page::class], function ($q) use ($popularPageIds) {
                    $q->whereIn('id', $popularPageIds);
                });
                break;

            case 'announcements':
                // Filter for announcement-type activities (you can customize this)
                $query->where('type', 'announcement');
                break;

            default:
                // All activities the user can see
                break;
        }

        // Apply sorting
        switch ($sort) {
            case 'recent':
                $query->orderByDesc('created_at');
                break;
            case 'relevant':
            default:
                // Prioritize followed spaces, then recent
                $query->orderByDesc('created_at');
                break;
        }

        $activities = $query->paginate(20);

        // Get recent items
        $recentItems = RecentView::where('user_id', $user->id)
            ->with('viewable')
            ->orderByDesc('viewed_at')
            ->limit(10)
            ->get()
            ->filter(fn($item) => $item->viewable !== null);

        // Get starred spaces
        $starredSpaces = $user->starredSpaces()->limit(10)->get();

        return view('feed.index', compact('activities', 'filter', 'sort', 'recentItems', 'starredSpaces'));
    }

    public function trackView(Request $request)
    {
        $request->validate([
            'type' => 'required|in:space,page',
            'id' => 'required|integer',
        ]);

        $user = auth()->user();
        $type = $request->type === 'space' ? Space::class : Page::class;

        // Update or create recent view
        RecentView::updateOrCreate(
            [
                'user_id' => $user->id,
                'viewable_type' => $type,
                'viewable_id' => $request->id,
            ],
            [
                'viewed_at' => now(),
            ]
        );

        // Keep only last 50 recent views per user
        $oldViews = RecentView::where('user_id', $user->id)
            ->orderByDesc('viewed_at')
            ->skip(50)
            ->pluck('id');

        if ($oldViews->isNotEmpty()) {
            RecentView::whereIn('id', $oldViews)->delete();
        }

        return response()->json(['success' => true]);
    }
}
