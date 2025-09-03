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
use App\Http\Controllers\Vendor\DocumentController; // files + public viewer
use App\Http\Controllers\Vendor\AnalyticsController;
use App\Http\Controllers\Vendor\PlanController;
use App\Http\Controllers\Vendor\HelpRequestController;
use App\Http\Controllers\Vendor\NotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserPlanController as AdminUserPlanController;

/* ------------------------- Guest (auth) ------------------------- */
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::post('/register/captcha', [ContactController::class, 'refreshCaptcha'])->name('register.captcha');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

/* ------------------------- Logout ------------------------- */
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')->name('logout');

/* ------------------------- Public pages ------------------------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/partnerships', [HomeController::class, 'partnerships'])->name('partnerships');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::post('/subscribe', [NewsletterSubscriptionController::class, 'store'])->name('subscribe');

/* Contact + Partnerships (AJAX) */
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/contact/captcha', [ContactController::class, 'refreshCaptcha'])->name('contact.captcha');
Route::post('/partnerships/captcha', [ContactController::class, 'refreshCaptcha'])->name('partnerships.captcha');
Route::post('/partnerships', [PartnershipsController::class, 'store'])->name('partnerships.store');

/* ------------------------- Vendor dashboard ------------------------- */
Route::prefix('vendor')->middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Files
    Route::get('files',                [DocumentController::class, 'index'])->name('vendor.files.index');
    Route::get('files/list',           [DocumentController::class, 'list'])->name('vendor.files.list');
    Route::get('files/manage',         [DocumentController::class, 'manage'])->name('vendor.files.manage');
    Route::get('files/manage/list',    [DocumentController::class, 'manageList'])->name('vendor.files.manage.list');
    Route::get('files/manage/{id}',    [DocumentController::class, 'show'])->name('vendor.files.show');
    Route::put('files/manage/{id}',    [DocumentController::class, 'update'])->name('vendor.files.update');
    Route::post('files/upload',        [DocumentController::class, 'upload'])->name('vendor.files.upload');
    Route::post('files/delete',        [DocumentController::class, 'destroy'])->name('vendor.files.delete');
    Route::post('files/generate-link', [DocumentController::class, 'generateLink'])->name('vendor.files.generate');
    Route::get('files/embed',          [DocumentController::class, 'embed'])->name('vendor.files.embed');

    // Analytics
    Route::get('analytics',                    [AnalyticsController::class, 'index'])->name('vendor.analytics.index');
    Route::get('analytics/document/{id}',      [AnalyticsController::class, 'document'])->name('vendor.analytics.document');
    Route::get('analytics/documents',          [AnalyticsController::class, 'documents'])->name('vendor.analytics.documents');

    // Plan
    Route::get('plan',                 [PlanController::class, 'index'])->name('vendor.plan.index');
    Route::post('plan/update',         [PlanController::class, 'update'])->name('vendor.plan.update');

    // Help Requests
    Route::get('help/manage',       [HelpRequestController::class, 'manage'])->name('vendor.help.manage');
    Route::get('help/manage/list',  [HelpRequestController::class, 'manageList'])->name('vendor.help.manage.list');
    Route::post('help/store',       [HelpRequestController::class, 'store'])->name('vendor.help.store');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('vendor.notifications.index');
});

/* ------------------------- Admin dashboard ------------------------- */
Route::prefix('admin')->middleware(['auth','admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/files', [AdminUserController::class, 'files'])->name('users.files');
    Route::get('users/{user}/files/list', [AdminUserController::class, 'filesList'])->name('users.files.list');
    Route::post('users/{user}/files/generate-link', [AdminUserController::class, 'generateLink'])->name('users.files.generate');
    Route::get('user-plans', [AdminUserPlanController::class, 'index'])->name('user-plans.index');
});

/* ------------------------- Public viewer (no auth) ------------------------- */
Route::get('/view',    [DocumentController::class, 'viewer'])->name('public.viewer');
Route::get('/get-pdf', [DocumentController::class, 'streamBySlug'])->name('public.pdf');

// 🔹 NEW: analytics endpoint used by the viewer JS
Route::post('/track',  [DocumentController::class, 'track'])
    ->name('public.track')
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

/* Optional legacy */
Route::get('/s/{token}', [DocumentController::class, 'public'])->name('vendor.files.public.legacy');
