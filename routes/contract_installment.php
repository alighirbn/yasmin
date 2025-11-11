<?php

use App\Http\Controllers\ContractInstallmentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'contract/installment', 'middleware' => ['auth', 'verified']], function () {

    // Single installment operations
    Route::get('/edit/{url_address}', [ContractInstallmentController::class, 'edit'])
        ->middleware('permission:contract-update')
        ->name('contract.installment.edit');

    Route::patch('/update/{url_address}', [ContractInstallmentController::class, 'update'])
        ->middleware('permission:contract-update')
        ->name('contract.installment.update');

    Route::delete('/delete/{url_address}', [ContractInstallmentController::class, 'destroy'])
        ->middleware('permission:contract-delete')
        ->name('contract.installment.destroy');

    // Bulk operations
    Route::get('/edit-bulk/{contract_url_address}', [ContractInstallmentController::class, 'editBulk'])
        ->middleware('permission:contract-update')
        ->name('contract.installment.edit_bulk');

    Route::patch('/update-bulk/{contract_url_address}', [ContractInstallmentController::class, 'updateBulk'])
        ->middleware('permission:contract-update')
        ->name('contract.installment.update_bulk');

    // Add new installment
    Route::get('/create/{contract_url_address}', [ContractInstallmentController::class, 'create'])
        ->middleware('permission:contract-create')
        ->name('contract.installment.create');

    Route::post('/store/{contract_url_address}', [ContractInstallmentController::class, 'store'])
        ->middleware('permission:contract-create')
        ->name('contract.installment.store');
});
