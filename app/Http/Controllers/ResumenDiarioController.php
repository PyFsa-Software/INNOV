<?php

namespace App\Http\Controllers;

use App\DataTables\CuotasPagadasDataTable;
use App\DataTables\PreVentasPagadasDataTable;
use App\DataTables\VentasPagadasDataTable;
use App\Models\DetalleReservaParcela;
use App\Models\DetalleVenta;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class ResumenDiarioController extends Controller
{
    public function index()
    {   
        return view('resumen_diario.index');
    }

    public function resumenCuotas(Request $request)
    {
        if ($request->ajax()) {
            $cuotas = DetalleVenta::with('venta.cliente', 'venta.parcela.lote')
                ->whereDate('fecha_pago', '>=', now()->subDay()) // Filtrar por las últimas 24 horas
                ->orderBy('fecha_pago', 'desc')
                ->get();
            
            // Transforma los datos según lo que necesitas para la tabla
            $data = $cuotas->map(function ($cuota) {
                return [
                    'cliente' => $cuota->venta->cliente->nombre, // Cambia 'nombre' por el campo que almacena el nombre del cliente
                    'fecha_pago' => $cuota->fecha_pago,
                    'metodo_pago' => $cuota->forma_pago,
                    'parcela' => $cuota->venta->parcela->descripcion_parcela, // Cambia 'descripcion_parcela' por el campo que almacena la descripción de la parcela
                    'lote' => $cuota->venta->parcela->lote->nombre_lote, // Cambia 'nombre_lote' por el campo que almacena el nombre del lote
                    'monto_pago' => '$ ' . number_format($cuota->total_pago, 2, ',', '.')
                ];
            });
            
            return DataTables::of($data)->toJson();

        }
    }
    
    public function resumenPreVentas(Request $request)
    {
        if ($request->ajax()) {
            $preVentas = DetalleReservaParcela::with('reservaParcela.cliente')
            ->whereDate('fecha_pago', '>=', now()->subDay()) // Filtrar por las últimas 24 horas
                ->orderBy('fecha_pago', 'desc')
                ->get();

            $data = $preVentas->map(function ($preVenta) {
                return [
                    'cliente' => $preVenta->reservaParcela->cliente->nombre . ' ' . $preVenta->reservaParcela->cliente->apellido,
                    'parcela' => $preVenta->reservaParcela->id_parcela,
                    'fecha_pago' => $preVenta->fecha_pago,
                    'forma_pago' => $preVenta->forma_pago,
                    'importe_pago' => '$ ' . number_format($preVenta->importe_pago, 2, ',', '.')
                ];
            });

            return DataTables::of($data)->toJson();
        }
        
    }
}