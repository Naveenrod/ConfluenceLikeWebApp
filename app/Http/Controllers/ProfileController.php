<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(User $user = null)
    {
        $user = $user ?? Auth::user();

        $activities = Activity::where('user_id', $user->id)
            ->with(['subject'])
            ->latest()
            ->take(20)
            ->get();

        $stats = [
            'pages' => $user->pages()->count(),
            'spaces' => $user->spaces()->count(),
            'comments' => $user->comments()->count(),
        ];

        return view('profile.show', compact('user', 'activities', 'stats'));
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        Auth::user()->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}

