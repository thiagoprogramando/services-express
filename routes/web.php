<?php

use App\Http\Controllers\Access\LoginController;
use App\Http\Controllers\People\ClientController;
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

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
