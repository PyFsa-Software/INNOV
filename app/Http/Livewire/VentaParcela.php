<?php

namespace App\Http\Livewire;

use App\Models\DetallePlan;
use App\Models\DetalleVenta;
use App\Models\Parcela;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VentaParcela extends Component
{

    // props
    public $clientes;
    public $parcelas;
    public $promedioCementoDelMes;

    // seleccionar usuario
    public $clienteCombo = "";
    public $parcelaCombo = "";
    public $cantidadCuotas = 0;
    public $precioTotalEntrega = 0;
    public $promedioCemento = 0;

    // se actualiza automaticamente

    public $parcelaById;
    public $fechaDesdeDetallePlan;
    public $fechaHastaDetallePlan;
    public $bolsasCementoMensual;
    public $valorCuotaMensual;
    public $valorTotalFinanciar;
    public $precioTotalTerreno;

    public $isDisabled = true;

    protected $rules = [
        'clienteCombo' => 'required|numeric',
        'parcelaCombo' => 'required|numeric',
        'cantidadCuotas' => 'required|numeric|min:1',
        'precioTotalEntrega' => 'required|numeric|min:1',
        'promedioCemento' => 'required|numeric|min:1',
    ];

    public function mount()
    {

    }

    public function updated($propertyName)
    {
        $this->isDisabled = true;
        $this->validateOnly($propertyName);
        $this->isDisabled = false;
    }

    public function calcularPlan()
    {
        $this->validate();

        $this->parcelaById = Parcela::where([
            ['id_parcela', '=', $this->parcelaCombo],
        ])->first();

        // CALCULAR TOTAL BOLSAS DE CEMENTO POR EL PROMEDIO DEL CEMENTO
        $this->precioTotalTerreno = $this->parcelaById->cantidad_bolsas * $this->promedioCemento;

        // RESTAR PRECIO TOTAL DEL TERRENO MENOS LA ENTREGA
        $this->valorTotalFinanciar = $this->precioTotalTerreno - $this->precioTotalEntrega;

        // OBTENER TOTAL BOLSAS DE CEMENTO PARA EL LOTE
        $cantidadBolsasCementoTerreno = $this->valorTotalFinanciar / $this->promedioCemento;
        // $cantidadBolsasCementoTerreno = number_format(($valorTotalFinanciar / $promedioCemento), 0, ',');

        // OBTENER BOLSAS DE CEMENTO A PAGAR MENSUAL
        $this->bolsasCementoMensual = round($cantidadBolsasCementoTerreno / $this->cantidadCuotas, 2);

        // CONVERTIR BOLSAS CEMENTO MENSUAL A PESOS
        $this->valorCuotaMensual = round($this->bolsasCementoMensual * $this->promedioCemento, 2);

        // OBTENER FECHA DESDE Y HASTA DEL PLAN DE PAGO
        $this->fechaDesdeDetallePlan = Carbon::now()->addMonth(1)->format('Y-m') . '-21';
        $this->fechaHastaDetallePlan = Carbon::now()->addMonth(6)->format('Y-m') . '-21';

    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $ventaGuardada = Venta::create([
                'cuotas' => $this->cantidadCuotas,
                'precio_total_terreno' => $this->precioTotalTerreno,
                'cuota_mensual_bolsas_cemento' => $this->bolsasCementoMensual,
                'precio_total_entrega' => $this->precioTotalEntrega,
                'precio_final' => $this->valorTotalFinanciar,
                'id_parcela' => $this->parcelaCombo,
                'id_cliente' => $this->clienteCombo,
            ]);

            for ($i = 1; $i <= 6; $i++) {
                DetalleVenta::create([
                    'numero_cuota' => $i,
                    'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-21',
                    'total_estimado_a_pagar' => $this->valorCuotaMensual,
                    'id_venta' => $ventaGuardada->id_venta,
                ]);
            }

            DetallePlan::create([
                'fecha_desde' => $this->fechaDesdeDetallePlan,
                'fecha_hasta' => $this->fechaHastaDetallePlan,
                'valor_cuota' => $this->valorCuotaMensual,
                'id_venta' => $ventaGuardada->id_venta,
            ]);

            $this->parcelaById->update(['disponible' => 0]);

            DB::commit();

            // $this->cuota->total_intereses = $this->totalIntereses;
            // $this->cuota->total_pago = $this->totalAbonar;
            // $this->cuota->fecha_pago = date('Y-m-d');
            // $this->cuota->pagado = 'si';

            // $this->cuota->save();

            // $this->pagado = true;

            return redirect()->route('ventas.crear')->with('success', "Venta realizada correctamente, puede visualizarla desde el modulo de detalle de clientes.");
        } catch (\Throwable$e) {

            // dd($e->getMessage());
            DB::rollback();
            return redirect()->route('ventas.crear')->with('error', "Error al intentar guardar la venta, contacte con el administrador.");
            // session()->flash('error', 'Error al intentar guardar la venta, contacte con el administrador.');

        }

    }

    public function render()
    {
        return view('livewire.venta-parcela');
    }
}