
<?php

use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'contract'], function () {

    //index
    Route::get('/', [ContractController::class, 'index'])->middleware(['auth', 'verified', 'permission:contract-list'])->name('contract.index');

    //customercreate
    Route::post('/customercreate', [ContractController::class, 'customerstore'])->middleware(['auth', 'verified', 'permission:customer-create'])->name('contract.customerstore');

    //create
    Route::get('/create', [ContractController::class, 'create'])->middleware(['auth', 'verified', 'permission:contract-create'])->name('contract.create');
    Route::post('/create', [ContractController::class, 'store'])->middleware(['auth', 'verified', 'permission:contract-create'])->name('contract.store');

    //transfer
    Route::get('/transfer/{url_address}', [ContractController::class, 'transfer'])->middleware(['auth', 'verified', 'permission:contract-transfer'])->name('contract.transfer');
    Route::post('/transfer/{url_address}', [ContractController::class, 'transferstore'])->middleware(['auth', 'verified', 'permission:contract-transfer'])->name('contract.transferstore');

    //show
    Route::get('/show/{url_address}', [ContractController::class, 'show'])->middleware(['auth', 'verified', 'permission:contract-show'])->name('contract.show');
    Route::get('/add/{url_address}', [ContractController::class, 'add_payment'])->middleware(['auth', 'verified', 'permission:contract-show'])->name('contract.add');
    Route::get('/print/{url_address}', [ContractController::class, 'print'])->middleware(['auth', 'verified', 'permission:contract-print'])->name('contract.print');
    Route::get('/temp/{url_address}', [ContractController::class, 'temp'])->middleware(['auth', 'verified', 'permission:contract-create'])->name('contract.temp');
    //update
    Route::get('/edit/{url_address}', [ContractController::class, 'edit'])->middleware(['auth', 'verified', 'permission:contract-update'])->name('contract.edit');
    Route::patch('/update/{url_address}', [ContractController::class, 'update'])->middleware(['auth', 'verified', 'permission:contract-update'])->name('contract.update');

    //accept 
    Route::get('/accept/{url_address}', [ContractController::class, 'accept'])->middleware(['auth', 'verified', 'permission:contract-accept'])->name('contract.accept');

    //authenticat 
    Route::get('/authenticat/{url_address}', [ContractController::class, 'authenticat'])->middleware(['auth', 'verified', 'permission:contract-authenticat'])->name('contract.authenticat');

    //archive
    Route::get('/archiveshow/{url_address}', [ContractController::class, 'archiveshow'])->middleware(['auth', 'verified', 'permission:contract-archiveshow'])->name('contract.archiveshow');
    Route::get('/archive/{url_address}', [ContractController::class, 'archivecreate'])->middleware(['auth', 'verified', 'permission:contract-archive'])->name('contract.archivecreate');
    Route::post('/archive/{url_address}', [ContractController::class, 'archivestore'])->middleware(['auth', 'verified', 'permission:contract-archive'])->name('contract.archivestore');

    //scan
    Route::get('/scan/{url_address}', [ContractController::class, 'scancreate'])->middleware(['auth', 'verified', 'permission:contract-archive'])->name('contract.scancreate');
    Route::post('/scan', [ContractController::class, 'scanstore'])->middleware(['auth', 'verified', 'permission:contract-archive'])->name('contract.scanstore');

    //statement
    Route::get('/statement/{url_address}', [ContractController::class, 'statement'])->middleware(['auth', 'verified', 'permission:contract-statement'])->name('contract.statement');

    //statement
    Route::get('/due/{contract_id?}', [ContractController::class, 'dueInstallments'])->middleware(['auth', 'verified', 'permission:contract-due'])->name('contract.due');


    //delete
    Route::delete('/delete/{url_address}', [ContractController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:contract-delete'])->name('contract.destroy');
});
