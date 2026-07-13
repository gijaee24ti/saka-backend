<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\PublicFeedbackController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\RiderStockController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('public')->group(function () {
    Route::get('menus', [PublicCatalogController::class, 'menus']);
    Route::get('outlets', [PublicCatalogController::class, 'outlets']);
    Route::get('stocks', [PublicCatalogController::class, 'stocks']);
    Route::get('feedback', [PublicCatalogController::class, 'feedback']);
    Route::post('feedback', [PublicFeedbackController::class, 'store'])->middleware('throttle:10,1');
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'adminLogin'])->middleware('throttle:5,1');

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('profile', [AuthController::class, 'adminProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::apiResource('menus', MenuController::class);
        Route::apiResource('outlets', OutletController::class);
        Route::apiResource('riders', RiderController::class);
        Route::apiResource('stocks', StockController::class);
        Route::apiResource('feedback', FeedbackController::class);
    });
});

Route::prefix('rider')->group(function () {
    Route::post('login', [AuthController::class, 'riderLogin'])->middleware('throttle:5,1');

    Route::middleware(['auth:sanctum', 'rider'])->group(function () {
        Route::get('profile', [AuthController::class, 'riderProfile']);
        Route::get('outlet', [RiderStockController::class, 'outlet']);
        Route::get('stocks', [RiderStockController::class, 'index']);
        Route::patch('stocks/{stock}/availability', [RiderStockController::class, 'update']);
        Route::patch('status', [RiderStockController::class, 'status']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
