<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Page $page)
    {
        $this->authorize('update', $page);

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB max
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('', $filename, 'attachments');

        $attachment = Attachment::create([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'page_id' => $page->id,
            'user_id' => Auth::id(),
        ]);

        // Log activity
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'attachment_uploaded',
            'subject_type' => Attachment::class,
            'subject_id' => $attachment->id,
            'description' => "Uploaded '{$attachment->original_filename}' to page '{$page->title}'",
        ]);

        return redirect()->route('pages.show', $page->id)
            ->with('success', 'File uploaded successfully!');
    }

    public function download(Attachment $attachment)
    {
        $this->authorize('view', $attachment->page);

        if (!Storage::disk('attachments')->exists($attachment->path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('attachments')->download($attachment->path, $attachment->original_filename);
    }

    public function destroy(Attachment $attachment)
    {
        $this->authorize('update', $attachment->page);

        $pageId = $attachment->page_id;
        $filename = $attachment->original_filename;

        Storage::disk('attachments')->delete($attachment->path);
        $attachment->delete();

        return redirect()->route('pages.show', $pageId)
            ->with('success', "File '{$filename}' deleted successfully!");
    }
}

