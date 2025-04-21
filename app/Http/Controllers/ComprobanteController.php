<?php

namespace App\Http\Controllers;

use App\DataTables\ComprobantesDataTable;
use App\Enums\FormasPago;
use App\Models\Comprobante;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComprobanteController extends Controller
{
    public function index(ComprobantesDataTable $dataTable)
    {
        $comprobantes = Comprobante::all();
        return $dataTable->render('comprobantes.index', compact('comprobantes'));
    }

    public function create()
    {
        $clientes = Persona::where([
            ['cliente', '=', '1'],
            ['activo', '=', '1'],
        ])->get();

        return view('comprobantes.crear', compact('clientes'));
    }

    // method for pdf
    public function pdf(Comprobante $comprobante)
    {
        if ($comprobante->id_cliente) {
            return self::generarComprobanteConCliente($comprobante);
        } else {
            return self::generarComprobanteSinCliente($comprobante);
        }
    }

    public static function generarComprobanteConCliente($comprobante)
    {
        $cliente = Persona::all()->where('id_persona', '=', $comprobante->id_cliente)->first();

        $venta = Venta::where('id_venta', '=', $comprobante->id_venta)->first();
        $parcela = Parcela::with('lote')->where('id_parcela', '=', $venta->id_parcela)->first();

        $pathLogo = Storage::path('public/img/logoInnova.png');
        $logo = file_get_contents($pathLogo);

        $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="180" height="100" />';

        $pdf = Pdf::loadView('comprobantes.comprobanteConClientePdf', compact('comprobante', 'venta', 'cliente', 'parcela', 'pathLogo', 'html'))
            ->setPaper('cart', 'vertical');

        return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
    }

    public static function generarComprobanteSinCliente($comprobante)
    {

        $pathLogo = Storage::path('public/img/logoInnova.png');
        $logo = file_get_contents($pathLogo);

        $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="180" height="100"/>';

        $pdf = Pdf::loadView('comprobantes.comprobanteSinClientePdf', compact('comprobante', 'pathLogo', 'html'))
            ->setPaper('cart', 'vertical');

        return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
    }
}
