<?php

namespace App\Http\Livewire;


use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Models\Precio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormEditarPrecioCuota extends Component
{

    

    public $cuota;
    public $idParcela;
    public $promedioCementoNuevo;
    public $listaPromedioCemento;
    public $promedio6Meses;
    public $precioNuevoCuota;
    public $isDisabled = true;

    protected $rules = [
        'precioNuevoCuota' => 'required|numeric|min:1',
    ];

    public function updated($propertyName)
    {
        $this->isDisabled = true;
        $this->validateOnly($propertyName);
        $this->isDisabled = false;
    }
    public function mount()
    {
        $this->listaPromedioCemento = Precio::orderBy("fecha", "DESC")
            ->take(6)
            ->get()
            ->each(function ($promedioFila) {
                $this->promedio6Meses += $promedioFila->precio_promedio;
            });
        $this->promedio6Meses = (int) $this->promedio6Meses / 6;

        $parcela = Venta::where('id_venta',$this->cuota->id_venta)->value('id_parcela');


        $this->idParcela = $parcela;

 
    }

    // public function calcularActualizacion()
    // {
    //     $this->validate();

    //     $cuotaMensualBolsasCemento = Venta::where('id_venta',$this->cuota->id_venta)->value('cuota_mensual_bolsas_cemento');

    //     dd($cuotaMensualBolsasCemento);

    //     (int) $this->totalAbonarProximosMeses =  (int) 8 * (int) intval($this->promedioCementoNuevo);
    // }


    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->cuota->total_estimado_a_pagar = $this->precioNuevoCuota;

            $this->cuota->fecha_actualizacion = Carbon::now()->format('Y-m');

            $this->cuota->save();

            
            DB::commit();
            return redirect()->route('clientes.estadoCuotas', $this->idParcela)->with('success', "Se ha Modificado correctamente el precio de la cuota."
            );
        } catch (\Throwable$e) {

            DB::rollback();

            dd($e->getMessage());
            return redirect()->route('clientes.estadoCuotas', $this->idParcela)->with('error', 'Error al realizar la modificacion del precio de la cuota, contacte con al administrador.');
        }
    }

    

    public function render()
    {
        return view('livewire.form-editar-precio-cuota');
    }
}
