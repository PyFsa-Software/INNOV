<?php

namespace App\Http\Controllers;

use App\DataTables\ClientesDataTable;
use App\DataTables\CuotasVentasDataTable;
use App\Http\Requests\StoreClientesRequest;
use App\Models\DetalleVenta;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;

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
        try {
            Persona::create(request()->all());
            return back()->with('success', 'Cliente creado correctamente!');
        } catch (\Throwable$th) {
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
            $persona->update($request->all());
            return back()->with('success', 'Cliente editado correctamente!');
        } catch (\Throwable$th) {
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
        } catch (\Throwable$th) {
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
        } catch (\Throwable$th) {
            return redirect()->route('clientes.index')->with('error', 'Error al eliminar al cliente!');

        }
    }

    public function estadoCliente(Persona $persona)
    {

        // obtener los is de parcelas compradas por una persona
        $idsParcelas = Venta::all()->where('id_cliente', '=', $persona->id_persona)->pluck('id_parcela');

        // obtener las parcelas
        $parcelas = Parcela::whereIn('id_parcela', $idsParcelas)->get();

        // dd($parcelas[1]->cantidadDeudas);
        return view('clientes.estado', compact('persona', 'parcelas'));

    }
    public function estadoCuotas(CuotasVentasDataTable $dataTable, $idVenta)
    {
        return $dataTable->with('idVenta', $idVenta)->render('clientes.cuotasVentas');
    }
    public function cobrarCuotas(DetalleVenta $cuota)
    {
        // if ($cuota->pagado == 'si') {
        //     return back()->withErrors(['error' => ['La cuota seleccionada ya esta pagada']]);
        // }

        return view('clientes.cobrarCuotas', compact('cuota'));
    }

    public function generarVolantePago(DetalleVenta $cuota)
    {
        // dd($cuota);
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
        ];

        // ->setOptions(['defaultFont' => 'sans-serif'])
        $pdf = Pdf::loadView('clientes.volantePago', $data);

        return $pdf->stream('test_pdf.pdf', array('Attachment' => 0));

        // $pdf->stream("", array("Attachment" => false));

        // return $pdf->download('volantePago.pdf');

        // $pdfContent = Pdf::loadView('clientes.volantePago', $data)->output();
        // response()->streamDownload(
        //     fn() => print($pdfContent),
        //     "filename.pdf"
        // );

    }

}