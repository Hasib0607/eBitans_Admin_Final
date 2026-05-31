<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentGateway\StripeController;
use App\Http\Controllers\PaymentGateway\StripeAdminController;
use App\Http\Controllers\PaymentGateway\PaypalController;
use App\Http\Controllers\PaymentGateway\PaypalAdminController;
use App\Http\Controllers\Dropship\PaypalDropshipController;
use App\Http\Controllers\Dropship\BkashDropController;
use App\Http\Controllers\Dropship\AmarpayDropController;
use App\Http\Controllers\PaymentGateway\AdminAmarPayController;
use App\Http\Controllers\PaymentGateway\AmarPayController;
use App\Http\Controllers\PaymentGateway\UddoktaPayController;
use App\Http\Controllers\PaymentGateway\AddonPayment\PaypalModulusController;
use App\Http\Controllers\PaymentGateway\AddonPayment\BkashModulusController;
use App\Http\Controllers\PaymentGateway\AddonPayment\NagadModulusController;
use App\Http\Controllers\PaymentGateway\AddonPayment\AmarpayModulusController;
use App\Http\Controllers\PaymentGateway\Merchant\BkashController;
use App\Http\Controllers\PaymentGateway\Merchant\NagadController;
use App\Http\Controllers\PaymentGateway\NagadAdminController;

Route::group(['prefix' => 'api/v1'], function () {

    /**** Stripe customer start ****/
    // View stripe payment button page
//    Route::get('/stripe-payment', [StripeController::class, 'payment'])->name('stripe.view');

    Route::get('/stripe-payment', [StripeController::class, 'createPayment'])->name('stripe.payment');
    Route::get('/stripe/success-transaction', [StripeController::class, 'successTransaction'])->name('stripe.successTransaction');
    Route::get('/stripe/cancel-transaction', [StripeController::class, 'cancelTransaction'])->name('stripe.cancelTransaction');
    /**** Stripe customer end ****/


    /*** Paypal customer start ***/
    // View stripe payment button page
//    Route::get('/paypal-payment', [PaypalController::class, 'paymentView'])->name('paypal.view');

    Route::get('/paypal-payment', [PayPalController::class, 'createPayment'])->name('paypal.payment');
    Route::get('/paypal/success-transaction', [PayPalController::class, 'successTransaction'])->name('paypal.successTransaction');
    Route::get('/paypal/cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('paypal.cancelTransaction');

    /*** Paypal customer end ***/


    /*** Amar pay start ***/
    Route::get('/apay-payment', [AmarPayController::class, 'createPayment'])->name('amarpay.payment');
    Route::post('/apay/success-transaction', [AmarPayController::class, 'successTransaction'])->name('amarpay.successTransaction');
    Route::post('/apay/failed-transaction', [AmarPayController::class, 'failedTransaction'])->name('amarpay.failedTransaction');
    Route::get('/apay/cancel-transaction', [AmarPayController::class, 'cancelTransaction'])->name('amarpay.cancelTransaction');
    /**** Amar pay end ****/


    /*** uddoktapay admin start ***/
    Route::get('/uddoktapay-payment', [UddoktaPayController::class, 'createPayment'])->name('uddoktapay.payment');
    Route::get('/uddoktapay/success-transaction', [UddoktaPayController::class, 'successTransaction'])->name('uddoktapay.successTransaction');
    Route::get('/uddoktapay/cancel-transaction', [UddoktaPayController::class, 'cancelTransaction'])->name('uddoktapay.cancelTransaction');
    Route::post('/uddoktapay/ipn-transaction', [UddoktaPayController::class, 'ipnTransaction'])->name('uddoktapay.ipnTransaction');
    /**** uddoktapay admin end ****/


    /*** Ebitans-Bkash start ***/
    Route::get('/ebitans-bkash/payment', [BkashController::class, 'createPayment'])->name('ebitans-bkash.payment');
    Route::get('/ebitans-bkash/success-transaction', [BkashController::class, 'callback'])->name('ebitans-bkash.callback');
    Route::get('/ebitans-bkash/refund', [BkashController::class, 'getRefund'])->name('ebitans-bkash.get-refund');
    Route::post('/ebitans-bkash/refund', [BkashController::class, 'refundPayment'])->name('ebitans-bkash.post-refund');
    /**** Ebitans-Bkash end ****/

    /*** Ebitans-Nagad start ***/
    Route::get('/ebitans-nagad/payment', [NagadController::class, 'createPayment'])->name('ebitans-nagad.payment');
    Route::get('/ebitans-nagad/callback', [NagadController::class, 'callback'])->name('ebitans-nagad.callback');
    Route::get('/ebitans-nagad/refund/{paymentRefId}', [NagadController::class, 'refund'])->name('ebitans-nagad.refund');
    /**** Ebitans-Nagad end ****/

});


Route::middleware(['auth', 'admin'])->group(function () {

    /**** Stripe admin start ****/
    // Store stripe credentials
    Route::post('/stripe/credentials/store', [StripeAdminController::class, 'stripeCredentials'])->name('store.stripe.credentials');

    // View stripe admin payment button page
//    Route::get('/a/stripe-payment', [StripeAdminController::class, 'paymentView'])->name('stripe.admin.view');

    Route::get('/a/stripe-payment', [StripeAdminController::class, 'createPayment'])->name('stripe.admin.payment');
    Route::get('/a/stripe/success-transaction', [StripeAdminController::class, 'successTransaction'])->name('stripe.admin.successTransaction');
    Route::get('/a/stripe/cancel-transaction', [StripeAdminController::class, 'cancelTransaction'])->name('stripe.admin.cancelTransaction');
    /**** Stripe admin end ****/


    /*** Paypal admin start ***/
    // Store paypal credentials
    Route::post('/paypal/credentials/store', [PaypalAdminController::class, 'paypalCredentials'])->name('store.paypal.credentials');

    // View stripe payment button page
//    Route::get('/a/paypal-payment', [PaypalAdminController::class, 'paymentView'])->name('paypal.admin.view');

    Route::get('/a/paypal-payment', [PaypalAdminController::class, 'createPayment'])->name('paypal.admin.payment');
    Route::get('/a/paypal/success-transaction', [PaypalAdminController::class, 'successTransaction'])->name('paypal.admin.successTransaction');
    Route::get('/a/paypal/cancel-transaction', [PaypalAdminController::class, 'cancelTransaction'])->name('paypal.admin.cancelTransaction');
    /*** Paypal admin end ***/

    /*** Amar pay admin start ***/
    // Store paypal credentials

    Route::get('/a/apay-payment', [AdminAmarPayController::class, 'createPayment'])->name('amarpay.admin.payment');
    Route::post('/a/apay/success-transaction', [AdminAmarPayController::class, 'successTransaction'])->name('amarpay.admin.successTransaction');
    Route::post('/a/apay/failed-transaction', [AdminAmarPayController::class, 'failedTransaction'])->name('amarpay.admin.failedTransaction');
    Route::get('/a/apay/cancel-transaction', [AdminAmarPayController::class, 'cancelTransaction'])->name('amarpay.admin.cancelTransaction');
    /**** Amar pay admin end ****/


    // Store stripe credentials
    Route::post('/uddoktapay/credentials/store', [UddoktaPayController::class, 'uddoktapayCredentials'])->name('store.uddoktapay.credentials');


    /*** Nagad Admin start ***/
    Route::get('/a/nagad/payment', [NagadAdminController::class, 'createPayment'])->name('nagad.admin.payment');
    Route::get('/a/nagad/callback', [NagadAdminController::class, 'callback'])->name('nagad.admin.callback');
    Route::get('/a/nagad/refund/{paymentRefId}', [NagadAdminController::class, 'refund'])->name('nagad.admin.refund');
    /**** Nagad Admin  end ****/
});


// Dropshipper payment route
Route::middleware(['auth', 'admin'])->group(function () {

    /*** paypal dropship payment start ***/
    Route::get('/d/paypal-payment', [PaypalDropshipController::class, 'createPayment'])->name('paypal.dropshipper.payment');
    Route::get('/d/paypal/success-transaction', [PaypalDropshipController::class, 'successTransaction'])->name('paypal.dropshipper.successTransaction');
    Route::get('/d/paypal/cancel-transaction', [PaypalDropshipController::class, 'cancelTransaction'])->name('paypal.dropshipper.cancelTransaction');
    /*** paypal dropship payment end ***/


    /*** bkash dropship payment start ***/
    Route::get('/d/bkash/create-payment', [BkashDropController::class, 'createPayment'])->name('bkash.dropshipper.payment');
    Route::get('/d/bkash/success-transaction', [BkashDropController::class, 'successTransaction'])->name('bkash.dropshipper.successTransaction');
    /*** bkash dropship payment end ***/

    /*** amarpay dropship payment start ***/
    Route::get('/d/amarpay/create-payment', [AmarpayDropController::class, 'createPayment'])->name('amarpay.dropshipper.payment');
    Route::post('/d/amarpay/success-transaction', [AmarpayDropController::class, 'successTransaction'])->name('amarpay.dropshipper.successTransaction');
    Route::post('/d/amarpay/failed-transaction', [AmarpayDropController::class, 'failedTransaction'])->name('amarpay.dropshipper.failedTransaction');
    Route::get('/d/amarpay/cancel-transaction', [AmarpayDropController::class, 'cancelTransaction'])->name('amarpay.dropshipper.cancelTransaction');

    /*** amarpay dropship payment end ***/

});


// Modulus payment route
Route::middleware(['auth'])->group(function () {

    /*** paypal modulus payment start ***/
    Route::get('/modulus/paypal-payment', [PaypalModulusController::class, 'createPayment'])->name('paypal.modulus.payment');
    Route::get('/modulus/paypal/success-transaction', [PaypalModulusController::class, 'successTransaction'])->name('paypal.modulus.successTransaction');
    Route::get('/modulus/paypal/cancel-transaction', [PaypalModulusController::class, 'cancelTransaction'])->name('paypal.modulus.cancelTransaction');
    /*** paypal modulus payment end ***/


    /*** bkash modulus payment start ***/
    Route::get('/modulus/bkash/create-payment', [BkashModulusController::class, 'createPayment'])->name('bkash.modulus.payment');
    Route::get('/modulus/bkash/success-transaction', [BkashModulusController::class, 'successTransaction'])->name('bkash.modulus.successTransaction');
    /*** bkash modulus payment end ***/

    /*** amarpay modulus payment start ***/
    Route::get('/modulus/amarpay/create-payment', [AmarpayModulusController::class, 'createPayment'])->name('amarpay.modulus.payment');
    Route::post('/modulus/amarpay/success-transaction', [AmarpayModulusController::class, 'successTransaction'])->name('amarpay.modulus.successTransaction');
    Route::post('/modulus/amarpay/failed-transaction', [AmarpayModulusController::class, 'failedTransaction'])->name('amarpay.modulus.failedTransaction');
    Route::get('/modulus/amarpay/cancel-transaction', [AmarpayModulusController::class, 'cancelTransaction'])->name('amarpay.modulus.cancelTransaction');
    /*** amarpay modulus payment end ***/


    /*** Nagad Admin start ***/
    Route::get('/modulus/nagad/payment', [NagadModulusController::class, 'createPayment'])->name('nagad.modulus.payment');
    Route::get('/modulus/nagad/callback', [NagadModulusController::class, 'callback'])->name('nagad.modulus.callback');
    Route::get('/modulus/nagad/refund/{paymentRefId}', [NagadModulusController::class, 'refund'])->name('nagad.modulus.refund');
    /**** Nagad Admin  end ****/

});


