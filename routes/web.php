<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Vendor\DashboardController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/features', [HomeController::class, 'features']);
Route::get('/pricing', [HomeController::class, 'pricing']);
Route::get('/how-it-works', [HomeController::class, 'howItWorks']);
Route::get('/terms', [HomeController::class, 'terms']);
Route::get('/privacy', [HomeController::class, 'privacy']);
Route::get('/login', [HomeController::class, 'login']);
Route::get('/registration', [HomeController::class, 'registration']);
Route::post('/registration', [HomeController::class, 'register']);

Route::prefix('vendor')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
});
