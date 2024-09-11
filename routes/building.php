
<?php

use App\Http\Controllers\BuildingController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'building'], function () {

    //index
    Route::get('/', [BuildingController::class, 'index'])->middleware(['auth', 'verified', 'permission:building-list'])->name('building.index');

    //create
    Route::get('/create', [BuildingController::class, 'create'])->middleware(['auth', 'verified', 'permission:building-create'])->name('building.create');
    Route::post('/create', [BuildingController::class, 'store'])->middleware(['auth', 'verified', 'permission:building-create'])->name('building.store');

    //show
    Route::get('/show/{url_address}', [BuildingController::class, 'show'])->middleware(['auth', 'verified', 'permission:building-show'])->name('building.show');

    //update
    Route::get('/edit/{url_address}', [BuildingController::class, 'edit'])->middleware(['auth', 'verified', 'permission:building-update'])->name('building.edit');
    Route::patch('/update/{url_address}', [BuildingController::class, 'update'])->middleware(['auth', 'verified', 'permission:building-update'])->name('building.update');
    Route::post('/update-building-coordinates/{id}', [BuildingController::class, 'updateCoordinates'])->middleware(['auth', 'verified', 'permission:building-update'])->name('building.updateCoordinates');

    //delete

    Route::delete('/delete/{url_address}', [BuildingController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:building-delete'])->name('building.destroy');
});
