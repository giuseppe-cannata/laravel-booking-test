<?php
use App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('bookings/export', [BookingController::class, 'export']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('bookings', BookingController::class);
});

// Rotta per il login
Route::post('login', [AuthController::class, 'login']);

// Rotta per il logout (protetta da Sanctum)
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

