<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\ParcelasDataTable;
use App\Models\Parcela;
use App\Models\Lote;

class ParcelasController extends Controller
{
    public function ParcelasIndex(ParcelasDataTable $dataTable)
    {

        $parcelas = Parcela::all();

        return $dataTable->render('parcelas.index', compact('parcelas'));

    

    }

    public function CrearParcelaView()
    {
     
        $lotes = Lote::all();

        return view('parcelas.crear', compact('lotes'));
        
    }
    public function CrearParcela(Request $request)
    {

       try {
         // dd($request->all());

         $nuevaParcela = new Parcelas;

        //  dd($request->all());

         $nuevaParcela->superficie_parcela = $request->superficie_parcela;
         $nuevaParcela->manzana = $request->manzana;
         $nuevaParcela->cantidad_bolsas = $request->cantidad_bolsas;
         $nuevaParcela->ancho = $request->ancho;
         $nuevaParcela->largo = $request->largo;
         $nuevaParcela->id_lote = $request->lote;
 
         $nuevaParcela->save();
 
         return back()->with('success', 'Parcela creada correctamente!');
       } catch (\Throwable $th) {
        return back()->with('error', 'Error al crear el registro de la Parcela!');
       }


    }
    public function EditarParcelaView(Parcela $parcela)
    {

        $lotes = Lote::all();

        return view('parcelas.editar', compact('parcela', 'lotes'));

    }
    public function EditarLote(Request $request,  Parcela $parcela)
    {


        try {


            $nombre_lote = $request->nombre_lote;
            $superficie_lote = $request->superficie_lote;
            $cantidad_manzanas = $request->cantidad_manzanas;
            $ubicacion = $request->ubicacion;

            $parcela->update([
                'nombre_lote' => $nombre_lote,
                'superficie_lote' => $superficie_lote,
                'cantidad_manzanas' => $cantidad_manzanas,
                'ubicacion' => $ubicacion,
            ]);
            return back()->with('success', 'Parcela actualizada correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al editar la Parcela!');

        }



    }
    public function EliminarLoteView(Parcela $parcela)
    {

        return view('parcelas.eliminar', compact('parcela'));

    }
    public function EliminarLote( Parcela $parcela)
    {
        try {
            $lote->delete();
            return redirect()->route('parcelas')->with('success', 'Parcela eliminada correctamente!');
        } catch (\Throwable$th) {
            return redirect()->route('parcelas')->with('error', 'Error al eliminar la Parcela!');

        }
    }
}