<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Page $page)
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'page_id' => $page->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Log activity
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'comment_added',
            'subject_type' => Comment::class,
            'subject_id' => $comment->id,
            'description' => "Added a comment on page '{$page->title}'",
        ]);

        return redirect()->route('pages.show', $page->id)
            ->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $comment->update($validated);

        return redirect()->route('pages.show', $comment->page_id)
            ->with('success', 'Comment updated successfully!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $pageId = $comment->page_id;
        $comment->delete();

        return redirect()->route('pages.show', $pageId)
            ->with('success', 'Comment deleted successfully!');
    }
}

