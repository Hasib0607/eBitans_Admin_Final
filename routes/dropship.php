<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\Dropship\DropshipperController;

Route::middleware(['auth', 'superadmin'])->name('superadmin.')->group(function () {

    // All dropship user
    Route::get('/dropshipper', [DropshipperController::class, 'index'])->name('dropshipper');

    // All dropship user
    Route::get('/dropshipper/overflow', [DropshipperController::class, 'overFlowList'])->name('overflow.list');

    // Change dropship commission
    Route::post('/dropshipper/commission/update', [DropshipperController::class, 'commissionUpdate'])->name('update.dropship.commission');

    // Change dropship order pull
    Route::post('/dropshipper/order-pull/update', [DropshipperController::class, 'orderPullUpdate'])->name('update.dropship.order.pull');

    // Change dropship overflow commission
    Route::post('/dropshipper/overflow-commission/update', [DropshipperController::class, 'overflowCommissionUpdate'])->name('update.dropship.order.overflow.commission');

    // Change dropship overflow commission
    Route::get('/dropshipper/store/order/{id}', [DropshipperController::class, 'storeOrderDetails'])->name('dropship.order.details');
});


Route::middleware(['auth', 'otpverify', 'store', 'activestore', 'checkplan', 'admin'])->name('dropshipper.')->group(function () {
    // Dropship commission
    Route::get('/sell/commission', [DropshipperController::class, 'dropshipCommission'])->name('dropship.commission');

    // Dropship commission
    Route::post('/sell/commission/pay', [DropshipperController::class, 'dropshipCommissionPay'])->name('dropship.commission.pay');

});

