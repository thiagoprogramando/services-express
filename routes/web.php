<?php

use App\Http\Controllers\Access\ForgoutController;
use App\Http\Controllers\Access\LoginController;
use App\Http\Controllers\Config\UserController;
use App\Http\Controllers\People\ClientController;
use App\Http\Controllers\Price\PriceController;
use App\Http\Controllers\Service\FeesController;
use App\Http\Controllers\Service\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/logon', [LoginController::class, 'logon'])->name('logon');

Route::get('/forgout/{code?}', [ForgoutController::class, 'forgout'])->name('forgout');
Route::post('/forgout-password', [ForgoutController::class, 'forgoutPassword'])->name('forgout-password');
Route::post('/recover-password/{code}', [ForgoutController::class, 'recoverPassword'])->name('recover-password');

Route::middleware(['auth'])->group(function () {

    Route::get('/app', function () {
        return view('app.app');
    })->name('app');

    Route::get('/profile', [UserController::class, 'index'])->name('profile');
    Route::get('/security', [UserController::class, 'security'])->name('security');
    Route::post('/updated-user', [UserController::class, 'update'])->name('updated-user');
    Route::post('/deleted-user', [UserController::class, 'delete'])->name('deleted-user');

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

    Route::get('/list-prices', [PriceController::class, 'index'])->name('list-prices');
    Route::get('/view-price/{uuid}', [PriceController::class, 'view'])->name('view-price');
    Route::post('/created-price', [PriceController::class, 'store'])->name('created-price');
    Route::post('/updated-price', [PriceController::class, 'edit'])->name('updated-price');
    Route::post('/deleted-price', [PriceController::class, 'delete'])->name('deleted-price');
        Route::post('/add-price-service', [PriceController::class, 'addPriceService'])->name('add-price-service');
        Route::post('/updated-price-service', [PriceController::class, 'updatedPriceService'])->name('updated-price-service');
        Route::post('/action-price-service', [PriceController::class, 'actionPriceServices'])->name('action-price-service');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
