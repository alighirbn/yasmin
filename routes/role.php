<?php
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'role'], function() {
    //index
    Route::get('/',[RoleController::class,'index'])->middleware(['auth','verified','permission:role-list'])->name('role.index');
    //create
    Route::get('/create',[RoleController::class,'create'])->middleware(['auth','verified','permission:role-create'])->name('role.create');
    Route::post('/create', [RoleController::class, 'store'])->middleware(['auth','verified','permission:role-create'])->name('role.store');
    //show
    Route::get('/show/{id}',[RoleController::class,'show'])->middleware(['auth','verified','permission:role-show'])->name('role.show');
    //update
    Route::get('/edit/{id}',[RoleController::class,'edit'])->middleware(['auth','verified','permission:role-update'])->name('role.edit');
    Route::patch('/update/{id}', [RoleController::class, 'update'])->middleware(['auth','verified','permission:role-update'])->name('role.update');
    //delete
    Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->middleware(['auth','verified','permission:role-delete'])->name('role.destroy');
    
});