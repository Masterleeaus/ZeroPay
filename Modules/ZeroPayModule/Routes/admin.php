<?php
use Illuminate\Support\Facades\Route;use Modules\ZeroPayModule\Http\Controllers\Admin\ExampleAdminController;
Route::get('/dashboard',[ExampleAdminController::class,'dashboard'])->name('dashboard');
