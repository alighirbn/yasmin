
<?php

use App\Http\Controllers\ContractTransferHistoryController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'transfer'], function () {

    //index
    Route::get('/', [ContractTransferHistoryController::class, 'index'])->middleware(['auth', 'verified', 'permission:transfer-list'])->name('transfer.index');

    //customercreate

    Route::post('/customercreate', [ContractTransferHistoryController::class, 'customerstore'])->middleware(['auth', 'verified', 'permission:customer-create'])->name('transfer.customerstore');

    //create
    Route::get('/create/{contract_id?}', [ContractTransferHistoryController::class, 'create'])->middleware(['auth', 'verified', 'permission:transfer-create'])->name('transfer.create');
    Route::post('/create', [ContractTransferHistoryController::class, 'store'])->middleware(['auth', 'verified', 'permission:transfer-create'])->name('transfer.store');

    //show
    Route::get('/show/{url_address}', [ContractTransferHistoryController::class, 'show'])->middleware(['auth', 'verified', 'permission:transfer-show'])->name('transfer.show');
    Route::get('/add/{url_address}', [ContractTransferHistoryController::class, 'add_payment'])->middleware(['auth', 'verified', 'permission:transfer-show'])->name('transfer.add');
    Route::get('/print/{url_address}', [ContractTransferHistoryController::class, 'print'])->middleware(['auth', 'verified', 'permission:transfer-print'])->name('transfer.print');
    Route::get('/contract/{url_address}', [ContractTransferHistoryController::class, 'showTransfersForContract'])->middleware(['auth', 'verified', 'permission:transfer-show'])->name('transfer.contract');
    //update
    Route::get('/edit/{url_address}', [ContractTransferHistoryController::class, 'edit'])->middleware(['auth', 'verified', 'permission:transfer-update'])->name('transfer.edit');
    Route::patch('/update/{url_address}', [ContractTransferHistoryController::class, 'update'])->middleware(['auth', 'verified', 'permission:transfer-update'])->name('transfer.update');
    Route::patch('/approve/{url_address}', [ContractTransferHistoryController::class, 'approve'])->middleware(['auth', 'verified', 'permission:transfer-approve'])->name('transfer.approve');

    //delete

    Route::delete('/delete/{url_address}', [ContractTransferHistoryController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:transfer-delete'])->name('transfer.destroy');
});
