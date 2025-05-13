<?php

namespace App\Http\Controllers;

use App\DataTables\VentasCanceladasDataTable;
use App\Models\DetalleVenta;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function imprimirVolanteCancelacion($numeroRecibo)
    {
        $detalleVentas = DetalleVenta::where('numero_recibo', $numeroRecibo)->get();


        if ($detalleVentas->isEmpty()) {

            return back()->with('error', 'No se encontraron cuotas con el número de recibo ingresado!');
        }

        $totalPago = $detalleVentas->sum('total_pago');

        $conceptoDe = $detalleVentas[0]->concepto_de;




        $venta = Venta::all()->where('id_parcela', '=', $detalleVentas->first()->id_parcela)->first();
        $cliente = Persona::all()->where('id_persona', '=', $venta->id_cliente)->first();
        $parcela = Parcela::with('lote')->where('id_parcela', '=', $detalleVentas->first()->id_parcela)->first();

        // Obtener el rango de números de cuota
        $numeroPrimeraCuota = $detalleVentas->first()->numero_cuota;
        $numeroUltimaCuota = $detalleVentas->last()->numero_cuota;

        $pathLogo = Storage::path('public/img/logoInnova.jpg');
        $logo = file_get_contents($pathLogo);

        $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="180" height="100" />';

        $pathCancel = Storage::path('public/img/cancelll.png');
        $cancel = file_get_contents($pathCancel);

        $htmlCancel = '<img src="data:image/svg+xml;base64,' . base64_encode($cancel) . '"  width="100" />';

        $descripcionParcela = $venta->parcela->descripcion_parcela ?? '';

        if (Str::contains($descripcionParcela, ['Parcela', '-Manzana'])) {
            $descripcionParcela = Str::before($descripcionParcela, '-Manzana');
        }

        $pdf = Pdf::loadView('ventas_canceladas.volanteCancelacion', compact('detalleVentas', 'venta', 'cliente', 'parcela', 'pathLogo', 'html', 'htmlCancel', 'numeroPrimeraCuota', 'numeroUltimaCuota', 'totalPago', 'conceptoDe', 'descripcionParcela'))
            ->setPaper('cart', 'vertical');

        return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
    }
}
