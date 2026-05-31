<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\chat\ChatController;
use App\Http\Controllers\Api\v1\chat\RealtimeController;

//Route::middleware(['isModulusAccess:124'])->group(function () {
Route::prefix('/v1')->name('chat.')->group(function () {

    // Get message
    Route::get('/welcome-message', [ChatController::class, 'welcome'])->name('welcome');

    // Create conversation
    Route::post('/create/chat-conversation', [ChatController::class, 'createConversation'])->name('create.conversation');

    // Create conversation
    Route::get('/get-chat-conversations', [ChatController::class, 'getConversation'])->name('get.conversation');

    // Get single conversation by visitor ID
    Route::get('/get/chat-conversations/{id}', [ChatController::class, 'getConversationByVisitorId'])->name('get.conversation.byID');

    // Get conversation message by conversation ID
    Route::get('/get-conversations/message/{id?}', [ChatController::class, 'getConversationMessage'])->name('get.conversation.message');

    // Message mark as read
    Route::put('/chat-massage/markAsRead', [ChatController::class, 'massageMarkAsRead'])->name('message.markAsRead');

    // Message send
    Route::post('/chat-message/send', [ChatController::class, 'massageSend'])->name('message.send');

    // Get user conversation
    Route::post('/get-visitor/conversation', [ChatController::class, 'getVisitorConversation'])->name('get.visitor.conversation');

    // Delete Chat conversation for end session
    Route::delete('/chat-session/delete', [ChatController::class, 'deleteVisitorConversation'])->name('delete.visitor.conversation');

    // Update conversation user data
    Route::post('/conversation/user-data-update', [ChatController::class, 'updateConversationUserdata'])->name('update.conversation.userdata');

    // Support chat realtime endpoints for visitor widget
    Route::get('/chat-realtime/events', [RealtimeController::class, 'events'])->name('realtime.events');
    Route::get('/chat-realtime/stream', [RealtimeController::class, 'stream'])->name('realtime.stream');

});



//});

