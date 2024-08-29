
<?php
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'payment'], function() {

    //index
        Route::get('/',[PaymentController::class,'index'])->middleware(['auth','verified','permission:payment-list'])->name('payment.index');

    //create
        Route::get('/create',[PaymentController::class,'create'])->middleware(['auth','verified','permission:payment-create'])->name('payment.create');
        Route::post('/create', [PaymentController::class, 'store'])->middleware(['auth','verified','permission:payment-create'])->name('payment.store');

    //show
        Route::get('/show/{url_address}',[PaymentController::class,'show'])->middleware(['auth','verified','permission:payment-show'])->name('payment.show');
    
    //update
        Route::get('/edit/{url_address}',[PaymentController::class,'edit'])->middleware(['auth','verified','permission:payment-update'])->name('payment.edit');
        Route::patch('/update/{url_address}', [PaymentController::class, 'update'])->middleware(['auth','verified','permission:payment-update'])->name('payment.update'); 
 
     //delete

        Route::delete('/delete/{url_address}', [PaymentController::class, 'destroy'])->middleware(['auth','verified','permission:payment-delete'])->name('payment.destroy');
    
});

