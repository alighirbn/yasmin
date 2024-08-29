<?php

use App\Http\Controllers\Map\MapController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'map'], function () {
    //index
    Route::get('/', [MapController::class, 'index'])->middleware(['auth', 'verified', 'permission:map-index'])->name('map.index');
    //index
    Route::get('/due_installments', [MapController::class, 'due_installments'])->middleware(['auth', 'verified', 'permission:map-index'])->name('map.due_installments');
});
