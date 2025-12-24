<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Space;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $recentSpaces = Space::where('owner_id', $user->id)
            ->orWhereHas('permissions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        $recentPages = Page::where('author_id', $user->id)
            ->orWhereHas('space', function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhereHas('permissions', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->latest()
            ->take(10)
            ->get();

        // Weekly statistics for spaces and pages (last 6 weeks, including current)
        // Do grouping in PHP so it works with SQLite/MySQL/Postgres without DB-specific functions.
        $weeksBack = 6;
        $startDate = Carbon::now()->subWeeks($weeksBack - 1)->startOfWeek();

        $spacesForStats = Space::where('created_at', '>=', $startDate)->get(['id', 'created_at']);
        $pagesForStats = Page::where('created_at', '>=', $startDate)->get(['id', 'created_at']);

        $spaceWeeklyRaw = [];
        foreach ($spacesForStats as $space) {
            $created = $space->created_at instanceof Carbon ? $space->created_at : Carbon::parse($space->created_at);
            $weekKey = $created->copy()->startOfWeek()->format('o-W'); // ISO year-week
            $spaceWeeklyRaw[$weekKey] = ($spaceWeeklyRaw[$weekKey] ?? 0) + 1;
        }

        $pageWeeklyRaw = [];
        foreach ($pagesForStats as $page) {
            $created = $page->created_at instanceof Carbon ? $page->created_at : Carbon::parse($page->created_at);
            $weekKey = $created->copy()->startOfWeek()->format('o-W'); // ISO year-week
            $pageWeeklyRaw[$weekKey] = ($pageWeeklyRaw[$weekKey] ?? 0) + 1;
        }

        $labels = [];
        $spaceCounts = [];
        $pageCounts = [];

        // Build a continuous series of weeks (even if some weeks have zero items)
        for ($i = $weeksBack - 1; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekKey = $weekStart->format('o-W'); // matches %x-%v in MySQL for ISO year-week

            $labels[] = $weekStart->format('M j'); // e.g., "Jan 1"
            $spaceCounts[] = $spaceWeeklyRaw[$weekKey] ?? 0;
            $pageCounts[] = $pageWeeklyRaw[$weekKey] ?? 0;
        }

        $chartData = [
            'labels' => $labels,
            'spaces' => $spaceCounts,
            'pages' => $pageCounts,
        ];

        return view('dashboard', compact('recentSpaces', 'recentPages', 'chartData'));
    }
}

