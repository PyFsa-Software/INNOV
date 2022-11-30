<?php

namespace App\Http\Controllers;

use App\DataTables\ClientesDataTable;
use App\Http\Requests\StoreClientesRequest;
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
            Personas::create(request()->all());
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
    public function edit(Personas $persona)
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
    public function update(StoreClientesRequest $request, Personas $persona)
    {
        try {
            $persona->update($request->all());
            return back()->with('success', 'Cliente editado correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al actualizar los datos del cliente!');

        }

    }

    public function showQuestionActivate(Personas $persona)
    {
        return view('clientes.activar', compact('persona'));
    }

    public function activate(Personas $persona)
    {
        try {
            $persona->activo = 1;
            $persona->save();
            return redirect()->route('clientes.index')->with('success', 'Cliente activado correctamente!');
        } catch (\Throwable$th) {
            return redirect()->route('clientes.index')->with('error', 'Error al reactivar el cliente!');

        }
    }

    public function showQuestionDestroy(Personas $persona)
    {
        return view('clientes.eliminar', compact('persona'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Personas $persona)
    {
        try {
            $persona->activo = 0;
            $persona->save();
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente!');
        } catch (\Throwable$th) {
            return redirect()->route('clientes.index')->with('error', 'Error al eliminar al cliente!');

        }
    }
}