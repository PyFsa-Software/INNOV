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
    public $formasDePagos;
    public $formaPago = "";
    public $conceptosDe;
    public $conceptoDe = "";
    // seleccionar usuario
    public $clienteCombo = "";
    public $parcelaCombo = "";
    public $cantidadCuotas = 0;
    // public $precioTotalEntrega = 0;
    public $promedioCemento = 0;

    // se actualiza automaticamente

    public $parcelaById;
    public $fechaDesdeDetallePlan;
    public $fechaHastaDetallePlan;
    public $bolsasCementoMensual;
    public $valorCuotaMensual;
    public $importeEntrega;
    // public $valorTotalFinanciar;
    public $precioTotalTerreno;

    public $isDisabled = true;

    protected $rules = [
        'clienteCombo' => 'required|numeric|integer',
        'parcelaCombo' => 'required|numeric|integer',
        'cantidadCuotas' => 'required|numeric|integer|min:1|int',
        // 'precioTotalEntrega' => 'required|numeric|min:1',
        'promedioCemento' => 'required|numeric|integer|min:1',
        'importeEntrega' => 'required|numeric|min:0',
        'formaPago' => 'required|string|max:255',
        'conceptoDe' => 'string|max:255|required',
    ];

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
        $this->precioTotalTerreno = ($this->parcelaById->cantidad_bolsas * $this->promedioCemento);

        // OBTENER TOTAL BOLSAS DE CEMENTO PARA EL LOTE
        $cantidadBolsasCementoTerreno = $this->precioTotalTerreno / $this->promedioCemento;
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

            // dd($this->formaPago, $this->importeEntrega);

            $this->conceptoDe = $this->conceptoDe == '' ? null : $this->conceptoDe;

            $ventaGuardada = Venta::create([
                'cuotas' => $this->cantidadCuotas,
                'precio_total_terreno' => $this->precioTotalTerreno,
                'cuota_mensual_bolsas_cemento' => $this->bolsasCementoMensual,
                'fecha_actualizacion_precio'=> Carbon::now()->addMonth(6)->format('Y-m') . '-01' ,
                // 'precio_total_entrega' => $this->precioTotalEntrega,
                // 'precio_final' => $this->valorTotalFinanciar,
                'importe_entrega' => $this->importeEntrega,
                'forma_pago' => $this->formaPago,
                'concepto_de' => $this->conceptoDe,
                'id_parcela' => $this->parcelaCombo,
                'id_cliente' => $this->clienteCombo,
            ]);

            $totalCuotas = DetalleVenta::where('id_venta','=',$ventaGuardada->id_venta)->count('id_detalle_venta');

            $planCuota = $ventaGuardada->cuotas; 

            $restoCuotas = $planCuota - $totalCuotas;

            if ($restoCuotas < 6) {
                for ($i = 1; $i <= $restoCuotas; $i++) {

                DetalleVenta::create([
                    'numero_cuota' => $i,
                    'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-21',
                    'total_estimado_a_pagar' => $this->valorCuotaMensual,
                    'id_venta' => $ventaGuardada->id_venta,
                ]);
            }
            }else{
                for ($i = 1; $i <= 6; $i++) {

                    DetalleVenta::create([
                        'numero_cuota' => $i,
                        'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-21',
                        'total_estimado_a_pagar' => $this->valorCuotaMensual,
                        'id_venta' => $ventaGuardada->id_venta,
                    ]);
                }
            }

            $this->parcelaById->update(['disponible' => 0]);

            DB::commit();

            return redirect()->route('ventas.listado')->with('success', "Venta realizada correctamente.");
        } catch (\Throwable$e) {
            DB::rollback();
            return redirect()->route('ventas.crear')->with('error', "Error al intentar guardar la venta, contacte con el administrador.");

        }

    }

    public function render()
    {
        return view('livewire.venta-parcela');
    }
}