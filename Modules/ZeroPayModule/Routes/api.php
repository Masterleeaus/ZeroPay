<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ZeroPayModule API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and are prefixed
| with /api/zeropay. Add module-specific API routes here.
|
*/

Route::get('/status', function () {
    return response()->json(['module' => 'ZeroPayModule', 'status' => 'active']);
})->name('status');
