<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pluginController;

Route::get('/', [pluginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/loginaksi', [pluginController::class, 'login'])->name('plugin.login');
Route::get('/captcha', [pluginController::class, 'getCaptcha']);
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [pluginController::class, 'dashboard'])->name('plugin.dashboard');
    Route::post('/logout', [pluginController::class, 'logout'])->name('logout');
    Route::post('/add', [pluginController::class, 'add'])->name('plugin.store');
    Route::post('/update', [pluginController::class, 'update'])->name('plugin.update');
    Route::get('plugins/download/{id}', [pluginController::class, 'download'])->name('plugin.download');
    Route::post('/plugin/delete', [pluginController::class, 'destroy'])->name('plugin.delete');

});
