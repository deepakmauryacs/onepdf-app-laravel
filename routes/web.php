<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\Vendor\PasswordController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PartnershipsController;
use App\Http\Controllers\Vendor\DocumentController; // <-- added

Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Logout (for logged-in users)
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/partnerships', [HomeController::class, 'partnerships'])->name('partnerships');
Route::get('/partnerships', fn() => view('partnerships'))->name('partnerships');

Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::post('/subscribe', [NewsletterSubscriptionController::class, 'store'])->name('subscribe');

// Contact form (AJAX submit)
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/partnerships', [PartnershipsController::class, 'store'])->name('partnerships.store');

// Vendor dashboard
Route::prefix('vendor')->middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Change password
    Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // ----- Files (Upload / Manage) -----
    Route::get('files',               [DocumentController::class, 'index'])->name('vendor.files.index');
    Route::get('files/list',          [DocumentController::class, 'list'])->name('vendor.files.list');
    Route::post('files/upload',       [DocumentController::class, 'upload'])->name('vendor.files.upload');
    Route::post('files/delete',       [DocumentController::class, 'destroy'])->name('vendor.files.delete');
    Route::post('files/generate-link',[DocumentController::class, 'generateLink'])->name('vendor.files.generate');
});

// Public viewer link (no auth)
Route::get('/view', [DocumentController::class, 'view'])->name('document.view');
Route::get('/s/{token}', [DocumentController::class, 'public'])->name('vendor.files.public');
