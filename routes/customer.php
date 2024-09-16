
<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'customer'], function () {

    //index
    Route::get('/', [CustomerController::class, 'index'])->middleware(['auth', 'verified', 'permission:customer-list'])->name('customer.index');

    //create
    Route::get('/create', [CustomerController::class, 'create'])->middleware(['auth', 'verified', 'permission:customer-create'])->name('customer.create');
    Route::post('/create', [CustomerController::class, 'store'])->middleware(['auth', 'verified', 'permission:customer-create'])->name('customer.store');

    //show
    Route::get('/show/{url_address}', [CustomerController::class, 'show'])->middleware(['auth', 'verified', 'permission:customer-show'])->name('customer.show');

    //statement
    Route::get('/statement/{url_address}', [CustomerController::class, 'statement'])->middleware(['auth', 'verified', 'permission:customer-statement'])->name('customer.statement');

    //update
    Route::get('/edit/{url_address}', [CustomerController::class, 'edit'])->middleware(['auth', 'verified', 'permission:customer-update'])->name('customer.edit');
    Route::patch('/update/{url_address}', [CustomerController::class, 'update'])->middleware(['auth', 'verified', 'permission:customer-update'])->name('customer.update');

    //delete

    Route::delete('/delete/{url_address}', [CustomerController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:customer-delete'])->name('customer.destroy');
});
