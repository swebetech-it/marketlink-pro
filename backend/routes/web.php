<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\AdminAuthMiddleware;

// Linkora Main Public Landing Page & API Subdomain Handlers
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/download', [LandingPageController::class, 'downloadApk'])->name('download.apk');

// SuperAdmin Authentication Routes (Public)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Web Management Portal Routes (Guarded: SuperAdmin Only)
Route::prefix('admin')->name('admin.')->middleware(AdminAuthMiddleware::class)->group(function () {
    Route::get('/', [AdminWebController::class, 'dashboard'])->name('dashboard');
    
    // Listing / Opportunity Management
    Route::get('/listings', [AdminWebController::class, 'listings'])->name('listings');
    Route::post('/listings', [AdminWebController::class, 'storeListing'])->name('listings.store');
    Route::post('/listings/{id}/delete', [AdminWebController::class, 'deleteListing'])->name('listings.delete');

    // System User KYC & Verification
    Route::get('/users', [AdminWebController::class, 'users'])->name('users');
    Route::post('/users/{id}/verify', [AdminWebController::class, 'toggleUserVerification'])->name('users.verify');

    // Escrow & Financial Audit
    Route::get('/transactions', [AdminWebController::class, 'transactions'])->name('transactions');
});

