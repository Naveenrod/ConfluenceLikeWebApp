<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Page;
use App\Models\PageVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageVersionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Page $page)
    {
        $this->authorize('view', $page);

        $versions = $page->versions()->with('user')->orderBy('version_number', 'desc')->get();

        return view('pages.versions', compact('page', 'versions'));
    }

    public function show(Page $page, PageVersion $version)
    {
        $this->authorize('view', $page);

        $currentVersion = $page->versions()->where('version_number', $page->version)->first();

        return view('pages.version-diff', compact('page', 'version', 'currentVersion'));
    }

    public function restore(Page $page, PageVersion $version)
    {
        $this->authorize('update', $page);

        $page->update([
            'title' => $version->title,
            'content' => $version->content,
            'version' => $page->version + 1,
        ]);

        // Create new version from restore
        PageVersion::create([
            'page_id' => $page->id,
            'title' => $version->title,
            'content' => $version->content,
            'version_number' => $page->version,
            'user_id' => Auth::id(),
            'change_summary' => "Restored from version {$version->version_number}",
        ]);

        // Log activity
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'page_restored',
            'subject_type' => Page::class,
            'subject_id' => $page->id,
            'description' => "Restored page '{$page->title}' to version {$version->version_number}",
        ]);

        return redirect()->route('pages.show', $page->id)
            ->with('success', "Page restored to version {$version->version_number}!");
    }
}

