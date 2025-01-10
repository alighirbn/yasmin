<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContractApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refreshToken');

        // Contracts resource routes
        Route::post('/contracts', [ContractApiController::class, 'store']);
        Route::get('/contracts', [ContractApiController::class, 'index']);
        Route::get('/contracts/{id}', [ContractApiController::class, 'show']);
    });
});
