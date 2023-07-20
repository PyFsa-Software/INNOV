<?php

namespace App\Http\Livewire;

use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormCobrarCuotas extends Component
{
    public $cuota;
    public $formasDePagos;
    public $venta;
    public $diferenciasDias = 0;
    public $totalIntereses;
    public $totalEstimadoAbonar = 0;
    public $incrementoInteres = 0;
    public $totalAbonar = 0;
    public $forma_pago = "";
    public $isDisabled = true;
    // public $pagado = false;

    // protected $rules = [
    //     'totalIntereses' => 'required|numeric|min:0',
    //     'formasDePagos' => 'required',
    // ];

    public function rules()
    {
        return [
            'totalIntereses' => 'required|numeric|min:0',
            'forma_pago' => 'required',
        ];
    }

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
        try {
            $this->validateOnly($propertyName);
            $this->isDisabled = false;
        } catch (\Throwable $e) {
            $this->isDisabled = true;
        }
    }

    public function calcularAbono()
    {
        $this->validate();

        // $this->incrementoInteres = $this->totalEstimadoAbonar * ($this->totalIntereses / 100);
        $this->totalAbonar = $this->totalIntereses + $this->totalEstimadoAbonar;
    }

    public function submit()
    {
        $this->validate();
        try {
            $numeroRecibo = DetalleVenta::where('numero_recibo', '!=', null)->orderBy('numero_recibo', 'desc')->value('numero_recibo');

            DB::beginTransaction();

            $fechaMaximaPagar = Carbon::create($this->cuota->fecha_maxima_a_pagar)->format('Y-m');

            if ($fechaMaximaPagar == getFechaActual() || $fechaMaximaPagar < getFechaActual()) {
                $this->cuota->total_intereses = $this->totalIntereses;
                $this->cuota->total_pago = $this->totalAbonar;
                $this->cuota->fecha_pago = date('Y-m-d');
                $this->cuota->pagado = 'si';
                $this->cuota->numero_recibo = $numeroRecibo === null ? 1500 : $numeroRecibo + 1;

                $this->cuota->save();

            } elseif ($fechaMaximaPagar > getFechaActual()) {

                $diferenciaDeMeses = Carbon::create($this->cuota->fecha_maxima_a_pagar)->diffInMonths(getFechaActual());

                // dd($diferenciaDeMeses);

                $this->cuota->fecha_maxima_a_pagar = Carbon::create($this->cuota->fecha_maxima_a_pagar)->subMonth($diferenciaDeMeses)->format('Y-m') . '-21';
                $this->cuota->total_intereses = $this->totalIntereses;
                $this->cuota->total_pago = $this->totalAbonar;
                $this->cuota->fecha_pago = date('Y-m-d');
                $this->cuota->pagado = 'si';
                $this->cuota->numero_recibo = $numeroRecibo === null ? 1500 : $numeroRecibo + 1;
                $this->cuota->forma_pago = $this->forma_pago;

                dd($this->forma_pago);

                $cuotaPagada = $this->cuota->save();

                DB::commit();
                // dd($this->cuota);

                $cuotasPosterioresPagar = DetalleVenta::where('id_venta', $this->cuota->id_venta)->where('pagado', 'no')->orderByRaw("CAST(numero_cuota AS UNSIGNED) ASC")->get();

                $cuotasPosterioresPagar->reduce(function ($fecha, $cuotasSinPagar) {

                    $proximaFecha = Carbon::create($fecha)->addMonth(1)->format('Y-m') . '-21';

                    $cuotasSinPagar->fecha_maxima_a_pagar = $proximaFecha;

                    $cuotasSinPagar->save();

                    return $proximaFecha;

                }, $this->cuota->fecha_maxima_a_pagar);

            }

            DB::commit();

            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('success', "Cuota guardada exitosamente <a href=" . route('clientes.volantePago', $this->cuota->id_detalle_venta) . " target='_blank'>Haga click aqui </a>para descargar el volante de pago."
            );
        } catch (\Throwable $e) {

            // dd($e);
            DB::rollback();

            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('error', 'Error al pagar la cuota, contacte al Administrador!');
        }

    }

    public function render()
    {
        return view('livewire.form-cobrar-cuotas');
    }
}