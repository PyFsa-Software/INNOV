<?php

namespace App\Http\Controllers;

use App\DataTables\ParcelasDataTable;
use App\Http\Requests\StoreParcelasRequest;
use App\Models\Lote;
use App\Models\Parcela;

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
    public function CrearParcela(StoreParcelasRequest $request)
    {

        try {

            $nuevaParcela = new Parcela;

            $nuevaParcela->descripcion_parcela = $request->descripcion_parcela;
            $nuevaParcela->superficie_parcela = $request->superficie_parcela;
            $nuevaParcela->manzana = $request->manzana;
            $nuevaParcela->cantidad_bolsas = $request->cantidad_bolsas;
            $nuevaParcela->ancho = $request->ancho;
            $nuevaParcela->largo = $request->largo;
            $nuevaParcela->id_lote = $request->lote;

            $nuevaParcela->save();

            return back()->with('success', 'Parcela creada correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al crear el registro de la Parcela!');
        }

    }
    public function EditarParcelaView(Parcela $parcela, Lote $lote)
    {

        $idLote = $parcela->id_lote;

        $loteSeleccionado = Lote::find($idLote);

        $lotes = Lote::all();

        return view('parcelas.editar', compact('parcela', 'loteSeleccionado', 'lotes'));

    }
    public function EditarParcela(StoreParcelasRequest $request, Parcela $parcela)
    {

        try {

            $parcela->update([
                'descripcion_parcela' => $request->descripcion_parcela,
                'superficie_parcela' => $request->superficie_parcela,
                'manzana' => $request->manzana,
                'cantidad_bolsas' => $request->cantidad_bolsas,
                'ancho' => $request->ancho,
                'largo' => $request->largo,
                'id_lote' => $request->lote,
            ]);
            return back()->with('success', 'Parcela actualizada correctamente!');
        } catch (\Throwable$th) {
            return back()->with('error', 'Error al editar la Parcela!');

        }

    }
    public function EliminarParcelaView(Parcela $parcela, Lote $lote)
    {

        $idLote = $parcela->id_lote;

        $loteSeleccionado = Lote::find($idLote);

        $lotes = Lote::all()->where('id_lote', '!=', $idLote);

        return view('parcelas.eliminar', compact('parcela', 'loteSeleccionado'));

    }
    public function EliminarParcela(Parcela $parcela)
    {
        try {

            $parcela->delete();
            return redirect()->route('parcelas')->with('success', 'Parcela eliminada correctamente!');
        } catch (\Throwable$th) {
            return redirect()->route('parcelas')->with('error', 'Error al eliminar la Parcela!');

        }
    }
}