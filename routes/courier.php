<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courier\CourierController;
use App\Http\Controllers\Courier\PathaoController;


Route::middleware(['isModulusAccess:123'])->group(function () {
    Route::middleware(['auth', 'otpverify', 'store', 'activestore', 'checkplan', 'admin'])->name('courier.')->group(function () {
        // Courier index
        Route::get('/courier', [CourierController::class, 'index'])->name('index');

        // Courier page show
        Route::get('/courier/{name}', [CourierController::class, 'courierPage'])->name('courierPage');

        // Courier data store
        Route::post('/courier/{name}', [CourierController::class, 'courierStore'])->name('courierStore');

        // Create pathao parcel
        Route::post('/courier/pathao/order', [CourierController::class, 'createPathaoOrder'])->name('createPathaoOrder');
        Route::get('/courier/pathao/zone/{id}', [CourierController::class, 'getPathaoZone'])->name('getPathaoZone');
        Route::get('/courier/pathao/area/{id}', [CourierController::class, 'getPathaoArea'])->name('getPathaoArea');

        // Create Steadfast parcel
        Route::post('/courier/steadfast/order', [CourierController::class, 'createSteadfastOrder'])->name('createSteadfastOrder');

        // Create Ecourier parcel
        Route::post('/courier/ecourier/order', [CourierController::class, 'createEcourierOrder'])->name('createEcourierOrder');

        // Create Redx parcel
        Route::post('/courier/redx/order', [CourierController::class, 'createRedxOrder'])->name('createRedxOrder');

    });

    // Get pathao webhook signature
    Route::get('/pathao/webhook/signature', [PathaoController::class, 'craeteSignature'])->name('pathao.webhook.signature');

});


// Get pathao webhook request
Route::post('/webhook/pathao', [PathaoController::class, 'handleWebhook']);

