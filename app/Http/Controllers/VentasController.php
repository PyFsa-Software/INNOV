<?php

namespace App\Http\Controllers;

use App\DataTables\VentasDataTable;
use App\Enums\ConceptoDeVenta;
use App\Enums\FormasPago;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\Precio;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VentasDataTable $dataTable)
    {

        $ventas = Venta::all();
        return $dataTable->render('ventas_realizadas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // $results = DB::table('users')->get();

        $clientes = Persona::where([
            ['cliente', '=', '1'],
            ['activo', '=', '1'],
        ])->get();

        $parcelas = [];

        $promedioCemento = Precio::orderBy('fecha', 'desc')->first();

        $formasDePagos = FormasPago::toArray();

        $conceptosDe = ConceptoDeVenta::toArray();

        return view('ventas.crear', compact('clientes', 'parcelas', 'promedioCemento', 'formasDePagos', 'conceptosDe'));
    }

    /**
     * Generate a payment voucher for a specific sale.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generarVolantePago($id)
    {
        try {
            // Obtener la venta por su ID
            $venta = Venta::findOrFail($id);

            // Obtener la persona asociada a la venta
            $cliente = Persona::where('id_persona', $venta->id_cliente)->first();


            $parcela = Parcela::where('id_parcela', $venta->id_parcela)->with('lote')->first();

            $pathLogo = Storage::path('public/img/logoInnova.jpg');
            $logo = file_get_contents($pathLogo);
            $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="100" height="100" />';

            $fecha_venta = Carbon::parse($venta->fecha_venta);
            $fecha_venta->format('d/m/Y');

            // Generar el volante de pago en formato PDF
            $pdf = Pdf::loadView('ventas_realizadas.volante_pago', compact('venta', 'cliente', 'html', 'fecha_venta', 'parcela'))->setPaper('cart', 'vertical');

            return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
            // Descargar el PDF
            // return $pdf->download('volante_pago.pdf');
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => "Se produjo un error al generar el volante de pago. Por favor, contacte al administrador.",
            ], 400);
        }
    }



    // public function calcularPlan(Request $request)
    // {

    //     try {
    //         // $idCliente = $request->idCliente;
    //         $parcelaById = Parcela::where([
    //             ['id_parcela', '=', $request->idParcela],
    //         ])->get()[0];

    //         $cantidadCuotas = $request->cantidadCuotas;
    //         $promedioCemento = $request->promedioCemento;
    //         $precioTotalEntrega = $request->precioTotalEntrega;

    //         // CALCULAR TOTAL BOLSAS DE CEMENTO POR EL PROMEDIO DEL CEMENTO
    //         $precioTotalTerreno = $parcelaById->cantidad_bolsas * $promedioCemento;
    //         // RESTAR PRECIO TOTAL DEL TERRENO MENOS LA ENTREGA

    //         $valorTotalFinanciar = $precioTotalTerreno - $precioTotalEntrega;

    //         // OBTENER TOTAL BOLSAS DE CEMENTO PARA EL LOTE
    //         $cantidadBolsasCementoTerreno = $valorTotalFinanciar / $promedioCemento;
    //         // $cantidadBolsasCementoTerreno = number_format(($valorTotalFinanciar / $promedioCemento), 0, ',');

    //         // OBTENER BOLSAS DE CEMENTO A PAGAR MENSUAL
    //         $bolsasCementoMensual = round($cantidadBolsasCementoTerreno / $cantidadCuotas, 2);
    //         // $bolsasCementoMensual = number_format(($cantidadBolsasCementoTerreno / $cantidadCuotas), 2, ',');

    //         // CONVERTIR BOLSAS CEMENTO MENSUAL A PESOS
    //         $valorCuotaMensual = round($bolsasCementoMensual * $promedioCemento, 2);

    //         $fechaDesdeDestallePlan = Carbon::now()->addMonth(1)->format('Y-m') . '-21';
    //         $fechaHastaDestallePlan = Carbon::now()->addMonth(6)->format('Y-m') . '-21';

    //         return response()->json([
    //             'fechaDesde' => $fechaDesdeDestallePlan,
    //             'fechaHasta' => $fechaHastaDestallePlan,
    //             'cuotaMensualBolsasCemento' => $bolsasCementoMensual,
    //             'valorCuota' => $valorCuotaMensual,
    //             'valorTotalFinanciar' => $valorTotalFinanciar,
    //             'precioTotalTerreno' => $precioTotalTerreno,
    //         ]);

    //     } catch (Exception $e) {
    //         return response()->json([
    //             'mensaje' => "Se produjo un error por favor contacte con el adminstrador",
    //         ], 400);
    //     }

    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(StoreVentasRequest $request)
    // {
    //     // dd($request->all());
    //     DB::beginTransaction();

    //     try {
    //         $idCliente = $request->id_cliente;
    //         $idParcela = $request->id_parcela;
    //         $parcelaById = Parcela::find($idParcela);

    //         $cantidadCuotas = $request->cuotas;
    //         $promedioCemento = $request->promedio_cemento;
    //         $precioTotalEntrega = $request->precio_total_entrega;

    //         // CALCULAR TOTAL BOLSAS DE CEMENTO POR EL PROMEDIO DEL CEMENTO
    //         $precioTotalTerreno = $parcelaById->cantidad_bolsas * $promedioCemento;
    //         // RESTAR PRECIO TOTAL DEL TERRENO MENOS LA ENTREGA

    //         $valorTotalFinanciar = $precioTotalTerreno - $precioTotalEntrega;

    //         // OBTENER TOTAL BOLSAS DE CEMENTO PARA EL LOTE
    //         $cantidadBolsasCementoTerreno = $valorTotalFinanciar / $promedioCemento;
    //         // $cantidadBolsasCementoTerreno = number_format(($valorTotalFinanciar / $promedioCemento), 0, ',');

    //         // OBTENER BOLSAS DE CEMENTO A PAGAR MENSUAL
    //         $bolsasCementoMensual = round($cantidadBolsasCementoTerreno / $cantidadCuotas, 2);
    //         // $bolsasCementoMensual = number_format(($cantidadBolsasCementoTerreno / $cantidadCuotas), 2, ',');

    //         // CONVERTIR BOLSAS CEMENTO MENSUAL A PESOS
    //         $valorCuotaMensual = round($bolsasCementoMensual * $promedioCemento, 2);

    //         $fechaDesdeDestallePlan = Carbon::now()->addMonth(1)->format('Y-m') . '-21';
    //         $fechaHastaDestallePlan = Carbon::now()->addMonth(6)->format('Y-m') . '-21';

    //         // GUARDAR VENTA

    //         $ventaGuardada = Venta::create([
    //             'cuotas' => $cantidadCuotas,
    //             'precio_total_terreno' => $precioTotalTerreno,
    //             'cuota_mensual_bolsas_cemento' => $bolsasCementoMensual,
    //             'precio_total_entrega' => $precioTotalEntrega,
    //             'precio_final' => $valorTotalFinanciar,
    //             'id_parcela' => $idParcela,
    //             'id_cliente' => $idCliente,
    //         ]);

    //         for ($i = 1; $i <= 6; $i++) {
    //             DetalleVenta::create([
    //                 'numero_cuota' => $i,
    //                 'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-21',
    //                 'total_estimado_a_pagar' => $valorCuotaMensual,
    //                 'id_venta' => $ventaGuardada->id_venta,
    //             ]);
    //         }

    //         DetallePlan::create([
    //             'fecha_desde' => $fechaDesdeDestallePlan,
    //             'fecha_hasta' => $fechaHastaDestallePlan,
    //             'valor_cuota' => $valorCuotaMensual,
    //             'id_venta' => $ventaGuardada->id_venta,
    //         ]);

    //         $parcelaById->update(['disponible' => 0]);

    //         DB::commit();

    //         return redirect()->route('ventas.crear')->with('success', 'Venta realizado correctamente!');
    //     } catch (Exception $e) {
    //         DB::rollback();

    //         return redirect()->route('ventas.crear')->with('error', 'Error al realizar la venta!');

    //     }

    // }
}