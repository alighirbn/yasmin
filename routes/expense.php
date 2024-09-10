
<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'expense'], function () {

    //index
    Route::get('/', [ExpenseController::class, 'index'])->middleware(['auth', 'verified', 'permission:expense-list'])->name('expense.index');

    //create
    Route::get('/create', [ExpenseController::class, 'create'])->middleware(['auth', 'verified', 'permission:expense-create'])->name('expense.create');
    Route::post('/create', [ExpenseController::class, 'store'])->middleware(['auth', 'verified', 'permission:expense-create'])->name('expense.store');

    //show
    Route::get('/show/{url_address}', [ExpenseController::class, 'show'])->middleware(['auth', 'verified', 'permission:expense-show'])->name('expense.show');
    //show
    Route::get('/pending/{url_address}', [ExpenseController::class, 'pending'])->middleware(['auth', 'verified', 'permission:expense-show'])->name('expense.pending');

    //update
    Route::get('/edit/{url_address}', [ExpenseController::class, 'edit'])->middleware(['auth', 'verified', 'permission:expense-update'])->name('expense.edit');
    Route::patch('/update/{url_address}', [ExpenseController::class, 'update'])->middleware(['auth', 'verified', 'permission:expense-update'])->name('expense.update');
    Route::patch('/approve/{url_address}', [ExpenseController::class, 'approve'])->middleware(['auth', 'verified', 'permission:expense-approve'])->name('expense.approve');

    //delete

    Route::delete('/delete/{url_address}', [ExpenseController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:expense-delete'])->name('expense.destroy');
});
