<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\BotDrZantiaController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\GuaranteeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/bot/drzantia/getupdates', [BotDrZantiaController::class, 'getupdates']);
Route::post('/bot/drzantia/sendmessage', [BotDrZantiaController::class, 'sendmessage']);

Route::post('senderror', [\App\Http\Controllers\Controller::class, 'sendError']);
Route::post('/bot/getupdates', [BotController::class, 'getupdates']);
Route::post('/bot/sendmessage', [BotController::class, 'sendmessage']);
Route::get('/bot/getme', [BotController::class, 'myInfo']);

Route::post('/chat/broadcast', [App\Http\Controllers\PushController::class, 'broadcast'])->name('chat.broadcast');
Route::post('/chat/chatsupporthistory', [App\Http\Controllers\PushController::class, 'chatSupportHistory'])->name('chat.support.history');


Route::any('payment/done', [TransactionController    ::class, 'payDone'])->name('eblagh.payment.done');
Route::any('guarantee/sms-verify', [GuaranteeController::class, 'smsVerify'])->name('guarantee.sms');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

