<?php
use Illuminate\Support\Facades\Route;use Modules\ZeroPayModule\Http\Controllers\Api\ExampleApiController;
Route::get('/records',[ExampleApiController::class,'index'])->name('records.index');Route::post('/records',[ExampleApiController::class,'store'])->name('records.store');

use Modules\ZeroPayModule\Http\Controllers\Api\ModuleAgentController;

Route::post('/zeropay-module/agent/chat', [ModuleAgentController::class, 'chat'])->middleware(['auth:sanctum']);
Route::post('/zeropay-module/agent/command', [ModuleAgentController::class, 'command'])->middleware(['auth:sanctum']);
