
<?php

use App\Http\Controllers\CashAccountController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'cash_account'], function () {

    //index
    Route::get('/', [CashAccountController::class, 'index'])->middleware(['auth', 'verified', 'permission:cash_account-list'])->name('cash_account.index');

    //create
    Route::get('/create', [CashAccountController::class, 'create'])->middleware(['auth', 'verified', 'permission:cash_account-create'])->name('cash_account.create');
    Route::post('/create', [CashAccountController::class, 'store'])->middleware(['auth', 'verified', 'permission:cash_account-create'])->name('cash_account.store');

    //show
    Route::get('/show/{url_address}', [CashAccountController::class, 'show'])->middleware(['auth', 'verified', 'permission:cash_account-show'])->name('cash_account.show');

    //update
    Route::get('/edit/{url_address}', [CashAccountController::class, 'edit'])->middleware(['auth', 'verified', 'permission:cash_account-update'])->name('cash_account.edit');
    Route::patch('/update/{url_address}', [CashAccountController::class, 'update'])->middleware(['auth', 'verified', 'permission:cash_account-update'])->name('cash_account.update');

    //delete

    Route::delete('/delete/{url_address}', [CashAccountController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:cash_account-delete'])->name('cash_account.destroy');
});
