<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ShareLink;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function create(Request $request, Page $page)
    {
        $request->validate([
            'permission' => 'in:view,edit',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $shareLink = ShareLink::create([
            'page_id' => $page->id,
            'created_by' => auth()->id(),
            'permission' => $request->input('permission', 'view'),
            'expires_at' => $request->input('expires_at'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'url' => $shareLink->url,
                'token' => $shareLink->token,
            ]);
        }

        return back()->with('success', 'Share link created!')->with('share_url', $shareLink->url);
    }

    public function view(string $token)
    {
        $shareLink = ShareLink::where('token', $token)->firstOrFail();

        if (!$shareLink->isValid()) {
            abort(403, 'This share link has expired or been deactivated.');
        }

        $page = $shareLink->page;
        $page->incrementViewCount();

        return view('pages.shared', compact('page', 'shareLink'));
    }

    public function deactivate(ShareLink $shareLink)
    {
        $this->authorize('delete', $shareLink->page);

        $shareLink->update(['is_active' => false]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Share link deactivated.');
    }

    public function listForPage(Page $page)
    {
        $shareLinks = $page->shareLinks()
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($shareLinks);
    }
}
