<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\DetalleVenta;

class FormCobrarCuotas extends Component
{
    public $cuota;
    public $venta;
    public $diferenciasDias = 0;
    public $totalIntereses;
    public $totalEstimadoAbonar = 0;
    public $incrementoInteres = 0;
    public $totalAbonar = 0;
    public $isDisabled = true;
    // public $pagado = false;

    protected $rules = [
        'totalIntereses' => 'required|numeric|min:0',
    ];

    public function mount()
    {

        $this->totalEstimadoAbonar = (int) $this->cuota->total_estimado_a_pagar;

        $result = Carbon::createFromFormat('Y-m-d', $this->cuota->fecha_maxima_a_pagar)->isPast();

        if ($result) {
            $date = Carbon::parse($this->cuota->fecha_maxima_a_pagar);
            $now = Carbon::now();
            $this->diferenciasDias = $date->diffInDays($now);
        }
    }

    public function updated($propertyName)
    {
        $this->isDisabled = true;
        $this->validateOnly($propertyName);
        $this->isDisabled = false;

    }

    public function calcularAbono()
    {
        $this->validate();

        $this->incrementoInteres = $this->totalEstimadoAbonar * ($this->totalIntereses / 100);
        $this->totalAbonar = $this->incrementoInteres + $this->totalEstimadoAbonar;
    }

    public function submit()
    {

        $this->validate();

      

        try {

            //  

             $numeroRecibo = DetalleVenta::where('numero_recibo','!=',null)->orderBy('numero_recibo','desc')->value('numero_recibo');

             
            // dd($numeroRecibo);

            DB::beginTransaction();

           

            $this->cuota->total_intereses = $this->totalIntereses;
            $this->cuota->total_pago = $this->totalAbonar;
            $this->cuota->fecha_pago = date('Y-m-d');
            $this->cuota->pagado = 'si';
            $this->cuota->numero_recibo = $numeroRecibo === null ? 1500 : $numeroRecibo + 1;

            $this->cuota->save();

   


            // if ($this->cuota->numero_cuota === "1") {
            //     $this->venta->fecha_inicio_pago = Carbon::now();

            //     $this->venta->save();
            // }

           

            DB::commit();
            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('success', "Cuota guardada exitosamente <a href=" . route('clientes.volantePago', $this->cuota->id_detalle_venta) . " target='_blank'>Haga click aqui </a>para descargar el volante de pago."
            );
        } catch (\Throwable$e) {

            DB::rollback();

            // dd($e->getMessage());
            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('error', 'Error al pagar la cuota, contacte al Administrador!');
        }

    }

    public function render()
    {
        return view('livewire.form-cobrar-cuotas');
    }
}