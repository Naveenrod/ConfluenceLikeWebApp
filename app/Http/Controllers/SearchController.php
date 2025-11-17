<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Page;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return view('search.index', ['query' => '', 'results' => collect()]);
        }

        $results = collect();

        // Search pages
        $pages = Page::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->with(['space', 'author'])
            ->get()
            ->filter(function ($page) {
                return Auth::user()->can('view', $page);
            })
            ->map(function ($page) {
                return [
                    'type' => 'page',
                    'title' => $page->title,
                    'description' => strip_tags(substr($page->content, 0, 200)),
                    'url' => route('pages.show', $page->id),
                    'space' => $page->space->name,
                    'author' => $page->author->name,
                ];
            });

        $results = $results->merge($pages);

        // Search spaces
        $spaces = Space::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('owner')
            ->get()
            ->filter(function ($space) {
                return Auth::user()->can('view', $space);
            })
            ->map(function ($space) {
                return [
                    'type' => 'space',
                    'title' => $space->name,
                    'description' => $space->description ?? '',
                    'url' => route('spaces.show', $space->id),
                    'author' => $space->owner->name,
                ];
            });

        $results = $results->merge($spaces);

        // Search comments
        $comments = Comment::where('content', 'like', "%{$query}%")
            ->with(['page', 'user'])
            ->get()
            ->filter(function ($comment) {
                return Auth::user()->can('view', $comment->page);
            })
            ->map(function ($comment) {
                return [
                    'type' => 'comment',
                    'title' => 'Comment on: ' . $comment->page->title,
                    'description' => substr($comment->content, 0, 200),
                    'url' => route('pages.show', $comment->page_id) . '#comment-' . $comment->id,
                    'author' => $comment->user->name,
                ];
            });

        $results = $results->merge($comments);

        return view('search.index', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}

