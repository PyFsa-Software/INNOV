<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;

Route::middleware(['guest'])->group(function () {
    // INICIAR SESION
    Route::get('/', [Auth::class, 'index'])->name('inicioSesion.index');

    Route::post('/', [Auth::class, 'loguearse'])->name('inicioSesion.loguearse');

});

Route::middleware(['auth'])->group(function () {

    // CERRAR SESION
    Route::get('/logout', [Auth::class, 'logout'])->name('inicioSesion.desloguearse');

    route::get('/inicio', function () {
        return view('dashboard.dashboard');
    })->name('inicio');

});