<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageLike;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Page $page)
    {
        $user = auth()->user();
        $like = PageLike::where('user_id', $user->id)
            ->where('page_id', $page->id)
            ->first();

        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            PageLike::create([
                'user_id' => $user->id,
                'page_id' => $page->id,
            ]);
            $isLiked = true;
        }

        $likesCount = $page->likes()->count();

        if (request()->wantsJson()) {
            return response()->json([
                'liked' => $isLiked,
                'count' => $likesCount,
            ]);
        }

        return back()->with('success', $isLiked ? 'Page liked!' : 'Like removed.');
    }
}
