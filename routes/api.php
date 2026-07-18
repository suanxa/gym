<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Butuh Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pay', [AuthController::class, 'pay']);
    Route::get('/prices-list', [AuthController::class, 'getPrices']);
    Route::get('/payment-history', [AuthController::class, 'paymentHistory']);
    Route::get('/payment-method', [AuthController::class, 'getPaymentMethod']);
    Route::post('/checkin', [AuthController::class, 'checkIn']);
    Route::get('/presence-stats', [AuthController::class, 'getPresenceStats']);
    Route::get('/user/cek-presensi', [AuthController::class, 'checkPresenceStatus']);
    Route::get('/user/profile', [AuthController::class, 'profile']);
    Route::post('/user/review', [AuthController::class, 'storeReview']);
    Route::post('/user/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('member');
    });
});