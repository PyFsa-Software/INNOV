<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\PreciosController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    // INICIAR SESION
    Route::get('/', [AuthController::class, 'index'])->name('inicioSesion.index');

    Route::post('/', [AuthController::class, 'loguearse'])->name('inicioSesion.loguearse');

});

Route::middleware(['auth'])->group(function () {

    // CERRAR SESION
    Route::get('/logout', [AuthController::class, 'logout'])->name('inicioSesion.desloguearse');

    route::get('/inicio', function () {
        return view('dashboard.dashboard');
    })->name('inicio');

    //ROUTES LOTES
    Route::get('lotes', [LotesController::class, 'Lotes'])->name('lotes');

    // ROUTES PRECIOS
    Route::get('precios', [PreciosController::class, 'index'])->name('precios.index');
    Route::get('precios/crear', [PreciosController::class, 'create'])->name('precios.crear');

    Route::post('precios/crear', [PreciosController::class, 'store'])->name('precios.guardar');

});