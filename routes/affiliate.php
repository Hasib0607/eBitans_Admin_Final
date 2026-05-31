<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\Affiliate\AffiliatePaymentController;
use App\Http\Controllers\SuperAdmin\Affiliate\AffiliateFAQController;
use App\Http\Controllers\SuperAdmin\Affiliate\AffiliateQuestionController;
use App\Http\Controllers\SuperAdmin\Affiliate\AffiliateQuestionAnswerController;
use App\Http\Controllers\SuperAdmin\Affiliate\AffiliateController;
use App\Http\Controllers\Affiliate\AmarpayAffiliateController;
use App\Http\Controllers\Affiliate\PaypalAffiliateController;


Route::middleware(['auth', 'superadmin'])->name('affiliate.')->group(function () {

    // Affiliate all question
    Route::get('/affiliate-questions', [AffiliateQuestionController::class, 'index'])->name('questions.index');
    Route::get('/affiliate-questions/create', [AffiliateQuestionController::class, 'create'])->name('questions.create');
    Route::post('/affiliate-questions/store', [AffiliateQuestionController::class, 'store'])->name('questions.store');
    Route::get('/affiliate-questions/edit/{id}', [AffiliateQuestionController::class, 'edit'])->name('questions.edit');
    Route::post('/affiliate-questions/update/{id}', [AffiliateQuestionController::class, 'update'])->name('questions.update');
    Route::get('/affiliate-questions/delete/{id}', [AffiliateQuestionController::class, 'destroy'])->name('questions.delete');
    Route::get('/affiliate-questions/status', [AffiliateQuestionController::class, 'status'])->name('questions.status');
    Route::post('/affiliate-questions/action', [AffiliateQuestionController::class, 'changeQuestionAction'])->name('questions.action');

    // Affiliate question answer
    Route::get('/affiliate-question-answer', [AffiliateQuestionAnswerController::class, 'index'])->name('questions.answers.index');
    Route::get('/affiliate-questions-answers-show/{uid}', [AffiliateQuestionAnswerController::class, 'show'])->name('questions.answers.show');
    // Route::get('/affiliate-questions-answers-result', [AffiliateQuestionAnswerController::class, 'show'])->name('questions.answers.result');
    Route::post('/affiliate-user-status', [AffiliateQuestionAnswerController::class, 'userStatusChange'])->name('user.status');

    // Affiliate payment
    Route::get('/affiliate-payment-list', [AffiliatePaymentController::class, 'index'])->name('payment.lists');
    Route::get('/affiliate-payment-lists/approved', [AffiliatePaymentController::class, 'approved'])->name('payment.lists.approved');
    Route::get('/affiliate-payment-lists/rejected', [AffiliatePaymentController::class, 'rejected'])->name('payment.lists.rejected');
    Route::post('/affiliate-payment/charge', [AffiliatePaymentController::class, 'affiliatePaymentCharge'])->name('payment.charge');

    // Affiliate Withdraw
    Route::get('/affiliate-withdraw', [AffiliatePaymentController::class, 'withdraw'])->name('withdraw.pending');
    //Route::get('/affiliate-withdraw-status', [AffiliatePaymentController::class, 'withdrawStatusChange'])->name('withdraw.status.approve');
    Route::get('/affiliate-withdraw-lists/approved', [AffiliatePaymentController::class, 'withdrawApproved'])->name('withdraw.status.approved');
    Route::get('/affiliate-withdraw-lists/rejected', [AffiliatePaymentController::class, 'withdrawRejected'])->name('withdraw.status.rejected');
    Route::get('/affiliate-withdraw-status-approved/{id}', [AffiliatePaymentController::class, 'withdrawStatusApproved'])->name('withdraw.status.change.approved');
    Route::get('/affiliate-withdraw-status-pending/{id}', [AffiliatePaymentController::class, 'withdrawStatusPending'])->name('withdraw.status.change.pending');
    Route::get('/affiliate-withdraw-status-rejected/{id}', [AffiliatePaymentController::class, 'withdrawStatusRejected'])->name('withdraw.status.change.rejected');

    // Affiliate FAQ
    Route::get('/affiliate-faq-question-list', [AffiliateFAQController::class, 'affiliateFAQList'])->name('faq.question.list');
    Route::get('/affiliate-faq-question-create', [AffiliateFAQController::class, 'affiliateFAQCreate'])->name('faq.question.create');
    Route::post('/affiliate-faq-question-store', [AffiliateFAQController::class, 'affiliateFAQStore'])->name('faq.question.store');
    Route::get('/affiliate-faq-question-edit/{id}', [AffiliateFAQController::class, 'affiliateFAQEdit'])->name('faq.question.edit');
    Route::post('/affiliate-faq-question-update', [AffiliateFAQController::class, 'affiliateFAQUpdate'])->name('faq.question.update');
    Route::get('/affiliate-faq-question-status', [AffiliateFAQController::class, 'status'])->name('faq.question.status');
    Route::get('/affiliate-faq-question-delete/{id}', [AffiliateFAQController::class, 'affiliateFAQDelete'])->name('faq.question.delete');
    Route::post('/affiliate-faq-question/action', [AffiliateFAQController::class, 'changeQuestionAction'])->name('faq.question.action');


});


Route::middleware(['auth', 'affiliate'])->name('affiliate.')->group(function () {

    // Frontend route
    Route::get('/affiliate', [AffiliateController::class, 'index'])->name('index');
    Route::get('/affiliate-exam-rules', [AffiliateController::class, 'exam_rules'])->name('exams.rules');
    Route::get('/affiliate-affiliateMarketing', [AffiliateController::class, 'affiliateMarketing'])->name('affiliateMarketing');
    Route::get('/affiliate-setting', [AffiliateController::class, 'profile'])->name('profile');
    Route::post('/affiliate-profile-update', [AffiliateController::class, 'profile_update'])->name('profile_update');
    Route::post('/affiliate-withdraw-request', [AffiliateController::class, 'withdrawRequest'])->name('withdraw.request');

    Route::middleware(['affiliateUpdateProfile'])->group(function () {
        // Affiliate FAQ
        Route::get('/affiliate-faq-one', [AffiliateController::class, 'faq'])->name('faq');
        Route::get('/affiliate-faq-two', [AffiliateController::class, 'faq2'])->name('faq2');

        // Affiliate exam
        Route::post('/affiliate-exams-one', [AffiliateController::class, 'exams'])->name('exams.start');
        Route::get('/affiliate-exams-two', [AffiliateController::class, 'examspagetwo'])->name('examspagetwo');
        Route::post('/affiliate-exams-two', [AffiliateController::class, 'answerStore'])->name('questions.answer.store');
    });

    // Affiliate exam result
    Route::get('/affiliate-exams-result', [AffiliateController::class, 'result'])->name('result');

    // Affiliate payment page
    Route::get('/affiliate-payment', [AffiliateController::class, 'payment'])->name('payment');

    // Affiliate user payment
    Route::post('/affiliate/user-payment', [AffiliateController::class, 'affiliateUserPayment'])->name('user.payment');

});


// Bkash payment
Route::middleware(['auth', 'affiliate'])->group(function () {

    /*** bkash affiliate payment start ***/
    Route::get('/bkash/create-payment', [App\Http\Controllers\BkashTokenizePaymentController::class, 'createPayment'])->name('bkash-create-payment');
    Route::get('/affiliate/bkash/callback', [App\Http\Controllers\BkashTokenizePaymentController::class, 'callBack'])->name('affiliate-bkash-callBack');

    //search payment
    Route::get('/bkash/search/{trxID}', [App\Http\Controllers\BkashTokenizePaymentController::class, 'searchTnx'])->name('bkash-serach');

    //refund payment routes
    Route::get('/a-bkash/refund', [App\Http\Controllers\BkashTokenizePaymentController::class, 'refund'])->name('bkash-refund');
    Route::get('/a-bkash/refund/status', [App\Http\Controllers\BkashTokenizePaymentController::class, 'refundStatus'])->name('bkash-refund-status');
    /*** bkash affiliate payment end ***/


    /*** amarpay affiliate payment start ***/
    Route::get('/affiliate/amarpay/create-payment', [AmarpayAffiliateController::class, 'createPayment'])->name('amarpay.affiliate.payment');
    Route::post('/affiliate/amarpay/success-transaction', [AmarpayAffiliateController::class, 'successTransaction'])->name('amarpay.affiliate.successTransaction');
    Route::post('/affiliate/amarpay/failed-transaction', [AmarpayAffiliateController::class, 'failedTransaction'])->name('amarpay.affiliate.failedTransaction');
    Route::get('/affiliate/amarpay/cancel-transaction', [AmarpayAffiliateController::class, 'cancelTransaction'])->name('amarpay.affiliate.cancelTransaction');
    /*** amarpay affiliate payment end ***/


    /*** paypal affiliate payment start ***/
    Route::get('/affiliate/paypal-payment', [PaypalAffiliateController::class, 'createPayment'])->name('paypal.affiliate.payment');
    Route::get('/affiliate/paypal/success-transaction', [PaypalAffiliateController::class, 'successTransaction'])->name('paypal.affiliate.successTransaction');
    Route::get('/affiliate/paypal/cancel-transaction', [PaypalAffiliateController::class, 'cancelTransaction'])->name('paypal.affiliate.cancelTransaction');
    /*** paypal affiliate payment end ***/


});
