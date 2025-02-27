
<?php

use App\Http\Controllers\CashTransferController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'cash_transfer'], function () {

    //index
    Route::get('/', [CashTransferController::class, 'index'])->middleware(['auth', 'verified', 'permission:cash_transfer-list'])->name('cash_transfer.index');

    //create
    Route::get('/create', [CashTransferController::class, 'create'])->middleware(['auth', 'verified', 'permission:cash_transfer-create'])->name('cash_transfer.create');
    Route::post('/create', [CashTransferController::class, 'store'])->middleware(['auth', 'verified', 'permission:cash_transfer-create'])->name('cash_transfer.store');

    //show
    Route::get('/show/{url_address}', [CashTransferController::class, 'show'])->middleware(['auth', 'verified', 'permission:cash_transfer-show'])->name('cash_transfer.show');

    //update
    Route::get('/edit/{url_address}', [CashTransferController::class, 'edit'])->middleware(['auth', 'verified', 'permission:cash_transfer-update'])->name('cash_transfer.edit');
    Route::patch('/update/{url_address}', [CashTransferController::class, 'update'])->middleware(['auth', 'verified', 'permission:cash_transfer-update'])->name('cash_transfer.update');
    Route::patch('/approve/{url_address}', [CashTransferController::class, 'approve'])->middleware(['auth', 'verified', 'permission:cash_transfer-approve'])->name('cash_transfer.approve');

    //archive
    Route::get('/archiveshow/{url_address}', [CashTransferController::class, 'archiveshow'])->middleware(['auth', 'verified', 'permission:cash_transfer-update'])->name('cash_transfer.archiveshow');
    Route::get('/archive/{url_address}', [CashTransferController::class, 'archivecreate'])->middleware(['auth', 'verified', 'permission:cash_transfer-update'])->name('cash_transfer.archivecreate');
    Route::post('/archive/{url_address}', [CashTransferController::class, 'archivestore'])->middleware(['auth', 'verified', 'permission:cash_transfer-update'])->name('cash_transfer.archivestore');

    //scan
    Route::get('/scan/{url_address}', [CashTransferController::class, 'scancreate'])->middleware(['auth', 'verified', 'permission:cash_transfer-update'])->name('cash_transfer.scancreate');
    Route::post('/scan', [CashTransferController::class, 'scanstore'])->middleware(['auth', 'verified', 'permission:cash_transfer-update'])->name('cash_transfer.scanstore');


    //delete

    Route::delete('/delete/{url_address}', [CashTransferController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:cash_transfer-delete'])->name('cash_transfer.destroy');
});
