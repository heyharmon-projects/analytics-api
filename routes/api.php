<?php

use Illuminate\Support\Facades\Route;
use DDD\Http\Services\Google\GoogleAuthController;
use DDD\Http\Services\GoogleAnalytics\GoogleAnalyticsDataController;
use DDD\Http\Services\GoogleAnalytics\GoogleAnalyticsAdminController;
use DDD\Http\Connections\ConnectionController;
use DDD\Http\Funnels\FunnelController;

Route::middleware('auth:sanctum')->group(function() {
    
    // Google Auth
    Route::prefix('google')->group(function() {
        Route::post('connect', [GoogleAuthController::class, 'connect']);
        Route::post('callback', [GoogleAuthController::class, 'callback']);
    });

    // Google Analytics admin
    Route::prefix('ga')->group(function() {
        Route::post('accounts', [GoogleAnalyticsAdminController::class, 'listAccounts']);
    });

    // Google Analytics data
    Route::prefix('ga')->group(function() {
        Route::get('funnel/{connection}', [GoogleAnalyticsDataController::class, 'runFunnelReport']);
        Route::get('report/{connection}', [GoogleAnalyticsDataController::class, 'runReport']);
    });

    Route::prefix('{organization:slug}')->scopeBindings()->group(function() {
        // Connections
        Route::prefix('connections')->group(function() {
            Route::get('', [ConnectionController::class, 'index']);
            Route::post('', [ConnectionController::class, 'store']);
        });

        // Funnels
        Route::prefix('funnels')->group(function() {
            Route::get('/', [FunnelController::class, 'index']);
            Route::post('/', [FunnelController::class, 'store']);
            Route::get('/{funnel}', [FunnelController::class, 'show']);
            Route::put('/{funnel}', [FunnelController::class, 'update']);
            Route::delete('/{funnel}', [FunnelController::class, 'destroy']);
        });
    }); 
});