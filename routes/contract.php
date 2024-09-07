
<?php

use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'contract'], function () {

    //index
    Route::get('/', [ContractController::class, 'index'])->middleware(['auth', 'verified', 'permission:contract-list'])->name('contract.index');

    //customercreate
    Route::get('/customercreate', [ContractController::class, 'customercreate'])->middleware(['auth', 'verified', 'permission:customer-create'])->name('contract.customercreate');
    Route::post('/customercreate', [ContractController::class, 'customerstore'])->middleware(['auth', 'verified', 'permission:customer-create'])->name('contract.customerstore');

    //create
    Route::get('/create/{customer_id?}', [ContractController::class, 'create'])->middleware(['auth', 'verified', 'permission:contract-create'])->name('contract.create');
    Route::post('/create', [ContractController::class, 'store'])->middleware(['auth', 'verified', 'permission:contract-create'])->name('contract.store');

    //transfer
    Route::get('/transfer/{url_address}', [ContractController::class, 'transfer'])->middleware(['auth', 'verified', 'permission:contract-transfer'])->name('contract.transfer');
    Route::post('/transfer/{url_address}', [ContractController::class, 'transferstore'])->middleware(['auth', 'verified', 'permission:contract-transfer'])->name('contract.transferstore');

    //show
    Route::get('/show/{url_address}', [ContractController::class, 'show'])->middleware(['auth', 'verified', 'permission:contract-show'])->name('contract.show');
    Route::get('/add/{url_address}', [ContractController::class, 'add_payment'])->middleware(['auth', 'verified', 'permission:contract-show'])->name('contract.add');
    Route::get('/print/{url_address}', [ContractController::class, 'print'])->middleware(['auth', 'verified', 'permission:contract-print'])->name('contract.print');
    //update
    Route::get('/edit/{url_address}', [ContractController::class, 'edit'])->middleware(['auth', 'verified', 'permission:contract-update'])->name('contract.edit');
    Route::patch('/update/{url_address}', [ContractController::class, 'update'])->middleware(['auth', 'verified', 'permission:contract-update'])->name('contract.update');

    //statement
    Route::get('/statement/{url_address}', [ContractController::class, 'statement'])->middleware(['auth', 'verified', 'permission:contract-statement'])->name('contract.statement');

    //statement
    Route::get('/due/{contract_id?}', [ContractController::class, 'dueInstallments'])->middleware(['auth', 'verified', 'permission:contract-due'])->name('contract.due');


    //delete
    Route::delete('/delete/{url_address}', [ContractController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:contract-delete'])->name('contract.destroy');
});
