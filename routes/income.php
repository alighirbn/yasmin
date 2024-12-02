
<?php

use App\Http\Controllers\IncomeController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'income'], function () {

    //index
    Route::get('/', [IncomeController::class, 'index'])->middleware(['auth', 'verified', 'permission:income-list'])->name('income.index');

    //create
    Route::get('/create', [IncomeController::class, 'create'])->middleware(['auth', 'verified', 'permission:income-create'])->name('income.create');
    Route::post('/create', [IncomeController::class, 'store'])->middleware(['auth', 'verified', 'permission:income-create'])->name('income.store');

    //show
    Route::get('/show/{url_address}', [IncomeController::class, 'show'])->middleware(['auth', 'verified', 'permission:income-show'])->name('income.show');
    //show
    Route::get('/pending/{url_address}', [IncomeController::class, 'pending'])->middleware(['auth', 'verified', 'permission:income-show'])->name('income.pending');

    //update
    Route::get('/edit/{url_address}', [IncomeController::class, 'edit'])->middleware(['auth', 'verified', 'permission:income-update'])->name('income.edit');
    Route::patch('/update/{url_address}', [IncomeController::class, 'update'])->middleware(['auth', 'verified', 'permission:income-update'])->name('income.update');
    Route::patch('/approve/{url_address}', [IncomeController::class, 'approve'])->middleware(['auth', 'verified', 'permission:income-approve'])->name('income.approve');

    //delete

    Route::delete('/delete/{url_address}', [IncomeController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:income-delete'])->name('income.destroy');
});
