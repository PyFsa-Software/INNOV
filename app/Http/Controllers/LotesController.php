<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\LotesDataTable;
use App\Models\Lote;

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
    public function CrearLote(Request $request)
    {

       try {
         // dd($request->all());

         $nuevoLote = new Lotes;

         $nuevoLote->nombre_lote = $request->nombre_lote;
         $nuevoLote->superficie_lote = $request->superficie_lote;
         $nuevoLote->cantidad_manzanas = $request->cantidad_manzanas;
         $nuevoLote->ubicacion = $request->ubicacion;
 
         $nuevoLote->save();
 
         return back()->with('success', 'Lote creado correctamente!');
       } catch (\Throwable $th) {
        return back()->with('error', 'Error al crear el registro de lote!');
       }


    }
    public function EditarLoteView(Lote $lote)
    {

        return view('lotes.editar', compact('lote'));

    }
    public function EditarLote(Request $request,  Lote $lote)
    {


        try {


            $nombre_lote = $request->nombre_lote;
            $superficie_lote = $request->superficie_lote;
            $cantidad_manzanas = $request->cantidad_manzanas;
            $ubicacion = $request->ubicacion;

            $lote->update([
                'nombre_lote' => $nombre_lote,
                'superficie_lote' => $superficie_lote,
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

        return view('lotes.eliminar', compact('lote'));

    }
    public function EliminarLote( Lote $lote)
    {
        try {
            $lote->delete();
            return redirect()->route('lotes')->with('success', 'Lote eliminado correctamente!');
        } catch (\Throwable$th) {
            return redirect()->route('lotes')->with('error', 'Error al eliminar el Lote!');

        }
    }
}