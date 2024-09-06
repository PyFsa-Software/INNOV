<?php

namespace App\Http\Controllers;

use App\Exports\ResumenDiarioExport;
use App\Models\DetalleReservaParcela;
use App\Models\DetalleVenta;
use Faker\Core\File;
use Faker\Provider\File as ProviderFile;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ResumenDiarioController extends Controller
{
    public function index()
    {
        return view('resumen_diario.index');
    }

    public function resumenCuotas(Request $request)
    {
        if ($request->ajax()) {
            $fechaDesde = $request->input('fecha_desde');
            $fechaHasta = $request->input('fecha_hasta');

            $cuotas = DetalleVenta::with('venta.cliente', 'venta.parcela.lote')
                ->whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
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
            $fechaDesde = $request->input('fecha_desde');
            $fechaHasta = $request->input('fecha_hasta');

            // Asegúrate de que las fechas están en el formato correcto
            // Convertimos la fecha desde al formato adecuado
            $fechaDesde = date('Y-m-d 00:00:00', strtotime($fechaDesde));
            // Convertimos la fecha hasta al formato adecuado incluyendo el final del día
            $fechaHasta = date('Y-m-d 23:59:59', strtotime($fechaHasta));

            // Obtén los registros con el rango de fechas especificado
            $preVentas = DetalleReservaParcela::with('reservaParcela.cliente')
                ->whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
                ->orderBy('fecha_pago', 'desc')
                ->get();

            $data = $preVentas->map(function ($preVenta) {
                return [
                    'cliente' => $preVenta->reservaParcela->cliente->nombre . ' ' . $preVenta->reservaParcela->cliente->apellido,
                    'parcela' => $preVenta->reservaParcela->id_parcela,
                    'fecha_pago' => $preVenta->fecha_pago, 
                    'forma_pago' => $preVenta->forma_pago,
                    'importe_pago' => '$ ' . number_format($preVenta->importe_pago, 2, ',', '.'),
                ];
            });

            // Devuelve los datos como JSON para DataTables
            return DataTables::of($data)->toJson();
        }
    }

    public function exportarExcel(Request $request)
    {
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        return Excel::download(new ResumenDiarioExport($fechaDesde, $fechaHasta), 'resumen_diario.xlsx');
    }
}