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
    Route::post('/checkin', [AuthController::class, 'checkIn']);
    Route::get('/presence-stats', [AuthController::class, 'getPresenceStats']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('member');
    });
});