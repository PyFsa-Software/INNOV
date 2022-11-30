<?php

namespace App\Http\Controllers;

use App\DataTables\PreciosDataTable;
use App\Http\Requests\StorePreciosRequest;
use App\Models\Precios;
use Illuminate\Http\Request;

class PreciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PreciosDataTable $dataTable)
    {
        $precios = Precios::all();

        return $dataTable->render('precios.index', compact('precios'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("precios.crear");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePreciosRequest $request)
    {
        // dd($request->all());
        try {
            $precioBercomat = $request->precio_bercomat;
            $precioSanCayetano = $request->precio_sancayetano;
            $precioRioColorado = $request->precio_rio_colorado;

            $promedio = ($precioBercomat + $precioSanCayetano + $precioRioColorado) / 3;

            $promedioRedondeado = number_format((float) $promedio, 2, '.', '');
            Precios::create([
                'precio_bercomat' => $precioBercomat,
                'precio_sancayetano' => $precioSanCayetano,
                'precio_rio_colorado' => $precioRioColorado,
                'precio_promedio' => $promedioRedondeado,
            ]);
            return back()->with('success', 'Precio creado correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al crear el registro de precio!');

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
    public function edit(Precios $precio)
    {
        return view('precios.editar', compact('precio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePreciosRequest $request, Precios $precio)
    {
        try {
            $precioBercomat = $request->precio_bercomat;
            $precioSanCayetano = $request->precio_sancayetano;
            $precioRioColorado = $request->precio_rio_colorado;

            $promedio = ($precioBercomat + $precioSanCayetano + $precioRioColorado) / 3;

            $promedioRedondeado = number_format((float) $promedio, 2, '.', '');
            $precio->update([
                'precio_bercomat' => $precioBercomat,
                'precio_sancayetano' => $precioSanCayetano,
                'precio_rio_colorado' => $precioRioColorado,
                'precio_promedio' => $promedioRedondeado,
            ]);
            return back()->with('success', 'Precio actualizado correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al editar el registro de precio!');

        }

    }

    public function showQuestion(Precios $precio)
    {
        return view('precios.eliminar', compact('precio'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Precios $precio)
    {
        // dd($precio);
        try {
            $precio->delete();
            return redirect()->route('precios.index')->with('success', 'Precio eliminado correctamente!');
        } catch (\Throwable$th) {
            return redirect()->route('precios.index')->with('error', 'Error al eliminar el registro de precio!');

        }

    }
}