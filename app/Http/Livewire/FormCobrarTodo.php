<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FormCobrarTodo extends Component
{
    public $venta;
    public $cuotasGeneradas;
    public $cuotasPagadas;
    public $cantidadCuotasPagar;
    public $precioCuotas;
    public $cuotasSinPagar = 0;
    public $message;

    protected $listeners = ['updatedCantidadCuotasPagar' => 'actualizarCuotas'];


    public function validaciones()
    {
        
        $restoCuotas = $this->venta->cuotas - $this->cuotasPagadas;

        $cuotasSinPagar = $this->cuotasGeneradas - $this->cuotasPagadas;

        $parcela = Venta::where('id_venta', '=', $this->venta->id_venta)->with('parcela')->first();

        if(intval($this->cantidadCuotasPagar) == 1 ){
            return redirect()->route('clientes.estadoCuotas', $parcela->parcela->id_parcela)
                ->with('error', "En este modulo no se puede pagar una sola cuota.");
        }
        

        if (intval($this->cantidadCuotasPagar) > $restoCuotas) {
            return redirect()->route('clientes.estadoCuotas', $parcela->parcela->id_parcela)
                ->with('error', "No se pueden pagar más de $restoCuotas cuotas.");
        }


        if($cuotasSinPagar >=1){
            $this->cantidadCuotasPagar = intval($this->cantidadCuotasPagar) - $cuotasSinPagar;
        }

    }


    public function submit()
    {
        // Validaciones iniciales
        $this->validate([
            'cantidadCuotasPagar' => 'required|integer|min:1',
            'precioCuotas' => 'required|numeric|min:0',
        ]);

        // Otras validaciones como la cantidad de cuotas generadas sin pagar

        try {
            DB::beginTransaction();

            // dd($this->cantidadCuotasPagar, $this->precioCuotas);

            // dd($this->venta->id_venta);

            $parcela = Venta::where('id_venta', '=', $this->venta->id_venta)->with('parcela')->first();


            // dd($this->cantidadCuotasPagar);

            $ultimaCuota = DetalleVenta::where('id_venta', '=', $this->venta->id_venta)
            ->orderByRaw('CAST(numero_cuota AS SIGNED) DESC')->value('numero_cuota');

            $numeroRecibo = DetalleVenta::where('numero_recibo','!=',null)->orderBy('numero_recibo','desc')->value('numero_recibo');

            $numeroRecibo = intval($numeroRecibo) + 1;

            for ($i = 1; $i <= $this->cantidadCuotasPagar; $i++) {
                $ultimaCuota++;
                DetalleVenta::create([
                    'numero_cuota' => $ultimaCuota,
                    'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-21',
                    'total_estimado_a_pagar' => $this->precioCuotas,
                    'pagado' => 'si',
                    'numero_recibo' =>$numeroRecibo,
                    'id_venta' => $this->venta->id_venta,
                ]);
            }

            
            DB::commit();
            
        
            return redirect()->route('clientes.estado', $this->venta->id_cliente)
                ->with('success', "Cuotas generadas y pagadas correctamente.");
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->route('clientes.estadoCuotas', $parcela->parcela->id_parcela)
                ->with('error', 'Error al generar y pagar cuotas. Contacte al administrador.');
        }
    }

    public function render()
    {
        return view('livewire.form-cobrar-todo');
    }
}