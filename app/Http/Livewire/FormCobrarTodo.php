<?php

namespace App\Http\Livewire;

use App\Enums\ConceptoDe;
use App\Enums\MonedaPago;
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
    public $cantidadCuotasPagar = '';
    public $cantidadCuotasPorPagar = 0;
    public $precioCuotas;
    public $cuotasSinPagar = 0;
    public $formaPago = "";
    public $message = "";
    public $conceptoDeOpcionesSelect = [];
    public $conceptoDe = "";
    public $leyenda = "";
    public $precioActual;
    public $isDisabled = true;
    public $parcela;
    public $monedaPago = "";
    public $monedasDePagos = [];

    public function rules()
    {
        $rules = [
            'cantidadCuotasPagar' => 'required|integer|min:0',
            'formaPago' => 'required',
            'conceptoDe' => 'required',
            'leyenda' => 'string'
        ];

        return $rules;
    }

    public function mount()
    {
        $this->monedasDePagos = MonedaPago::toArray();
        $this->parcela = Venta::where('id_venta', '=', $this->venta->id_venta)->with('parcela')->first();
        $this->conceptoDeOpcionesSelect = ConceptoDe::toArray();
    }

    public function updatedCantidadCuotasPagar()
    {
        $this->resetValidation();

        $restoCuotas = $this->venta->cuotas - $this->cuotasPagadas;
        $this->cuotasSinPagar = $this->cuotasGeneradas - $this->cuotasPagadas;

        $this->message = "";
        $this->isDisabled = true;


        if ($this->cantidadCuotasPagar < 2) {
            $this->addError('cantidadCuotasPagar', 'La cantidad de cuotas a pagar debe ser mayor que dos.');
            // O cualquier otro valor predeterminado
        } elseif ($this->cantidadCuotasPagar > $restoCuotas) {
            // $this->cantidadCuotasPagar = $restoCuotas;
            $this->addError('cantidadCuotasPagar', "No se pueden pagar más de $restoCuotas cuotas.");
        } elseif ($this->cuotasSinPagar > 0 && $this->cantidadCuotasPagar <= $this->cuotasSinPagar) {
            $this->cantidadCuotasPorPagar = $this->cuotasSinPagar - $this->cantidadCuotasPagar;
            $this->message = "Se pagaran $this->cantidadCuotasPagar cuota(s) de las que ya existen. Ya que actualmente hay $this->cuotasSinPagar cuota(s) generada(s) sin pagar.";


            $this->isDisabled = false;
        } elseif ($this->cuotasSinPagar >= 0 && $this->cantidadCuotasPagar >= $this->cuotasSinPagar) {
            $this->cantidadCuotasPorPagar = $this->cantidadCuotasPagar - $this->cuotasSinPagar;
            $this->message = "Se generarán $this->cantidadCuotasPorPagar cuota(s) adicionales. Actualmente hay $this->cuotasSinPagar cuota(s) generada(s) sin pagar.";



            $this->isDisabled = false;
        }
    }

    public function submit()
    {
        try {
            $this->validate();
            DB::beginTransaction();

            $parcela = Venta::where('id_venta', '=', $this->venta->id_venta)->with('parcela')->first();

            $ultimaCuota = DetalleVenta::where('id_venta', '=', $this->venta->id_venta)
                ->orderByRaw('CAST(numero_cuota AS SIGNED) DESC')->value('numero_cuota');

            $numeroRecibo = DetalleVenta::getSiguienteNumeroRecibo();

            $cuotasNoPagadas = DetalleVenta::where('id_venta', '=', $this->venta->id_venta)
                ->where('pagado', '=', 'no')->get();

            if (count($cuotasNoPagadas) >=  $this->cantidadCuotasPagar) {

                // Si hay suficientes cuotas no pagadas, pagar solo la cantidad requerida
                $this->cantidadCuotasPagar = intval($this->cantidadCuotasPagar);
                        $cuotasNoPagadas->take($this->cantidadCuotasPagar)->each(function ($cuota) use (&$numeroRecibo) {
                            $cuota->update([
                                'pagado' => 'si',
                                'numero_recibo' => $numeroRecibo,
                                'forma_pago' => $this->formaPago,
                                'total_estimado_a_pagar' => $this->precioActual,
                                'total_pago' => $this->precioActual,
                                'fecha_pago' => Carbon::now()->format('Y-m-d'),
                                'concepto_de' => $this->conceptoDe,
                                'moneda_pago' => $this->monedaPago,
                                'leyenda' => $this->leyenda
                            ]);
                        });
            
         
            } else {

                // Si no hay suficientes cuotas no pagadas, pagar todas y generar y pagar las que faltan
                

                $cuotasNoPagadas->each(function ($cuota) use (&$numeroRecibo) {

                    $cuota->update([
                        'pagado' => 'si',
                        'numero_recibo' => $numeroRecibo,
                        'forma_pago' => $this->formaPago,
                        'total_estimado_a_pagar' => $this->precioActual,
                        'total_pago' => $this->precioActual,
                        'fecha_pago' => Carbon::now()->format('Y-m-d'),
                        'concepto_de' => $this->conceptoDe,
                        'moneda_pago' => $this->monedaPago,
                        'leyenda' => $this->leyenda
                    ]);
                });


                $cuotasFaltantes = $this->cantidadCuotasPagar - count($cuotasNoPagadas);


                for ($i = 1; $i <= $cuotasFaltantes; $i++) {
                    $ultimaCuota++;
                    DetalleVenta::create([
                        'numero_cuota' => $ultimaCuota,
                        'fecha_maxima_a_pagar' => Carbon::now()->addMonth($i)->format('Y-m') . '-15',
                        'fecha_pago' => Carbon::now()->format('Y-m-d'),
                        'total_estimado_a_pagar' => $this->precioActual,
                        'total_pago' => $this->precioActual,
                        'pagado' => 'si',
                        'numero_recibo' => $numeroRecibo,
                        'id_venta' => $this->venta->id_venta,
                        'forma_pago' => $this->formaPago,
                        'concepto_de' => $this->conceptoDe,
                        'moneda_pago' => $this->monedaPago,
                        'leyenda' => $this->leyenda
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('clientes.estadoCuotas', $parcela->parcela->id_parcela)
                ->with('success', "Cuotas generadas y pagadas correctamente.");
        } catch (\Throwable $e) {
            dd($e);
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