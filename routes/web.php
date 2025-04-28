<?php

use App\Http\Controllers\Access\LoginController;
use App\Http\Controllers\People\ClientController;
use App\Http\Controllers\Service\FeesController;
use App\Http\Controllers\Service\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/logon', [LoginController::class, 'logon'])->name('logon');

Route::middleware(['auth'])->group(function () {

    Route::get('/app', function () {
        return view('app.app');
    })->name('app');

    Route::get('/list-clients', [ClientController::class, 'index'])->name('list-clients');
    Route::post('/created-client', [ClientController::class, 'store'])->name('created-client');
    Route::post('/updated-client', [ClientController::class, 'edit'])->name('updated-client');
    Route::post('/deleted-client', [ClientController::class, 'delete'])->name('deleted-client');

    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::post('/created-service', [ServiceController::class, 'store'])->name('created-service');
    Route::post('/updated-service', [ServiceController::class, 'edit'])->name('updated-service');
    Route::post('/deleted-service', [ServiceController::class, 'delete'])->name('deleted-service');

    Route::post('/created-fee', [FeesController::class, 'store'])->name('created-fee');
    Route::post('/updated-fee', [FeesController::class, 'edit'])->name('updated-fee');
    Route::post('/deleted-fee', [FeesController::class, 'delete'])->name('deleted-fee');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
