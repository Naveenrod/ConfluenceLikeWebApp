<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $recentSpaces = Space::where('owner_id', $user->id)
            ->orWhereHas('permissions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        $recentPages = Page::where('author_id', $user->id)
            ->orWhereHas('space', function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhereHas('permissions', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('recentSpaces', 'recentPages'));
    }
}

