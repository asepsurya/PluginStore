<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pluginController;

Route::get('/', [pluginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/loginaksi', [pluginController::class, 'login'])->name('plugin.login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [pluginController::class, 'dashboard'])->name('plugin.dashboard');
    Route::post('/logout', [pluginController::class, 'logout'])->name('logout');
    Route::post('/add', [pluginController::class, 'add'])->name('plugin.store');
    Route::get('plugins/download/{id}', [pluginController::class, 'download'])->name('plugin.download');

});
