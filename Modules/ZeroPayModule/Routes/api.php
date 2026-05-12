<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPayPushController;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPaySessionController;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPayWebhookController;

Route::get('/status', fn () => response()->json(['module' => 'ZeroPayModule', 'status' => 'active']))->name('status');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('sessions', ZeroPaySessionController::class);
    Route::post('sessions/{session}/complete', [ZeroPaySessionController::class, 'complete'])->name('sessions.complete');
    Route::post('sessions/{session}/cancel', [ZeroPaySessionController::class, 'cancel'])->name('sessions.cancel');

    // Web Push subscription management
    Route::post('push/subscribe', [ZeroPayPushController::class, 'subscribe'])->name('push.subscribe');
    Route::post('push/unsubscribe', [ZeroPayPushController::class, 'unsubscribe'])->name('push.unsubscribe');
});

Route::post('/webhook/{gateway}', [ZeroPayWebhookController::class, 'handle'])->name('webhook');
