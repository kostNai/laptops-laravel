<?php

use App\Http\Controllers\Api\AdditionalController;
use App\Http\Controllers\Api\CpuController;
use App\Http\Controllers\Api\DisplayController;
use App\Http\Controllers\Api\GraphicController;
use App\Http\Controllers\Api\MemoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/products',ProductController::class);
Route::resource('/cpu',CpuController::class);
Route::resource('/display',DisplayController::class);
Route::resource('/graphics',GraphicController::class);
Route::resource('/memories',MemoryController::class);
Route::resource('/rams',RamController::class);

Route::controller(AdditionalController::class)->group(function (){
    Route::get('/products-by-cpu','getProductsByCpu');
    Route::get('/products-by-display','getProductsByDisplay');
    Route::get('/products-by-graphic','getProductsByGraphic');
    Route::get('/products-by-memory','getProductsByMemory');
    Route::get('/products-by-ram','getProductsByRam');
});
