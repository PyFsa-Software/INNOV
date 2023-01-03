<?php

namespace App\Http\Controllers;

use App\DataTables\LotesDataTable;
use App\Http\Requests\StoreLotesRequest;
use App\Models\Lote;
use App\Models\Parcela;

class LotesController extends Controller
{
    public function LotesIndex(LotesDataTable $dataTable)
    {

        $lotes = Lote::all();

        return $dataTable->render('lotes.index', compact('lotes'));

    }

    public function CrearLoteView()
    {

        return view('lotes.crear');

    }
    public function CrearLote(StoreLotesRequest $request)
    {

        try {

            $nuevoLote = new Lote;

            $nuevoLote->nombre_lote = $request->nombre_lote;
            $nuevoLote->hectareas_lote = $request->hectareas_lote;
            $nuevoLote->cantidad_manzanas = $request->cantidad_manzanas;
            $nuevoLote->ubicacion = $request->ubicacion;

            $nuevoLote->save();

            return back()->with('success', 'Lote creado correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al crear el registro de lote!');
        }

    }
    public function EditarLoteView(Lote $lote)
    {

        return view('lotes.editar', compact('lote'));

    }
    public function EditarLote(StoreLotesRequest $request, Lote $lote)
    {

        try {

            $nombre_lote = $request->nombre_lote;
            $hectareas_lote = $request->hectareas_lote;
            $cantidad_manzanas = $request->cantidad_manzanas;
            $ubicacion = $request->ubicacion;

            $lote->update([
                'nombre_lote' => $nombre_lote,
                'hectareas_lote' => $hectareas_lote,
                'cantidad_manzanas' => $cantidad_manzanas,
                'ubicacion' => $ubicacion,
            ]);
            return back()->with('success', 'Lote actualizado correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al editar el Lote!');

        }

    }
    public function EliminarLoteView(Lote $lote)
    {
        $cantidadParcelasLotes = Parcela::all()->where('id_lote', '=', $lote->id_lote)->count();

        return view('lotes.eliminar', compact('lote', 'cantidadParcelasLotes'));

    }
    public function EliminarLote(Lote $lote)
    {
        try {
            $lote->delete();
            return redirect()->route('lotes.index')->with('success', 'Lote eliminado correctamente!');
        } catch (\Throwable$th) {
            return redirect()->route('lotes.index')->with('error', 'Error al eliminar el Lote!');

        }
    }
}