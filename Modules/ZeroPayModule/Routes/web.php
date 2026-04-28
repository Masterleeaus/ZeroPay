<?php
use Illuminate\Support\Facades\Route;use Modules\ZeroPayModule\Http\Controllers\ExampleController;
Route::get('/',[ExampleController::class,'index'])->name('index');Route::post('/',[ExampleController::class,'store'])->name('store');
