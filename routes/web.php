<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
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
    Route::controller(PreciosController::class)->group(function () {

        Route::get('precios', 'index')->name('precios.index');

        Route::get('precios/crear', 'create')->name('precios.crear');
        Route::post('precios/crear', 'store')->name('precios.guardar');

        Route::get('precios/editar/{precio}', 'edit')->name('precios.editar');
        Route::put('precios/editar/{precio}', 'update')->name('precios.modificar');

        Route::get('precios/borrar/{precio}', 'showQuestion')->name('precios.borrar');
        Route::delete('precios/borrar/{precio}', 'destroy')->name('precios.eliminar');

    });

    // ROUTES CLIENTES
    Route::controller(ClientesController::class)->group(function () {

        Route::get('clientes', 'index')->name('clientes.index');

        Route::get('clientes/crear', 'create')->name('clientes.crear');
        Route::post('clientes/crear', 'store')->name('clientes.guardar');

        Route::get('clientes/editar/{persona}', 'edit')->name('clientes.editar');
        Route::put('clientes/editar/{persona}', 'update')->name('clientes.modificar');

        Route::get('clientes/activar/{persona}', 'showQuestionActivate')->name('clientes.activar');
        Route::patch('clientes/activar/{persona}', 'activate')->name('clientes.habilitar');

        Route::get('clientes/borrar/{persona}', 'showQuestionDestroy')->name('clientes.borrar');
        Route::delete('clientes/borrar/{persona}', 'destroy')->name('clientes.eliminar');

    });

});