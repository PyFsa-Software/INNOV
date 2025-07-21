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
    public $leyenda = "";
    public $isDisabled = true;
    public $conceptoDeOpcionesSelect = [];
    public $monedaPago = "";
    public $monedasDePagos = [];

    // Propiedades para configuración de fechas
    public $configurarFechas = false;
    public $cuotasSiguientes = [];
    public $nuevaFechaCuotaActual = '';
    public $nuevaFechaSiguientes = '';
    public $cantidadCuotasConfigurar = 1;
    public $puedeConfigurarFechas = false;
    public $mostrarConfiguracion = false;

    public function rules()
    {
        $rules = [
            'totalIntereses' => 'required|numeric|min:0',
            'formaPago' => 'required',
            'conceptoDe' => 'required',
            'leyenda' => 'string',
        ];

        if ($this->diferenciasDias > 0) {
            $rules['interes'] = 'required';
        }

        // Validaciones para configuración de fechas
        if ($this->configurarFechas) {
            if ($this->nuevaFechaCuotaActual && !Carbon::createFromFormat('Y-m-d', $this->cuota->fecha_maxima_a_pagar)->isPast()) {
                $rules['nuevaFechaCuotaActual'] = 'required|date|after_or_equal:today';
            }
            if ($this->nuevaFechaSiguientes && !empty($this->cuotasSiguientes)) {
                $rules['nuevaFechaSiguientes'] = 'required|date|after_or_equal:today';
                $rules['cantidadCuotasConfigurar'] = 'required|integer|min:1|max:' . count($this->cuotasSiguientes);
            }
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
        $this->cargarCuotasSiguientes();
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

        if ($propertyName == 'configurarFechas') {
            $this->mostrarConfiguracion = $this->configurarFechas;
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

    public function cargarCuotasSiguientes()
    {
        // Obtener cuotas siguientes no vencidas
        $cuotasSiguientes = DetalleVenta::where('id_venta', $this->cuota->id_venta)
            ->where('pagado', 'no')
            ->where('numero_cuota', '>', $this->cuota->numero_cuota)
            ->where('fecha_maxima_a_pagar', '>', date('Y-m-d'))
            ->orderByRaw("CAST(numero_cuota AS UNSIGNED) ASC")
            ->get();

        $this->cuotasSiguientes = $cuotasSiguientes->toArray();

        // Verificar si puede configurar fechas
        $this->puedeConfigurarFechas = $cuotasSiguientes->count() > 0 ||
            !Carbon::createFromFormat('Y-m-d', $this->cuota->fecha_maxima_a_pagar)->isPast();

        // Inicializar cantidad máxima
        $this->cantidadCuotasConfigurar = min(1, $cuotasSiguientes->count());
    }

    public function validarFechasConfiguracion()
    {
        $errores = [];

        if ($this->nuevaFechaCuotaActual) {
            $fechaNueva = Carbon::createFromFormat('Y-m-d', $this->nuevaFechaCuotaActual);
            if ($fechaNueva->isPast()) {
                $errores[] = 'La nueva fecha de la cuota actual no puede ser anterior a hoy.';
            }
        }

        if ($this->nuevaFechaSiguientes) {
            $fechaNueva = Carbon::createFromFormat('Y-m-d', $this->nuevaFechaSiguientes);
            if ($fechaNueva->isPast()) {
                $errores[] = 'La nueva fecha para cuotas siguientes no puede ser anterior a hoy.';
            }
        }

        if ($this->cantidadCuotasConfigurar > count($this->cuotasSiguientes)) {
            $errores[] = 'No hay suficientes cuotas futuras para configurar.';
        }

        return $errores;
    }

    public function submit()
    {
        $this->validate();

        // Validar configuración de fechas si está habilitada
        if ($this->configurarFechas) {
            $erroresFechas = $this->validarFechasConfiguracion();
            if (!empty($erroresFechas)) {
                foreach ($erroresFechas as $error) {
                    $this->addError('configuracion_fechas', $error);
                }
                return;
            }
        }

        try {
            $numeroRecibo = DetalleVenta::getSiguienteNumeroRecibo();
            DB::beginTransaction();

            $fechaMaximaPagar = Carbon::create($this->cuota->fecha_maxima_a_pagar)->format('Y-m');

            // Actualizar fecha de cuota actual si se configuró
            if ($this->configurarFechas && $this->nuevaFechaCuotaActual) {
                $this->cuota->fecha_maxima_a_pagar = $this->nuevaFechaCuotaActual;
            }

            if ($fechaMaximaPagar == getFechaActual() || $fechaMaximaPagar < getFechaActual() || $this->configurarFechas) {
                $this->cuota->total_intereses = $this->totalIntereses;
                $this->cuota->total_pago = $this->totalAbonar;
                $this->cuota->fecha_pago = date('Y-m-d');
                $this->cuota->pagado = 'si';
                $this->cuota->numero_recibo = $numeroRecibo === null ? 1500 : $numeroRecibo;
                $this->cuota->forma_pago = $this->formaPago;
                $this->cuota->concepto_de = $this->conceptoDe;
                $this->cuota->moneda_pago = $this->monedaPago;
                $this->cuota->leyenda = $this->leyenda;

                $this->cuota->save();

                // Configurar fechas de cuotas siguientes si está habilitado
                if ($this->configurarFechas && $this->nuevaFechaSiguientes && $this->cantidadCuotasConfigurar > 0) {
                    $this->configurarFechasCuotasSiguientes();
                }
            } elseif ($fechaMaximaPagar > getFechaActual()) {

                // Solo modificar fecha automáticamente si no se configuró manualmente
                if (!$this->configurarFechas || !$this->nuevaFechaCuotaActual) {
                    $diferenciaDeMeses = Carbon::create($this->cuota->fecha_maxima_a_pagar)->diffInMonths(getFechaActual());
                    $this->cuota->fecha_maxima_a_pagar = Carbon::create($this->cuota->fecha_maxima_a_pagar)->subMonth($diferenciaDeMeses)->format('Y-m') . '-15';
                }
                $this->cuota->total_intereses = $this->totalIntereses;
                $this->cuota->total_pago = $this->totalAbonar;
                $this->cuota->fecha_pago = date('Y-m-d');
                $this->cuota->pagado = 'si';
                $this->cuota->numero_recibo = $numeroRecibo === null ? 1500 : $numeroRecibo;
                $this->cuota->forma_pago = $this->formaPago;
                $this->cuota->concepto_de = $this->conceptoDe;
                $this->cuota->moneda_pago = $this->monedaPago;
                $this->cuota->leyenda = $this->leyenda;

                $this->cuota->save();

                // Si no se configuraron fechas manualmente, usar lógica automática
                if (!$this->configurarFechas) {
                    $cuotasPosterioresPagar = DetalleVenta::where('id_venta', $this->cuota->id_venta)->where('pagado', 'no')->orderByRaw("CAST(numero_cuota AS UNSIGNED) ASC")->get();

                    $cuotasPosterioresPagar->reduce(function ($fecha, $cuotasSinPagar) {
                        $proximaFecha = Carbon::create($fecha)->addMonth(1)->format('Y-m') . '-15';
                        $cuotasSinPagar->fecha_maxima_a_pagar = $proximaFecha;
                        $cuotasSinPagar->save();
                        return $proximaFecha;
                    }, $this->cuota->fecha_maxima_a_pagar);
                } else {
                    // Configurar fechas manualmente si está habilitado
                    if ($this->nuevaFechaSiguientes && $this->cantidadCuotasConfigurar > 0) {
                        $this->configurarFechasCuotasSiguientes();
                    }
                }
            }

            DB::commit();

            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('success', "Cuota guardada exitosamente <a href=" . route('clientes.volantePago', $this->cuota->id_detalle_venta) . " target='_blank'>Haga click aqui </a>para descargar el volante de pago.");
        } catch (\Throwable $e) {
            DB::rollback();

            return redirect()->route('clientes.estadoCuotas', $this->cuota->idParcela)->with('error', 'Error al pagar la cuota, contacte al Administrador!');
        }
    }

    private function configurarFechasCuotasSiguientes()
    {
        $fechaBase = Carbon::createFromFormat('Y-m-d', $this->nuevaFechaSiguientes);

        // Obtener las cuotas originales desde la base de datos para poder actualizarlas
        $cuotasOriginales = DetalleVenta::where('id_venta', $this->cuota->id_venta)
            ->where('pagado', 'no')
            ->where('numero_cuota', '>', $this->cuota->numero_cuota)
            ->where('fecha_maxima_a_pagar', '>', date('Y-m-d'))
            ->orderByRaw("CAST(numero_cuota AS UNSIGNED) ASC")
            ->take($this->cantidadCuotasConfigurar)
            ->get();

        foreach ($cuotasOriginales as $index => $cuota) {
            // Para la primera cuota, usar la fecha exacta configurada
            // Para las siguientes, agregar un mes por cada posición
            $nuevaFecha = $fechaBase->copy()->addMonths($index);

            $cuota->fecha_maxima_a_pagar = $nuevaFecha->format('Y-m-d');
            $cuota->save();
        }
    }

    public function render()
    {
        return view('livewire.form-cobrar-cuotas');
    }
}
