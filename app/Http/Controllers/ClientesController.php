<?php

namespace App\Http\Controllers;

use App\DataTables\ClientesDataTable;
use App\Models\Personas;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClientesDataTable $dataTable)
    {
        $clientes = Personas::all()->where('cliente', '=', '1');
        return $dataTable->render('clientes.index', compact('clientes'));

        // // dd($data);
        // if ($request->ajax()) {
        //     $data = Personas::select(DB::raw("CONCAT(nombre,' ',apellido) AS nombre_apellido"), 'dni', 'celular', 'correo')->get();
        //     return DataTables::of($data)->addIndexColumn()
        //         ->addColumn('editar', function ($row) {
        //             $btn = '<a href="{}" class="btn btn-warning btn-sm">Editar</a>';
        //             return $btn;
        //         })
        //         ->addColumn('eliminar', function ($row) {
        //             $btn = '<a href="{}" class="btn btn-danger btn-sm">Eliminar</a>';
        //             return $btn;
        //         })
        //         ->rawColumns(['editar', 'eliminar'])
        //         ->make(true);
        // }

        // return view('clientes.index', compact('clientes'));

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