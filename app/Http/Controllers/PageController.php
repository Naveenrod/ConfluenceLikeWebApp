<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pages = Page::with(['space', 'author'])
            ->latest()
            ->paginate(20);

        return view('pages.index', compact('pages'));
    }

    public function create(Request $request)
    {
        $spaceId = $request->get('space');
        $parentId = $request->get('parent');
        
        $spaces = Space::where('owner_id', Auth::id())
            ->orWhereHas('permissions', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        $parent = $parentId ? Page::findOrFail($parentId) : null;

        return view('pages.create', compact('spaces', 'spaceId', 'parent'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'space_id' => ['required', 'exists:spaces,id'],
            'parent_id' => ['nullable', 'exists:pages,id'],
        ]);

        $space = Space::findOrFail($validated['space_id']);
        $this->authorize('view', $space);

        $slug = Str::slug($validated['title']);
        $slugCount = Page::where('space_id', $validated['space_id'])
            ->where('slug', $slug)
            ->count();

        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }

        $page = Page::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => $slug,
            'space_id' => $validated['space_id'],
            'parent_id' => $validated['parent_id'] ?? null,
            'author_id' => Auth::id(),
            'version' => 1,
        ]);

        // Create initial version
        PageVersion::create([
            'page_id' => $page->id,
            'title' => $page->title,
            'content' => $page->content,
            'version_number' => 1,
            'user_id' => Auth::id(),
        ]);

        // Log activity
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'page_created',
            'subject_type' => Page::class,
            'subject_id' => $page->id,
            'description' => "Created page '{$page->title}'",
        ]);

        return redirect()->route('pages.show', $page->id)
            ->with('success', 'Page created successfully!');
    }

    public function show(Page $page)
    {
        $this->authorize('view', $page);

        $page->load(['space', 'author', 'comments.user', 'attachments', 'children']);
        $breadcrumbs = $page->breadcrumbs;

        return view('pages.show', compact('page', 'breadcrumbs'));
    }

    public function edit(Page $page)
    {
        $this->authorize('update', $page);

        $spaces = Space::where('owner_id', Auth::id())
            ->orWhereHas('permissions', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        return view('pages.edit', compact('page', 'spaces'));
    }

    public function update(Request $request, Page $page)
    {
        $this->authorize('update', $page);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'space_id' => ['required', 'exists:spaces,id'],
            'parent_id' => ['nullable', 'exists:pages,id'],
        ]);

        $oldContent = $page->content;
        $oldTitle = $page->title;

        $page->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'space_id' => $validated['space_id'],
            'parent_id' => $validated['parent_id'] ?? null,
            'version' => $page->version + 1,
        ]);

        // Create new version
        PageVersion::create([
            'page_id' => $page->id,
            'title' => $page->title,
            'content' => $page->content,
            'version_number' => $page->version,
            'user_id' => Auth::id(),
            'change_summary' => $request->input('change_summary'),
        ]);

        // Log activity
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'page_updated',
            'subject_type' => Page::class,
            'subject_id' => $page->id,
            'description' => "Updated page '{$page->title}'",
        ]);

        return redirect()->route('pages.show', $page->id)
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $this->authorize('delete', $page);

        $pageTitle = $page->title;
        $page->delete();

        return redirect()->route('spaces.show', $page->space_id)
            ->with('success', "Page '{$pageTitle}' deleted successfully!");
    }
}

