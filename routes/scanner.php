<?php

use App\Http\Controllers\ScannerController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'scan'], function () {
    //index
    Route::get('/', [ScannerController::class, 'index'])->name('scan.index');
    Route::post('/scan', [ScannerController::class, 'scan'])->name('scan.scan');
});
