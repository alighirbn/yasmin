<?php

use App\Http\Controllers\ModelHistoryController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'history'], function () {

    Route::get('/all', [ModelHistoryController::class, 'index'])
        ->middleware(['auth', 'verified', 'permission:history-list'])
        ->name('history.all');

    // Route for the current user's history logs
    Route::get('/user', [ModelHistoryController::class, 'userHistory'])
        ->middleware(['auth', 'verified', 'permission:history-user'])
        ->name('history.user');
});
