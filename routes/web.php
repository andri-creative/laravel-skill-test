<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Public routes
Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}', [PostController::class, 'show']);

Route::middleware('auth')->group(function () {
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

    Route::match(['put', 'patch'], 'posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');

    Route::delete('posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
