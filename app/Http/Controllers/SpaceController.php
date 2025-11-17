<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SpaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $spaces = Space::where('owner_id', Auth::id())
            ->orWhereHas('permissions', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(12);

        return view('spaces.index', compact('spaces'));
    }

    public function create()
    {
        return view('spaces.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:spaces,key'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
        ]);

        $space = Space::create([
            ...$validated,
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('spaces.show', $space->id)
            ->with('success', 'Space created successfully!');
    }

    public function show(Space $space)
    {
        $pages = $space->rootPages()->with('author')->latest()->get();
        return view('spaces.show', compact('space', 'pages'));
    }

    public function edit(Space $space)
    {
        $this->authorize('update', $space);
        return view('spaces.edit', compact('space'));
    }

    public function update(Request $request, Space $space)
    {
        $this->authorize('update', $space);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:spaces,key,' . $space->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
        ]);

        $space->update($validated);

        return redirect()->route('spaces.show', $space->id)
            ->with('success', 'Space updated successfully!');
    }

    public function destroy(Space $space)
    {
        $this->authorize('delete', $space);
        $space->delete();

        return redirect()->route('spaces.index')
            ->with('success', 'Space deleted successfully!');
    }
}

