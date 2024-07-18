<?php

use App\Http\Controllers\Api\CpuController;
use App\Http\Controllers\Api\DisplayController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/products',ProductController::class);
Route::resource('/cpu',CpuController::class);
Route::resource('/display',DisplayController::class);
