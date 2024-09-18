<?php

use App\Http\Controllers\Map\MapController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'map'], function () {
    //map
    Route::get('/map', [MapController::class, 'map'])->middleware(['auth', 'verified', 'permission:map-map'])->name('map.map');


    //contract
    Route::get('/contract', [MapController::class, 'contract'])->middleware(['auth', 'verified', 'permission:map-contract'])->name('map.contract');


    //empty
    Route::get('/empty', [MapController::class, 'empty'])->middleware(['auth', 'verified', 'permission:map-empty'])->name('map.empty');
    //draw
    Route::get('/draw', [MapController::class, 'draw'])->middleware(['auth', 'verified', 'permission:map-draw'])->name('map.draw');

    //edit
    Route::get('/edit', [MapController::class, 'edit'])->middleware(['auth', 'verified', 'permission:map-edit'])->name('map.edit');

    //index
    Route::get('/due', [MapController::class, 'due'])->middleware(['auth', 'verified', 'permission:map-due'])->name('map.due');
});
