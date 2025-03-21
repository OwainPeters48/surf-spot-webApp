<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SurfSpotController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;

// The default Home redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Login routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Route
Route::get('/dashboard', [SurfSpotController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

// Profile Route
Route::get('/users/{id}', [ProfileController::class, 'show'])->name('users.profile');

// Admin-only Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::patch('/admin/users/{id}/role', [AdminController::class, 'updateRole'])->name('admin.update.role');
    Route::delete('/admin/surf-spots/{id}', [AdminController::class, 'destroySurfSpot'])->name('admin.delete.surf-spot');
    Route::delete('/admin/comments/{id}', [AdminController::class, 'dest        royComment'])->name('admin.delete.comment');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.delete.user');
});

// Surf Spot Routes
Route::post('/dashboard/surf-spots', [SurfSpotController::class, 'store'])->name('surf-spots.store');
Route::put('/dashboard/surf-spots/{id}', [SurfSpotController::class, 'update'])->name('surf-spots.update');
Route::delete('/dashboard/surf-spots/{id}', [SurfSpotController::class, 'destroy'])->name('surf-spots.destroy');
Route::post('/dashboard/surf-spots/{id}/favourite', [SurfSpotController::class, 'toggleFavourite'])->name('surf-spots.toggle-favourite');

// Comment Routes
Route::post('/dashboard/surf-spots/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->middleware('auth')->name('comments.edit');
Route::put('/comments/{id}', [CommentController::class, 'update'])->middleware('auth')->name('comments.update');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');


// Notification Routes
Route::post('/admin/notifications/store', [NotificationController::class, 'store'])
    ->name('admin.notifications.store');

Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');

Route::get('/notifications/fetch', function () {
    $notifications = Auth::user()->unreadNotifications->map(function ($notification) {
        return [
            'id' => $notification->id, // Include ID
            'message' => $notification->data['message'],
            'time' => $notification->created_at->diffForHumans(),
        ];
    });

    return response()->json(['notifications' => $notifications]);
})->name('notifications.fetch');

// Profile Route
Route::get('/users/{id}', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->name('users.profile');
