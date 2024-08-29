<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user'], function() {
    //index
    Route::get('/',[UserController::class,'index'])->middleware(['auth','verified','permission:user-list'])->name('user.index');
    //create
    Route::get('/create',[UserController::class,'create'])->middleware(['auth','verified','permission:user-create'])->name('user.create');
    Route::post('/create', [UserController::class, 'store'])->middleware(['auth','verified','permission:user-create'])->name('user.store');
    //show
    Route::get('/show/{url_address}',[UserController::class,'show'])->middleware(['auth','verified','permission:user-show'])->name('user.show');
    //update
    Route::get('/edit/{url_address}',[UserController::class,'edit'])->middleware(['auth','verified','permission:user-update'])->name('user.edit');
    Route::patch('/update/{url_address}', [UserController::class, 'update'])->middleware(['auth','verified','permission:user-update'])->name('user.update');
    //delete
    Route::delete('/delete/{url_address}', [UserController::class, 'destroy'])->middleware(['auth','verified','permission:user-delete'])->name('user.destroy');
    
});