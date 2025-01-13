<?php

namespace App\Http\Controllers;

use App\DataTables\ClientesDataTable;
use App\DataTables\CuotasVentasDataTable;
use App\DataTables\PagosMultiplesDataTable;
use App\Enums\FormasPago;
use App\Enums\Intereses;
use App\Http\Requests\StoreClientesRequest;
use App\Models\DetalleVenta;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClientesDataTable $dataTable)
    {
        $clientes = Persona::all()->where('cliente', '=', '1');
        return $dataTable->render('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('clientes.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientesRequest $request)
    {
        // dd($request->all());

        $dataPersona = $request->all();

        $dataPersona["celular"] = $dataPersona["celular"] != "" ? $dataPersona["celular"] : "";
        $dataPersona["correo"] = $dataPersona["correo"] != "" ? $dataPersona["correo"] : "";
        // dd($dataPersona);

        try {
            Persona::create($dataPersona);
            return back()->with('success', 'Cliente creado correctamente!');
        } catch (\Throwable $th) {
            dd($th);
            return back()->with('error', 'Error al crear al registrar el cliente!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Persona $persona)
    {
        return view('clientes.editar', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreClientesRequest $request, Persona $persona)
    {
        try {
            $dataPersona = $request->all();

            $dataPersona["celular"] = $dataPersona["celular"] != "" ? $dataPersona["celular"] : "";
            $dataPersona["correo"] = $dataPersona["correo"] != "" ? $dataPersona["correo"] : "";
            $persona->update($dataPersona);
            return back()->with('success', 'Cliente editado correctamente!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Error al actualizar los datos del cliente!');
        }
    }

    public function showQuestionActivate(Persona $persona)
    {
        return view('clientes.activar', compact('persona'));
    }

    public function activate(Persona $persona)
    {
        try {
            $persona->activo = 1;
            $persona->save();
            return redirect()->route('clientes.index')->with('success', 'Cliente activado correctamente!');
        } catch (\Throwable $th) {
            return redirect()->route('clientes.index')->with('error', 'Error al reactivar el cliente!');
        }
    }

    public function showQuestionDestroy(Persona $persona)
    {
        return view('clientes.eliminar', compact('persona'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        try {
            $persona->activo = 0;
            $persona->save();
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente!');
        } catch (\Throwable $th) {
            return redirect()->route('clientes.index')->with('error', 'Error al eliminar al cliente!');
        }
    }

    public function estadoCliente(Persona $persona)
    {
        // Obtener las ventas de la persona con los campos id_parcela e id_venta
        $ventas = Venta::where('id_cliente', '=', $persona->id_persona)->get(['id_parcela', 'id_venta','update_period']);

    
        // Obtener las parcelas relacionadas a las ventas
        $parcelas = Parcela::whereIn('id_parcela', $ventas->pluck('id_parcela'))->get();
    
        // Asociar el id_venta a cada parcela
        $parcelass = $parcelas->map(function ($parcela) use ($ventas) {
            $parcela->id_venta = $ventas->firstWhere('id_parcela', $parcela->id_parcela)->id_venta;
            return $parcela;
        });
        // Pasar los datos a la vista
        return view('clientes.estado', compact('persona', 'parcelas','ventas'));
    }
    
    public function estadoCuotas(CuotasVentasDataTable $dataTable, $idParcela)
    {
        $venta = Venta::select('id_venta', 'id_cliente')->where('id_parcela', '=', $idParcela)->first();

        $idCliente = $venta->id_cliente;
        return $dataTable->with('idVenta', $venta->id_venta)->render('clientes.cuotasVentas', compact('idCliente', 'idParcela'));
    }
    public function cobrarCuotas(DetalleVenta $cuota, Venta $venta)
    {

        $idVenta = venta::all()->where('id_venta', '=', $cuota->id_venta);
        $formasDePagos = FormasPago::toArray();
        $intereses = Intereses::toArray();

        return view('clientes.cobrarCuotas', compact('cuota', 'formasDePagos', 'intereses'));
    }

    public function editarPrecioCuota(DetalleVenta $cuota)
    {

        // dd($cuota);
        return view('clientes.editarPrecioCuota', compact('cuota'));
    }

    public function updatePrecioCuota(StoreClientesRequest $request, DetalleVenta $cuota)
    {

        try {


            $cuota->update($request->all());
            return back()->with('success', 'Precio Actualizado correctamente!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Error al actualizar el Precio de la cuota!');
        }
    }

    public function generarVolantePago(DetalleVenta $cuota)
    {
        $venta = Venta::all()->where('id_parcela', '=', $cuota->id_parcela)->first();
        $cliente = Persona::all()->where('id_persona', '=', $venta->id_cliente)->first();
        $parcela = Parcela::with('lote')->where('id_parcela', '=', $cuota->id_parcela)->first();

        $pathLogo = Storage::path('public/img/logoInnova.jpg');
        $logo = file_get_contents($pathLogo);

        $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="100" height="100" />';

        $pdf = Pdf::loadView('clientes.volantePago', compact('cuota', 'venta', 'cliente', 'parcela', 'pathLogo', 'html'))
            ->setPaper('cart', 'vertical');

        return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
    }


    public function generarVolantePagoMultiple($numeroRecibo)
    {
        $detalleVentas = DetalleVenta::where('numero_recibo', $numeroRecibo)->get();

        // dd($detalleVentas);

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

        $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="100" height="100" />';



        $pdf = Pdf::loadView('clientes.volantePagoMultiple', compact('detalleVentas', 'venta', 'cliente', 'parcela', 'pathLogo', 'html', 'numeroPrimeraCuota', 'numeroUltimaCuota', 'totalPago', 'conceptoDe'))
            ->setPaper('cart', 'vertical');

        return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
    }




    public function actualizarPrecios(Request $request, Parcela $parcela)
    {
        // dd($request->all());
        $venta = $request->venta;
        $ultimaCuota = $request->ultimaCuota;

        return view('clientes.actualizarPrecios', compact('venta', 'parcela', 'ultimaCuota'));
    }



    public function cobrarTodo($idParcela)
    {


        $venta = Venta::where('id_parcela', $idParcela)->first();

        $cantidadCuotasGeneradas = DetalleVenta::where('id_venta', $venta->id_venta)->count();

        $cantidadCuotasPagadas = DetalleVenta::where('id_venta', $venta->id_venta)->where('pagado', 'si')->count();

        $formasDePagos = FormasPago::toArray();

        $maxPrecioActual = DetalleVenta::where('id_venta', $venta->id_venta)
            ->selectRaw('MAX(CAST(total_estimado_a_pagar AS DECIMAL(10, 2))) as max_precio')
            ->first();

        $precioActual = $maxPrecioActual->max_precio ?? 0.0;



        return view('clientes.cobrarTodo', compact('venta', 'cantidadCuotasGeneradas', 'cantidadCuotasPagadas', 'formasDePagos', 'precioActual'));
    }

    public function volantesPagosMultiples(PagosMultiplesDataTable $dataTable, $idParcela)
    {
        $venta = Venta::select('id_venta', 'id_cliente')->where('id_parcela', '=', $idParcela)->first();

        $idCliente = $venta->id_cliente;
        return $dataTable->with('idVenta', $venta->id_venta)->render('clientes.pagosMultiples', compact('idCliente', 'idParcela'));
    }

    public function generarCuotas(Request $request, Parcela $parcela)
    {
        $venta = $request->venta;
        $ultimaCuota = $request->ultimaCuota;

        // var_dump($venta);
        // var_dump($ultimaCuota);


        return view('clientes.generarCuotas', compact('venta', 'parcela', 'ultimaCuota'));
    }


    public function actualizarPreciosCuotasVencidas(Parcela $parcela)
    {

        $ventas = Venta::where('id_parcela', $parcela->id_parcela)->first();
        $cuotasPrecioVencido = DetalleVenta::where('id_venta', $ventas->id_venta)
            ->get()
            ->filter(function ($detalleVenta) {
                return $detalleVenta->getActualizarCuotasAttribute();
            });
        return view('clientes.actualizarCuotasVencidas.actualizarCuotasVencidas', compact('cuotasPrecioVencido', 'parcela'));
    }

    public function guardarPreciosCuotasVencidas(Request $request, Parcela $parcela)
    {

        $rules = [
            'preciosCuotasVencidas' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ventas = Venta::where('id_parcela', $parcela->id_parcela)->first();
        $cuotasPrecioVencido = DetalleVenta::where('id_venta', $ventas->id_venta)
            ->get()
            ->filter(function ($detalleVenta) {
                return $detalleVenta->getActualizarCuotasAttribute();
            });
        foreach ($cuotasPrecioVencido as $cuota) {
            $cuota->update([
                'total_estimado_a_pagar' => $request->input('preciosCuotasVencidas'),
                'fecha_actualizacion' => Carbon::now()->format('Y-m'),
            ]);
        }

        return redirect()->route('clientes.estadoCuotas', [$parcela->id_parcela])->with('success', 'Precios actualizados correctamente!');
    }

    public function calcularDeuda(Parcela $parcela)
    {
        // Obtener las opciones de interés desde el Enum
        $intereses = Intereses::toArray();

        // Pasamos la información de la parcela y las opciones de interés a la vista
        return view('clientes.calcularDeuda', compact('parcela', 'intereses'));
    }

    public function calcularDeudaResultado(Request $request, Parcela $parcela)
    {
        // Obtener el interés como porcentaje del request
        $interesDiario = (float) $request->input('interes');

        // Obtener el id de venta relacionado con la parcela
        $idVenta = Venta::where('id_parcela', $parcela->id_parcela)->value('id_venta');

        // Obtener las cuotas vencidas para calcular el total con interés
        $cuotasVencidas = DetalleVenta::where('id_venta', $idVenta)
            ->where('fecha_maxima_a_pagar', '<', date('Y-m-d'))
            ->where('pagado', '!=', 'si')
            ->get();

        // Calcular el monto total con interés
        $totalConInteres = 0;
        $cuotasCalculadas = [];

        foreach ($cuotasVencidas as $cuota) {
            $diasVencidos = Carbon::parse($cuota->fecha_maxima_a_pagar)->diffInDays(Carbon::today());

            // Calcula el incremento de interés como porcentaje aplicado al monto original
            $totalInteres = $diasVencidos * ($interesDiario / 100); // Divide entre 100 para obtener el porcentaje real
            $incrementoInteres = round($cuota->total_estimado_a_pagar * $totalInteres, 2);
            $montoConInteres = $cuota->total_estimado_a_pagar + $incrementoInteres;

            $totalConInteres += $montoConInteres;

            // Agregar detalle de cada cuota vencida al arreglo
            $cuotasCalculadas[] = [
                'monto_original' => $cuota->total_estimado_a_pagar,
                'dias_vencidos' => $diasVencidos,
                'monto_con_interes' => $montoConInteres,
                'cuota' => $cuota->numero_cuota,
            ];
        }

        return response()->json([
            'cuotasCalculadas' => $cuotasCalculadas,
            'totalConInteres' => $totalConInteres,
        ]);
    }
}