<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContentController;

// ── PUBLIC ROUTES ──────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

// Projects
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Contact
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// ── ADMIN ROUTES ──────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Profile / Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/photo', [AdminController::class, 'uploadPhoto'])->name('settings.photo');
    Route::post('/settings/cv', [AdminController::class, 'uploadCv'])->name('settings.cv');
    Route::delete('/settings/cv', [AdminController::class, 'deleteCv'])->name('settings.cv.delete');

    // Projects CRUD
    Route::get('/projects', [AdminController::class, 'projects'])->name('projects');
    Route::get('/projects/create', [AdminController::class, 'createProject'])->name('projects.create');
    Route::post('/projects', [AdminController::class, 'storeProject'])->name('projects.store');
    Route::get('/projects/{id}/edit', [AdminController::class, 'editProject'])->name('projects.edit');
    Route::put('/projects/{id}', [AdminController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/{id}', [AdminController::class, 'deleteProject'])->name('projects.delete');

    // Blog CRUD
    Route::get('/blog', [AdminController::class, 'blog'])->name('blog');
    Route::get('/blog/create', [AdminController::class, 'createPost'])->name('blog.create');
    Route::post('/blog', [AdminController::class, 'storePost'])->name('blog.store');
    Route::get('/blog/{id}/edit', [AdminController::class, 'editPost'])->name('blog.edit');
    Route::put('/blog/{id}', [AdminController::class, 'updatePost'])->name('blog.update');
    Route::delete('/blog/{id}', [AdminController::class, 'deletePost'])->name('blog.delete');

    // Messages
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::delete('/messages/{id}', [AdminController::class, 'deleteMessage'])->name('messages.delete');

    // Testimonials
    Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('testimonials');
    Route::post('/testimonials', [AdminController::class, 'storeTestimonial'])->name('testimonials.store');
    Route::delete('/testimonials/{id}', [AdminController::class, 'deleteTestimonial'])->name('testimonials.delete');

    // Site content — experiences, skills, tech, highlights, stats, menu, social.
    // One schema-driven controller serves every type; {type} picks the schema.
    Route::prefix('content/{type}')->name('content.')->group(function () {
        Route::get('/', [ContentController::class, 'index'])->name('index');
        Route::get('/create', [ContentController::class, 'create'])->name('create');
        Route::post('/', [ContentController::class, 'store'])->name('store');
        Route::post('/reorder', [ContentController::class, 'reorder'])->name('reorder');
        Route::get('/{id}/edit', [ContentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ContentController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [ContentController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [ContentController::class, 'destroy'])->name('delete');
    });
});

// Auth (basic)
Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
