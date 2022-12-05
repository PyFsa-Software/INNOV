<?php

namespace App\Http\Controllers;

use App\DataTables\VentasDataTable;
use App\Models\Venta;
use Illuminate\Http\Request;

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
        return $dataTable->render('ventas.index', compact('ventas'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ventas.crear');
    }

    public function calcularPlan(Request $request)
    {
        $idCliente = $request->idCliente;
        $idParcela = $request->idParcela;
        $cantidadCuotas = $request->cantidadCuotas;

        return response()->json([
            'fechaDesde' => $idCliente,
            'fechaHasta' => $idParcela,
            'valorCuota' => $cantidadCuotas,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}