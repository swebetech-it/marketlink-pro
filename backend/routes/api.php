<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\EscrowController;
use App\Http\Controllers\Api\AiController;
use App\Http\Controllers\Api\UssdController;
use App\Http\Controllers\Api\TrainingController;
use App\Http\Controllers\Api\FinancialPartnerController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DeployWebhookController;

// Auth & Users
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('users')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/', [AuthController::class, 'index']);
    Route::put('/{id}/verify-nida', [AuthController::class, 'verifyNida']);
    Route::post('/{id}/vouch', [AuthController::class, 'vouch']);
    Route::put('/{id}/role', [AuthController::class, 'updateRole']);
    Route::delete('/{id}', [AuthController::class, 'destroy']);
});

// Listings
Route::prefix('listings')->group(function () {
    Route::get('/professions', [ListingController::class, 'professions']);
    Route::get('/', [ListingController::class, 'index']);
    Route::post('/', [ListingController::class, 'store']);
    Route::get('/{id}', [ListingController::class, 'show']);
    Route::delete('/{id}', [ListingController::class, 'destroy']);
});

// Applications
Route::prefix('applications')->group(function () {
    Route::get('/', [ApplicationController::class, 'index']);
    Route::post('/', [ApplicationController::class, 'store']);
});

// Escrow & Mobile Money
Route::prefix('escrow')->group(function () {
    Route::get('/transactions', [EscrowController::class, 'transactions']);
    Route::post('/deposit', [EscrowController::class, 'deposit']);
    Route::post('/{id}/release', [EscrowController::class, 'release']);
    Route::post('/{id}/dispute', [EscrowController::class, 'dispute']);
    Route::post('/mpesa-callback', [EscrowController::class, 'mpesaCallback']);
});

// AI Services (Gemini AI)
Route::prefix('ai')->group(function () {
    Route::post('/match', [AiController::class, 'match']);
    Route::post('/fraud-check', [AiController::class, 'fraudCheck']);
    Route::post('/generate-pitch', [AiController::class, 'generatePitch']);
});

// USSD Gateway (Africa's Talking webhook & Simulator)
Route::prefix('ussd')->group(function () {
    Route::match(['get', 'post'], '/', [UssdController::class, 'handleUssd']);
    Route::post('/simulate', [UssdController::class, 'simulate']);
});

// Vocational Training Hub
Route::prefix('courses')->group(function () {
    Route::get('/', [TrainingController::class, 'index']);
    Route::post('/{id}/enroll', [TrainingController::class, 'enroll']);
});

// Financial Partners
Route::prefix('financial-products')->group(function () {
    Route::get('/', [FinancialPartnerController::class, 'index']);
    Route::post('/assess-credit', [FinancialPartnerController::class, 'assessCredit']);
});

// Messaging
Route::prefix('messages')->group(function () {
    Route::get('/{listingId}', [MessageController::class, 'forListing']);
    Route::post('/', [MessageController::class, 'store']);
});

// Notifications
Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
});

// SuperAdmin Governance
Route::prefix('admin')->group(function () {
    Route::get('/stats', [AdminController::class, 'stats']);
    Route::post('/listings/{id}/moderate', [AdminController::class, 'moderateListing']);
});

// Automated Continuous Deployment Webhook
Route::post('/deploy-webhook', [DeployWebhookController::class, 'handle']);
