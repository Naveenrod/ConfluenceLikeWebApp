<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\StarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Public share link route (no auth required)
Route::get('/share/{token}', [ShareController::class, 'view'])->name('share.view');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Feed routes
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::post('/track-view', [FeedController::class, 'trackView'])->name('track.view');

    // Search
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Spaces
    Route::resource('spaces', \App\Http\Controllers\SpaceController::class);
    Route::post('/spaces/{space}/star', [StarController::class, 'toggleSpace'])->name('spaces.star');

    // Pages
    Route::resource('pages', \App\Http\Controllers\PageController::class);
    Route::post('/pages/{page}/star', [StarController::class, 'togglePage'])->name('pages.star');
    Route::post('/pages/{page}/like', [LikeController::class, 'toggle'])->name('pages.like');

    // Share links
    Route::post('/pages/{page}/share', [ShareController::class, 'create'])->name('pages.share.create');
    Route::get('/pages/{page}/share-links', [ShareController::class, 'listForPage'])->name('pages.share.list');
    Route::delete('/share-links/{shareLink}', [ShareController::class, 'deactivate'])->name('share.deactivate');

    // Starred items reorder
    Route::post('/starred/reorder', [StarController::class, 'reorder'])->name('starred.reorder');

    // Page versions
    Route::get('pages/{page}/versions', [\App\Http\Controllers\PageVersionController::class, 'index'])->name('pages.versions');
    Route::get('pages/{page}/versions/{version}', [\App\Http\Controllers\PageVersionController::class, 'show'])->name('pages.versions.show');
    Route::post('pages/{page}/versions/{version}/restore', [\App\Http\Controllers\PageVersionController::class, 'restore'])->name('pages.versions.restore');

    // Comments
    Route::post('pages/{page}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');

    // Attachments
    Route::post('pages/{page}/attachments', [\App\Http\Controllers\AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('attachments/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('attachments/{attachment}', [\App\Http\Controllers\AttachmentController::class, 'destroy'])->name('attachments.destroy');
});
