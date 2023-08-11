<?php

namespace App\Http\Controllers;

use App\DataTables\VentasCanceladasDataTable;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VentasCanceladasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VentasCanceladasDataTable $dataTable)
    {

        // obtener todas las ventas canceladas, en donde en el DetalleVenta, las cuotas esten pagadas

        $ventasCanceladas = Venta::whereHas('detalleVenta', function ($query) {
            $query->selectRaw('id_venta, COUNT(*) as total_cuotas')
                ->where('pagado', '=', 'si')
                ->groupBy('id_venta')
                ->havingRaw('total_cuotas = ventas.cuotas');
        })->with('cliente', 'parcela')->get();
        return $dataTable->render('ventas_canceladas.index', compact('ventasCanceladas'));
    }

    public function imprimirVolanteCancelacion($venta)
    {
        $venta = Venta::where('id_venta', $venta)->with('cliente', 'parcela', 'detalleVenta')->first();
        $fechaCancelacion = $venta->detalleVenta->last()->fecha_pago;
        $totalPago = $venta->detalleVenta->reduce(function ($carry, $item) {
            return $carry + $item->total_pago;
        }, 0);

        $pathLogo = Storage::path('public/img/logoInnova.jpg');
        $logo = file_get_contents($pathLogo);

        $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="100" height="100" />';

        $pdf = Pdf::loadView('ventas_canceladas.volanteCancelacion', compact('venta', 'html', 'fechaCancelacion', 'totalPago'))
            ->setPaper('cart', 'vertical');

        return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
    }
}
