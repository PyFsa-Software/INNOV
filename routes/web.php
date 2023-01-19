<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\ParcelasController;
use App\Http\Controllers\PreciosController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\VentasController;
use App\Http\Middleware\VerificarActualizacionCuotas;
use App\Http\Middleware\VerificarCuotaNoPagada;
use App\Http\Middleware\VerificarCuotaVolantePago;
use App\Models\Parcela;
use App\Models\Persona;
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

        $totalParcelas = Parcela::all()->count();
        $totalParcelasVendidas = Parcela::where('disponible', '=', 'no')->count();
        $totalParcelasDisponibles = Parcela::where('disponible', '=', 'si')->count();
        $totalClientes = Persona::where('cliente', '=', '1')
            ->where('activo', '=', '1')
            ->count();

        return view('dashboard.dashboard', compact('totalParcelas', 'totalParcelasVendidas', 'totalParcelasDisponibles', 'totalClientes'));

    })->name('inicio');

    //ROUTES LOTES
    Route::controller(LotesController::class)->group(function () {

        Route::get('lotes', 'LotesIndex')->name('lotes.index');

        Route::get('lotes/crear', 'CrearLoteView')->name('lotes.crear');
        Route::post('lotes/crear', 'CrearLote')->name('lotes.guardar');

        Route::get('lotes/editar/{lote}', 'EditarLoteView')->name('lotes.editar');
        Route::put('lotes/editar/{lote}', 'EditarLote')->name('lotes.modificar');

        Route::get('lotes/eliminar/{lote}', 'EliminarLoteView')->name('lotes.borrar');
        Route::delete('lotes/eliminar/{lote}', 'EliminarLote')->name('lotes.eliminar');

    });

    //ROUTES PARCELAS

    Route::controller(ParcelasController::class)->group(function () {

        Route::get('parcelas', 'ParcelasIndex')->name('parcelas.index');

        Route::get('parcelas/crear', 'CrearParcelaView')->name('parcelas.crear');
        Route::post('parcelas/crear', 'CrearParcela')->name('parcelas.guardar');

        Route::get('parcelas/editar/{parcela}', 'EditarParcelaView')->name('parcelas.editar');
        Route::put('parcelas/editar/{parcela}', 'EditarParcela')->name('parcelas.modificar');

        Route::get('parcelas/eliminar/{parcela}', 'EliminarParcelaView')->name('parcelas.borrar');
        Route::delete('parcelas/eliminar/{parcela}', 'EliminarParcela')->name('parcelas.eliminar');

    });

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

        Route::get('clientes/estado/{persona}', 'estadoCliente')->name('clientes.estado');

        Route::get('clientes/cuotas/{parcela}', 'estadoCuotas')->name('clientes.estadoCuotas');

        Route::get('clientes/cobrar/{cuota}', 'cobrarCuotas')->name('clientes.cobrarCuota')->middleware(VerificarCuotaNoPagada::class);

        Route::get('clientes/volante-pago/{cuota}', 'generarVolantePago')->name('clientes.volantePago')->middleware(VerificarCuotaVolantePago::class);

        Route::get('clientes/actualizar-precios/{parcela}', 'actualizarPrecios')->name('clientes.actualizarPrecios')->middleware(VerificarActualizacionCuotas::class);

    });

    // ROUTES VENTAS
    Route::controller(VentasController::class)->group(function () {
        Route::get('ventas/crear', 'create')->name('ventas.crear');
    });
    // ROUTES REPORTES
    Route::controller(ReportesController::class)->group(function () {
        Route::get('reportes/planilla', 'planilla')->name('reportes.planilla');
        Route::post('reportes/planilla', 'exportarPlanilla')->name('reportes.exportarPlanilla');
    });

});