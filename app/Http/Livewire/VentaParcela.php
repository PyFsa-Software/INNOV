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
    public $periodosActualizacion;
    // seleccionar usuario
    public $clienteCombo = "";
    public $parcelaCombo = "";
    public $cantidadCuotas = 0;
    public $periodoActualizacion = "";
    // public $precioTotalEntrega = 0;
    public $promedioCemento = 0;

    // se actualiza automaticamente

    public $parcelaById;
    public $fechaDesdeDetallePlan;
    public $fechaHastaDetallePlan;
    public $bolsasCementoMensual;
    public $valorCuotaMensual;

    // public $valorTotalFinanciar;
    public $precioTotalTerreno;

    public $isDisabled = true;

    protected $rules = [
        'clienteCombo' => 'required|numeric|integer',
        'parcelaCombo' => 'required|numeric|integer',
        'cantidadCuotas' => 'required|numeric|integer|min:1|int',
        // 'precioTotalEntrega' => 'required|numeric|min:1',
        'promedioCemento' => 'required|numeric|integer|min:1',
        'periodoActualizacion' => 'required',
    ];

    protected $listeners = ['setCliente'];

    public function setCliente($value)
    {
        $this->clienteCombo = $value;
        $this->obtenerParcelasCliente();
        $this->calcularPlan();
    }


    public function updated($propertyName)
    {
        $this->isDisabled = true;
        $this->validateOnly($propertyName);
        $this->isDisabled = false;
    }


    public function obtenerParcelasCliente()
    {
        // Obtener las parcelas asociadas al cliente seleccionado
        $parcelas = Parcela::join('reserva_parcela', 'parcelas.id_parcela', '=', 'reserva_parcela.id_parcela')
            ->where('reserva_parcela.id_cliente', '=', $this->clienteCombo)
            ->select('parcelas.id_parcela', 'parcelas.descripcion_parcela', 'parcelas.id_lote', 'parcelas.cantidad_bolsas', 'parcelas.manzana')
            ->get();

        $this->parcelas = $parcelas;
    }

    public function updatedClienteCombo($value)
    {
        $this->obtenerParcelasCliente();
        $this->calcularPlan();
    }

    // Dentro de la clase VentaParcela

    private function obtenerMesesPorPeriodo()
    {
        switch ($this->periodoActualizacion) {
            case 'BIMESTRAL':
                return 2;
            case 'TRIMESTRAL':
                return 3;
            case 'SEMESTRAL':
                return 6;
            default:
                return 6;
        }
    }



    public function calcularPlan()
    {
        $this->validate();

        $this->parcelaById = Parcela::where([
            ['id_parcela', '=', $this->parcelaCombo],
        ])->first();

        if ($this->parcelaById->cantidad_bolsas == "") {
            $this->addError('parcelaCombo', 'La parcela seleccionada no tiene cantidad de bolsas de cemento.');
            $this->isDisabled = true;
            return;
        }
        $this->isDisabled = false;


        // CALCULAR TOTAL BOLSAS DE CEMENTO POR EL PROMEDIO DEL CEMENTO
        $this->precioTotalTerreno = (int)$this->parcelaById->cantidad_bolsas * (int)$this->promedioCemento;

        // OBTENER TOTAL BOLSAS DE CEMENTO PARA EL LOTE
        $cantidadBolsasCementoTerreno = $this->precioTotalTerreno / $this->promedioCemento;
        // $cantidadBolsasCementoTerreno = number_format(($valorTotalFinanciar / $promedioCemento), 0, ',');

        // OBTENER BOLSAS DE CEMENTO A PAGAR MENSUAL
        $this->bolsasCementoMensual = round($cantidadBolsasCementoTerreno / $this->cantidadCuotas, 2);

        // CONVERTIR BOLSAS CEMENTO MENSUAL A PESOS
        $this->valorCuotaMensual = round($this->bolsasCementoMensual * $this->promedioCemento, 2);

        // OBTENER FECHA DESDE Y HASTA DEL PLAN DE PAGO
        $this->fechaDesdeDetallePlan = Carbon::now()->addMonth(1)->format('Y-m') . '-15';
        $this->fechaHastaDetallePlan = Carbon::now()->addMonth($this->cantidadCuotas)->format('Y-m') . '-15';
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // dd($this->formaPago, $this->importeEntrega);

            // $this->conceptoDe = $this->conceptoDe == '' ? null : $this->conceptoDe;

            $ventaGuardada = Venta::create([
                'cuotas' => $this->cantidadCuotas,
                'precio_total_terreno' => $this->precioTotalTerreno,
                'cuota_mensual_bolsas_cemento' => $this->bolsasCementoMensual,
                'fecha_actualizacion_precio' => Carbon::now()->addMonth($this->obtenerMesesPorPeriodo())->format('Y-m') . '-01',
                // 'precio_total_entrega' => $this->precioTotalEntrega,
                // 'precio_final' => $this->valorTotalFinanciar,
                // 'importe_entrega' => $this->importeEntrega,
                // 'forma_pago' => $this->formaPago,
                // 'concepto_de' => $this->conceptoDe,
                'id_parcela' => $this->parcelaCombo,
                'id_cliente' => $this->clienteCombo,
                'update_period' => $this->periodoActualizacion,
            ]);

            $totalCuotas = DetalleVenta::where('id_venta', '=', $ventaGuardada->id_venta)->count('id_detalle_venta');

            $planCuota = $ventaGuardada->cuotas;

            $restoCuotas = $planCuota - $totalCuotas;

            if ($restoCuotas < 6) {
                for ($i = 1; $i <= $restoCuotas; $i++) {

                    DetalleVenta::create([
                        'numero_cuota' => $i,
                        'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-15',
                        'total_estimado_a_pagar' => $this->valorCuotaMensual,
                        'id_venta' => $ventaGuardada->id_venta,
                    ]);
                }
            } else {
                for ($i = 1; $i <= 6; $i++) {

                    DetalleVenta::create([
                        'numero_cuota' => $i,
                        'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-15',
                        'total_estimado_a_pagar' => $this->valorCuotaMensual,
                        'id_venta' => $ventaGuardada->id_venta,
                    ]);
                }
            }

            $this->parcelaById->update(['disponible' => 0]);

            DB::commit();

            return redirect()->route('ventas.crear')->with('success', "Venta realizada correctamente.");
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->route('ventas.crear')->with('error', "Error al intentar guardar la venta, contacte con el administrador.");
        }
    }

    public function render()
    {
        return view('livewire.venta-parcela');
    }
}