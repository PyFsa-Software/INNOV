<?php
namespace App\Http\Livewire;

use App\Enums\ConceptoDe;
use App\Enums\Intereses;
use App\Enums\MonedaPago;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormCobrarCuotas extends Component
{
    public $cuota;
    public $formasDePagos;
    public $intereses;
    public $venta;
    public $diferenciasDias = 0;
    public $totalIntereses = 0;
    public $totalEstimadoAbonar = 0;
    public $incrementoInteres = 0;
    public $totalAbonar = 0;
    public $formaPago = "";
    public $interes = "";
    public $conceptoDe = "";
    public $isDisabled = true;
    public $conceptoDeOpcionesSelect = [];
    public $monedaPago = "";
    public $monedasDePagos = [];

    public function rules()
    {
        $rules = [
            'totalIntereses' => 'required|numeric|min:0',
            'formaPago' => 'required',
            'conceptoDe' => 'required',
        ];

        if ($this->diferenciasDias > 0) {
            $rules['interes'] = 'required';
        }

        return $rules;
    }

    public function mount()
    {
        $this->monedasDePagos = MonedaPago::toArray();
        $this->intereses = Intereses::toArray();
        $this->totalEstimadoAbonar = (int) $this->cuota->total_estimado_a_pagar;

        $result = Carbon::createFromFormat('Y-m-d', $this->cuota->fecha_maxima_a_pagar)->isPast();
        $this->conceptoDeOpcionesSelect = ConceptoDe::toArray();

        if ($result) {
            $date = Carbon::parse($this->cuota->fecha_maxima_a_pagar);
            $now = Carbon::now();
            $this->diferenciasDias = $date->diffInDays($now);
        }
        
        $this->calcularIntereses();
        $this->calcularAbono();
    }

    public function updated($propertyName)
    {
        try {
            $this->validateOnly($propertyName);
            $this->isDisabled = false;
        } catch (\Throwable $e) {
            $this->isDisabled = true;
        }

        if ($propertyName == 'totalIntereses' || $propertyName == 'interes') {
            $this->calcularIntereses();
            $this->calcularAbono();
        }
    }

    public function calcularIntereses()
    {
        $this->totalIntereses = $this->diferenciasDias * ($this->interes ? (float) $this->interes : 0);
    }

    public function calcularAbono()
    {
        $this->incrementoInteres = round($this->totalEstimadoAbonar * ($this->totalIntereses / 100), 2);
        $this->totalAbonar = $this->totalEstimadoAbonar + $this->incrementoInteres;
    }

    public function submit()
    {
        $this->validate();
        try {
            $numeroRecibo = DetalleVenta::getSiguienteNumeroRecibo();
            DB::beginTransaction();

            $fechaMaximaPagar = Carbon::create($this->cuota->fecha_maxima_a_pagar)->format('Y-m');

            if ($fechaMaximaPagar == getFechaActual() || $fechaMaximaPagar < getFechaActual()) {
                $this->cuota->total_intereses = $this->totalIntereses;
                $this->cuota->total_pago = $this->totalAbonar;
                $this->cuota->fecha_pago = date('Y-m-d');
                $this->cuota->pagado = 'si';
                $this->cuota->numero_recibo = $numeroRecibo === null ? 1500 : $numeroRecibo;
                $this->cuota->forma_pago = $this->formaPago;
                $this->cuota->concepto_de = $this->conceptoDe;
                $this->cuota->moneda_pago = $this->monedaPago;

                $this->cuota->save();

            } elseif ($fechaMaximaPagar > getFechaActual()) {

                $diferenciaDeMeses = Carbon::create($this->cuota->fecha_maxima_a_pagar)->diffInMonths(getFechaActual());

                $this->cuota->fecha_maxima_a_pagar = Carbon::create($this->cuota->fecha_maxima_a_pagar)->subMonth($diferenciaDeMeses)->format('Y-m') . '-15';
                $this->cuota->total_intereses = $this->totalIntereses;
                $this->cuota->total_pago = $this->totalAbonar;
                $this->cuota->fecha_pago = date('Y-m-d');
                $this->cuota->pagado = 'si';
                $this->cuota->numero_recibo = $numeroRecibo === null ? 1500 : $numeroRecibo;
                $this->cuota->forma_pago = $this->formaPago;
                $this->cuota->concepto_de = $this->conceptoDe;
                $this->cuota->moneda_pago = $this->monedaPago;

                $this->cuota->save();

                DB::commit();

                $cuotasPosterioresPagar = DetalleVenta::where('id_venta', $this->cuota->id_venta)->where('pagado', 'no')->orderByRaw("CAST(numero_cuota AS UNSIGNED) ASC")->get();

                $cuotasPosterioresPagar->reduce(function ($fecha, $cuotasSinPagar) {

                    $proximaFecha = Carbon::create($fecha)->addMonth(1)->format('Y-m') . '-15';

                    $cuotasSinPagar->fecha_maxima_a_pagar = $proximaFecha;

                    $cuotasSinPagar->save();

                    return $proximaFecha;

                }, $this->cuota->fecha_maxima_a_pagar);

            }

            DB::commit();

            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('success', "Cuota guardada exitosamente <a href=" . route('clientes.volantePago', $this->cuota->id_detalle_venta) . " target='_blank'>Haga click aqui </a>para descargar el volante de pago.");
        } catch (\Throwable $e) {
            DB::rollback();

            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('error', 'Error al pagar la cuota, contacte al Administrador!');
        }
    }

    public function render()
    {
        return view('livewire.form-cobrar-cuotas');
    }
}