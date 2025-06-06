<?php

namespace App\Http\Controllers;

use App\DataTables\DetalleReservaParcelaDataTable;
use App\DataTables\ReservaParcelaDataTable;
use App\Enums\FormasPago;
use App\Models\DetalleReservaParcela;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\ReservaParcela;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReservaParcelaController extends Controller
{


    public function index(ReservaParcelaDataTable $dataTable)
    {

        $reservas = ReservaParcela::all();

        return $dataTable->render('reservas_realizadas.index', compact('reservas'));
    }

    public function create()
    {

        $clientes = Persona::where([
            ['cliente', '=', '1'],
            ['activo', '=', '1'],
        ])->get();

        // Paso 1: Obtén todas las parcelas disponibles
        $parcelasDisponibles = Parcela::where('disponible', 1)->get();

        $parcelasSinReserva = $parcelasDisponibles->filter(function ($parcela) {
            // Verifica si el id_parcela NO está en la tabla reserva_parcela
            return !ReservaParcela::where('id_parcela', $parcela->id_parcela)->exists();
        });


        // Convierte el resultado a una colección de Laravel o array, según prefieras
        $parcelas = $parcelasSinReserva->values(); // Limpia las claves


        $formasDePagos = FormasPago::toArray();


        return view('reservas_realizadas.crear', compact('clientes', 'parcelas', 'formasDePagos'));
    }

    public function payments(DetalleReservaParcelaDataTable $dataTable, $idReserva)
    {
        $reservas = DetalleReservaParcela::where('id_reserva_parcela', $idReserva)->get();


        $cancelado = DetalleReservaParcela::where('id_reserva_parcela', $idReserva)->where('cancelado', 1)->count();


        return $dataTable->render('reservas_realizadas.listado_pagos', compact('reservas', 'cancelado'));
    }

    public function pay($id)
    {
        $formasDePagos = FormasPago::toArray();
        $reserva = ReservaParcela::findOrFail($id);
        $detalleReserva = DetalleReservaParcela::where('id_reserva_parcela', $id)->get();
        return view('reservas_realizadas.realizar_pago', compact('reserva', 'detalleReserva', 'formasDePagos'));
    }

    public function generarVolantePago($id)
    {
        try {


            // Obtener la venta por su ID
            $detalleReservaParcela = DetalleReservaParcela::where('id_detalle_reserva_parcela', $id)->with('reservaParcela')->first();

            $reservaParcela = ReservaParcela::findOrFail($detalleReservaParcela->reservaParcela->id_reserva_parcela);

            $idCliente = $detalleReservaParcela->reservaParcela->id_cliente;

            // Obtener la persona asociada
            $cliente = Persona::where('id_persona', $idCliente)->first();



            $idParcela = $detalleReservaParcela->reservaParcela->id_parcela;

            $parcela = Parcela::where('id_parcela', $idParcela)->with('lote')->first();

            $pathLogo = Storage::path('public/img/logoInnova.png');
            $logo = file_get_contents($pathLogo);
            $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="180" height="100" />';

            $fecha_pago = Carbon::parse($detalleReservaParcela->fecha_pago);
            $fecha_pago->format('d/m/Y');

            $pathCancel = Storage::path('public/img/cancelll.png');
            $cancel = file_get_contents($pathCancel);

            $htmlCancel = '<img src="data:image/svg+xml;base64,' . base64_encode($cancel) . '"  width="100" />';


            // Generar el volante de pago en formato PDF
            $pdf = Pdf::loadView('reservas_realizadas.volante_pago', compact('reservaParcela', 'detalleReservaParcela', 'cliente', 'html', 'fecha_pago', 'parcela', 'htmlCancel'))->setPaper('cart', 'vertical');

            return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
            // Descargar el PDF
            // return $pdf->download('volante_pago.pdf');
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => "Se produjo un error al generar el volante de pago. Por favor, contacte al administrador.",
            ], 400);
        }
    }
}
