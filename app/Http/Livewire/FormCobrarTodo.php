<?php

namespace App\Http\Livewire;

use App\Enums\ConceptoDe;
use Livewire\Component;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FormCobrarTodo extends Component
{
    public $venta;
    public $formasDePagos;
    public $cuotasGeneradas;
    public $cuotasPagadas;
    public $cantidadCuotasPagar;
    public $precioCuotas;
    public $cuotasSinPagar = 0;
    public $formaPago = "";
    public $message = "";
    public $conceptoDeOpcionesSelect = [];
    public $conceptoDe = "";
    public $precioActual;

    public function actualizarCantidadCuotas($value)
    {
        $this->cantidadCuotasPagar = $value;

        $restoCuotas = $this->venta->cuotas - $this->cuotasPagadas;
    
        $this->cuotasSinPagar =  $this->cuotasGeneradas - $this->cuotasPagadas;
    
        $this->message = "";
    
        if (intval($this->cantidadCuotasPagar) > $restoCuotas) {
            $this->message = "No se pueden pagar más de $restoCuotas cuotas.";
            $this->cantidadCuotasPagar = $restoCuotas;
        } elseif (intval($this->cuotasSinPagar) > 0 && intval($this->cantidadCuotasPagar) > intval($this->cuotasSinPagar)) {
            $this->cantidadCuotasPagar = intval($this->cantidadCuotasPagar) - intval($this->cuotasSinPagar);
        } elseif (intval($this->cantidadCuotasPagar) == 1) {
            $this->message = "En este módulo no se puede pagar una sola cuota.";
        }
    }

    public function submit()
    {
        // Validaciones iniciales
        $this->validate([
            'cantidadCuotasPagar' => 'required|integer|min:1',
            'formaPago' => 'required',
            'conceptoDe' => 'required',
        ]);

        // Otras validaciones como la cantidad de cuotas generadas sin pagar

        try {
            DB::beginTransaction();

            $parcela = Venta::where('id_venta', '=', $this->venta->id_venta)->with('parcela')->first();

            $ultimaCuota = DetalleVenta::where('id_venta', '=', $this->venta->id_venta)
                ->orderByRaw('CAST(numero_cuota AS SIGNED) DESC')->value('numero_cuota');

            $numeroRecibo = DetalleVenta::where('numero_recibo', '!=', null)->orderBy('numero_recibo', 'desc')->value('numero_recibo');
            $numeroRecibo = intval($numeroRecibo) + 1;

            $cuotasNoPagadas = DetalleVenta::where('id_venta', '=', $this->venta->id_venta)
                ->where('pagado', '=', 'no')->get();
            
            if (count($cuotasNoPagadas) > 0) {
                foreach ($cuotasNoPagadas as $cuota) {
                    $cuota->pagado = 'si';
                    $cuota->numero_recibo = $numeroRecibo;
                    $cuota->forma_pago = $this->formaPago;
                    $cuota->total_pago = $this->precioActual;
                    $cuota->fecha_pago = Carbon::now()->format('Y-m-d');
                    $cuota->concepto_de = $this->conceptoDe;
                    $cuota->save();
                }
            }

            for ($i = 1; $i <= $this->cantidadCuotasPagar; $i++) {
                $ultimaCuota++;
                DetalleVenta::create([
                    'numero_cuota' => $ultimaCuota,
                    'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-21',
                    'fecha_pago' => Carbon::now()->format('Y-m-d'),
                    'total_estimado_a_pagar' => $this->precioActual,
                    'total_pago' => $this->precioActual,
                    'pagado' => 'si',
                    'numero_recibo' => $numeroRecibo,
                    'id_venta' => $this->venta->id_venta,
                    'forma_pago' => $this->formaPago,
                    'concepto_de' => $this->conceptoDe,
                ]);

            }

            DB::commit();

            return redirect()->route('clientes.estado', $this->venta->id_cliente)
                ->with('success', "Cuotas generadas y pagadas correctamente.");
        } catch (\Throwable $e) {
            // dd($e);
            DB::rollback();
            return redirect()->route('clientes.estadoCuotas', $parcela->parcela->id_parcela)
                ->with('error', 'Error al generar y pagar cuotas. Contacte al administrador.');
        }
    }

    public function render()
    {
        $this->conceptoDeOpcionesSelect = ConceptoDe::toArray();


        return view('livewire.form-cobrar-todo', [
            'disabled' => $this->message !== '' ? true : false,
        ]);
    }
}