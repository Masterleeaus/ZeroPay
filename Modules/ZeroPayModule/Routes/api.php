<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPaySessionController;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPayWebhookController;

Route::get('/status', fn () => response()->json(['module' => 'ZeroPayModule', 'status' => 'active']))->name('status');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('sessions', ZeroPaySessionController::class);
    Route::post('sessions/{session}/complete', [ZeroPaySessionController::class, 'complete'])->name('sessions.complete');
    Route::post('sessions/{session}/cancel', [ZeroPaySessionController::class, 'cancel'])->name('sessions.cancel');
});

Route::post('/webhook/{gateway}', [ZeroPayWebhookController::class, 'handle'])->name('webhook');
