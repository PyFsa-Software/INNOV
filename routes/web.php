<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\ParcelasController;
use App\Http\Controllers\PreciosController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ReservaParcelaController;
use App\Http\Controllers\ResumenDiarioController;
use App\Http\Controllers\VentasCanceladasController;
use App\Http\Controllers\VentasController;
use App\Http\Middleware\VerificarActualizacionCuotas;
use App\Http\Middleware\VerificarCuotaNoPagada;
use App\Http\Middleware\VerificarCuotaVolantePago;
use App\Http\Middleware\VerificarCuotaAnteriorPagada;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\DetalleVenta;
use App\Models\Venta;
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

        // Métricas básicas existentes
        $totalParcelas = Parcela::all()->count();
        $totalParcelasVendidas = Parcela::where('disponible', '=', '0')->count();
        $totalParcelasDisponibles = Parcela::where('disponible', '=', '1')->count();
        $totalClientes = Persona::where('cliente', '=', '1')
            ->where('activo', '=', '1')
            ->count();

        // NUEVAS MÉTRICAS DE NEGOCIO

        // Cantidad de cuotas cobradas este mes
        $cobranzaDelMes = DetalleVenta::whereMonth('fecha_pago', date('m'))
            ->whereYear('fecha_pago', date('Y'))
            ->where('pagado', 'si')
            ->count();

        // Pre-ventas pendientes (aún faltan pagos para completarlas)
        $reservasPendientes = \App\Models\ReservaParcela::where('estado_reserva', false)->count();

        // Pre-ventas canceladas (usando la relación con detalle_reserva_parcela)
        $reservasCanceladas = \App\Models\ReservaParcela::whereHas('detalles', function ($query) {
            $query->where('cancelado', 1);
        })->count();

        // Total de pre-ventas realizadas este mes
        $reservasDelMes = \App\Models\ReservaParcela::whereMonth('fecha_reserva', date('m'))
            ->whereYear('fecha_reserva', date('Y'))
            ->count();

        // ALERTAS CRÍTICAS

        // Cuotas vencidas hace más de 30 días
        $cuotasVencidasCriticas = DetalleVenta::where('pagado', 'no')
            ->where('fecha_maxima_a_pagar', '<', date('Y-m-d', strtotime('-30 days')))
            ->count();

        // Cuotas que vencen en los próximos 7 días
        $cuotasProximasVencer = DetalleVenta::where('pagado', 'no')
            ->whereBetween('fecha_maxima_a_pagar', [date('Y-m-d'), date('Y-m-d', strtotime('+7 days'))])
            ->count();

        // Clientes con cuotas vencidas (existente mejorado)
        $clientesCuotasVencidas = DetalleVenta::where('fecha_maxima_a_pagar', '<', date('Y-m-d'))
            ->with(['venta' => function ($query) {
                $query->with('cliente');
            }])
            ->where('pagado', '!=', 'si')
            ->get()
            ->groupBy(function ($detalleVenta) {
                return $detalleVenta->venta->cliente->id_persona;
            })
            ->map(function ($item) {
                return $item->unique('id_persona');
            });



        // Cálculo de tendencias (comparar con mes anterior)
        $cobranzaMesAnterior = DetalleVenta::whereMonth('fecha_pago', date('m') - 1)
            ->whereYear('fecha_pago', date('Y'))
            ->where('pagado', 'si')
            ->count();

        $tendenciaCobranza = $cobranzaMesAnterior > 0 ?
            (($cobranzaDelMes - $cobranzaMesAnterior) / $cobranzaMesAnterior) * 100 : 0;

        return view('dashboard.dashboard', compact(
            'totalParcelas',
            'totalParcelasVendidas',
            'totalParcelasDisponibles',
            'totalClientes',
            'clientesCuotasVencidas',
            'cobranzaDelMes',
            'reservasPendientes',
            'reservasCanceladas',
            'reservasDelMes',
            'cuotasVencidasCriticas',
            'cuotasProximasVencer',
            'tendenciaCobranza'
        ));
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


        Route::get('parcelas/crear-multiple', 'CrearParcelasMultiplesView')->name('parcelas.crearParcelasMultiples');
        // Route::post('parcelas/crear-multiple', 'CrearParcela')->name('parcelas.guardar');

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

        Route::get('clientes/cobrar/{cuota}', 'cobrarCuotas')->name('clientes.cobrarCuota')->middleware([VerificarCuotaAnteriorPagada::class]);

        Route::get('clientes/cobrar-todo/{parcela}', 'cobrarTodo')->name('clientes.cobrarTodo');


        Route::get('clientes/editar-precio/{cuota}', 'editarPrecioCuota')->name('clientes.editarPrecioCuota');
        Route::put('clientes/editar-precio/{cuota}', 'updatePrecioCuota')->name('clientes.modificarPrecioCuota');

        Route::get('clientes/volante-pago/{cuota}', 'generarVolantePago')->name('clientes.volantePago')->middleware(VerificarCuotaVolantePago::class);

        Route::get('clientes/volante-pago-multiple/{numeroRecibo}', 'generarVolantePagoMultiple')->name('clientes.volantePagoMultiple');

        Route::get('clientes/actualizar-precios/{parcela}', 'actualizarPrecios')->name('clientes.actualizarPrecios')->middleware(VerificarActualizacionCuotas::class);

        Route::get('clientes/generar-cuotas/{parcela}', 'generarCuotas')->name('clientes.generarCuotas')->middleware(VerificarActualizacionCuotas::class);

        //PAGOS MULTIPLES

        Route::get('clientes/pagos-multiples/{parcela}', 'volantesPagosMultiples')->name('clientes.pagosMultiples');

        // ACTUALIZAR MONTO CUOTAS VENCIDAS

        Route::get('clientes/actualizar-precios-cuotas/{parcela}', 'actualizarPreciosCuotasVencidas')->name('clientes.actualizarPreciosCuotasVencidas');
        Route::post('clientes/actualizar-precios-cuotas/{parcela}', 'guardarPreciosCuotasVencidas')->name('clientes.guardarPreciosCuotasVencidas');

        Route::get('clientes/calcularDeuda/{parcela}', 'calcularDeuda')->name('clientes.calcularDeuda');
        Route::post('clientes/calcularDeudaResultado/{parcela}', 'calcularDeudaResultado')->name('clientes.calcularDeudaResultado');
    });

    // ROUTES RESERVA PARCELAS
    Route::controller(ReservaParcelaController::class)->group(function () {

        Route::get('pre-venta', 'index')->name('reservaParcela.index');

        Route::get('pre-venta/pagos/{idReserva}', 'payments')->name('reservaParcela.payments');
        Route::get('pre-venta/pagar/{idReserva}', 'pay')->name('reservaParcela.pay');

        Route::get('pre-venta/crear', 'create')->name('reservaParcela.crear');

        Route::get('pre-venta/volante-pago/{idDetalleReserva}', 'generarVolantePago')->name('reservaParcela.volantePago');
    });

    // ROUTES VENTAS
    Route::controller(VentasController::class)->group(function () {
        Route::get('ventas/crear', 'create')->name('ventas.crear');
        Route::get('ventas/listado', 'index')->name('ventas.listado');
        Route::get('ventas/volante-pago/{venta}', 'generarVolantePago')->name('ventas.volantePago');
        Route::get('ventas/eliminar/{venta}', 'eliminarVenta')->name('ventas.eliminarVenta');
    });



    // ROUTES REPORTES
    Route::controller(ReportesController::class)->group(function () {
        Route::get('reportes/planilla', 'planilla')->name('reportes.planilla');
        Route::post('reportes/planilla', 'exportarPlanilla')->name('reportes.exportarPlanilla');
    });


    // ROUTE FOR VENTAS CANCELADAS
    Route::controller(VentasCanceladasController::class)->group(function () {
        Route::get('ventas-canceladas/listado', 'index')->name('ventasCanceladas.index');
        Route::get('ventas-canceladas/volante/{numeroRecibo}', 'imprimirVolanteCancelacion')->name('ventasCanceladas.imprimirVolanteCancelacion');
    });

    // ROUTE FOR COMPROBANTES

    Route::controller(ComprobanteController::class)->group(function () {
        Route::get('comprobantes', 'index')->name('comprobantes.index');
        Route::get('comprobantes/crear', 'create')->name('comprobantes.crear');
        // pdf
        Route::get('comprobantes/pdf/{comprobante}', 'pdf')->name('comprobantes.pdf');
    });

    //ROUTE FOR RESUMEN DIARIO

    Route::controller(ResumenDiarioController::class)->group(function () {
        Route::get('resumen-diario', 'index')->name('resumenDiario.index');
        Route::get('resumen-cuotas', 'resumenCuotas')->name('resumenCuotas.resumenCuotas');
        Route::get('resumen-ventas', 'resumenVentas')->name('resumenVentas.resumenVentas');
        Route::get('resumen-preVentas', 'resumenPreVentas')->name('resumenPreVentas.resumenPreVentas');
        Route::get('exportar-resumen', 'exportarExcel')->name('resumen.exportarExcel');
    });
});
