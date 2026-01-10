<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Space;
use App\Models\StarredItem;
use Illuminate\Http\Request;

class StarController extends Controller
{
    public function toggleSpace(Space $space)
    {
        $user = auth()->user();
        $starred = StarredItem::where('user_id', $user->id)
            ->where('starrable_type', Space::class)
            ->where('starrable_id', $space->id)
            ->first();

        if ($starred) {
            $starred->delete();
            $isStarred = false;
        } else {
            $maxOrder = StarredItem::where('user_id', $user->id)
                ->where('starrable_type', Space::class)
                ->max('order') ?? 0;

            StarredItem::create([
                'user_id' => $user->id,
                'starrable_type' => Space::class,
                'starrable_id' => $space->id,
                'order' => $maxOrder + 1,
            ]);
            $isStarred = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['starred' => $isStarred]);
        }

        return back()->with('success', $isStarred ? 'Space starred!' : 'Space unstarred.');
    }

    public function togglePage(Page $page)
    {
        $user = auth()->user();
        $starred = StarredItem::where('user_id', $user->id)
            ->where('starrable_type', Page::class)
            ->where('starrable_id', $page->id)
            ->first();

        if ($starred) {
            $starred->delete();
            $isStarred = false;
        } else {
            $maxOrder = StarredItem::where('user_id', $user->id)
                ->where('starrable_type', Page::class)
                ->max('order') ?? 0;

            StarredItem::create([
                'user_id' => $user->id,
                'starrable_type' => Page::class,
                'starrable_id' => $page->id,
                'order' => $maxOrder + 1,
            ]);
            $isStarred = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['starred' => $isStarred]);
        }

        return back()->with('success', $isStarred ? 'Page starred!' : 'Page unstarred.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.order' => 'required|integer',
        ]);

        $user = auth()->user();

        foreach ($request->items as $item) {
            StarredItem::where('user_id', $user->id)
                ->where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
