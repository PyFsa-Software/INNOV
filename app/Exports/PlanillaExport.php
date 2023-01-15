<?php

namespace App\Exports;

use App\Models\DetalleVenta;
use App\Models\Lote;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PlanillaExport implements FromView
{

    protected $anioSeleccionado;
    protected $mesSeleccionado;

    public function __construct($anioSeleccionado, $mesSeleccionado)
    {
        $this->anioSeleccionado = $anioSeleccionado;
        $this->mesSeleccionado = $mesSeleccionado;
    }

    public function view(): View
    {

        // $totalCuotas = DetalleVenta::with(['venta', 'venta.cliente', 'venta.parcela'])
        //     ->whereYear('fecha_pago', '=', $this->anioSeleccionado)
        //     ->whereMonth('fecha_pago', '=', $this->mesSeleccionado)
        //     ->get();

        // DB::statement("SET SQL_MODE=''");

        $lotes = Lote::all();

        $totalCuotas = DetalleVenta::select(['id_lote', 'nombre', 'apellido', 'descripcion_parcela', 'numero_cuota', 'total_pago'])
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('personas', 'ventas.id_cliente', '=', 'personas.id_persona')
            ->join('parcelas', 'ventas.id_parcela', '=', 'parcelas.id_parcela')
            ->whereYear('fecha_pago', '=', $this->anioSeleccionado)
            ->whereMonth('fecha_pago', '=', $this->mesSeleccionado)
            ->get();

        $cuotasAgrupadas = [];

        foreach ($totalCuotas as $cuota) {
            $cuotasAgrupadas[$cuota->id_lote][] = $cuota;
        }

        return view('reportes.planilla.exportarPlanilla', [
            'cuotasAgrupadas' => $cuotasAgrupadas,
            'lotes' => $lotes,
            // 'invoices' => Invoice::all(),
        ]);
    }
}
