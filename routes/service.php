
<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'service'], function () {

    //index
    Route::get('/', [ServiceController::class, 'index'])->middleware(['auth', 'verified', 'permission:service-list'])->name('service.index');

    //create
    Route::get('/create', [ServiceController::class, 'create'])->middleware(['auth', 'verified', 'permission:service-create'])->name('service.create');
    Route::post('/create', [ServiceController::class, 'store'])->middleware(['auth', 'verified', 'permission:service-create'])->name('service.store');

    //show
    Route::get('/show/{url_address}', [ServiceController::class, 'show'])->middleware(['auth', 'verified', 'permission:service-show'])->name('service.show');

    //update
    Route::get('/edit/{url_address}', [ServiceController::class, 'edit'])->middleware(['auth', 'verified', 'permission:service-update'])->name('service.edit');
    Route::patch('/update/{url_address}', [ServiceController::class, 'update'])->middleware(['auth', 'verified', 'permission:service-update'])->name('service.update');

    //delete

    Route::delete('/delete/{url_address}', [ServiceController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:service-delete'])->name('service.destroy');
});
