<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\pluginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MidtransCallbackController;

Route::get('/', [pluginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/loginaksi', [pluginController::class, 'login'])->name('plugin.login');
Route::get('/captcha', [pluginController::class, 'getCaptcha']);
Route::get('/plugin/payment/{id}', [pluginController::class, 'payment']);
Route::post('/payment/process', [pluginController::class, 'paymentprocess'])->name('payment.process');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transaction/success/{transaction}', [TransactionController::class, 'success'])->name('transactions.success');

Route::any('/midtrans/callback', [TransactionController::class, 'callback'])->name('transactions.success');






Route::post('/gopay/connect/notify', [TransactionController::class, 'notify']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [pluginController::class, 'dashboard'])->name('plugin.dashboard');
    Route::post('/logout', [pluginController::class, 'logout'])->name('logout');
    Route::post('/add', [pluginController::class, 'add'])->name('plugin.store');
    Route::post('/update', [pluginController::class, 'update'])->name('plugin.update');
    Route::get('plugins/download/{id}', [pluginController::class, 'download'])->name('plugin.download');
    Route::post('/plugin/delete', [pluginController::class, 'destroy'])->name('plugin.delete');

});
