<?php

namespace App\Http\Livewire;

use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Models\Precio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormActualizarPrecios extends Component
{

    public $venta;
    public $parcela;
    public $ultimaCuota;
    public $promedioCementoNuevo;
    public $listaPromedioCemento;
    public $promedio6Meses;
    public $totalAbonarProximosMeses;
    public $isDisabled = true;

    protected $rules = [
        'totalAbonarProximosMeses' => 'required|numeric|min:1',
    ];

    public function updated($propertyName)
    {
        $this->isDisabled = true;
        $this->validateOnly($propertyName);
        $this->isDisabled = false;
    }
    public function mount()
    {
        $this->listaPromedioCemento = Precio::orderBy("fecha", "DESC")
            ->take(6)
            ->get()
            ->each(function ($promedioFila) {
                $this->promedio6Meses += $promedioFila->precio_promedio;
            });
        $this->promedio6Meses = $this->promedio6Meses / 6;
    }

    public function calcularActualizacion()
    {
        $this->validate();
        $this->totalAbonarProximosMeses =  ($this->venta->cuota_mensual_bolsas_cemento) *  ($this->promedioCementoNuevo);
    }

    public function submit()
    {

        $this->validate();

        try {
            DB::beginTransaction();

            $periodoActualizacionVenta = $this->venta->update_period;

            switch ($periodoActualizacionVenta) {
                case 'BIMESTRAL':
                    $periodoActualizacion =  2;
                case 'TRIMESTRAL':
                    $periodoActualizacion = 3;
                case 'SEMESTRAL':
                    $periodoActualizacion =  6;
                default:
                    $periodoActualizacion =  6;
            }

            $this->venta->fecha_actualizacion_precio = Carbon::create($this->venta->fecha_actualizacion_precio)->addMonth($periodoActualizacion)->format('Y-m') . '-01';

            $this->venta->save();

            $numeroCuota = $this->ultimaCuota->numero_cuota;

            //Validacion de Plan de cuota Personalizado

            $totalCuotas = DetalleVenta::where('id_venta', '=', $this->venta->id_venta)->count('id_detalle_venta');

            $planCuota =  $this->venta->cuotas;

            $restoCuotas = $planCuota - $totalCuotas;

            // dd($restoCuotas, $numeroCuota);
            if ($restoCuotas < 6) {

                for ($i = 1; $i <= $restoCuotas; $i++) {
                    $numeroCuota++;
                    DetalleVenta::create([
                        'numero_cuota' => $numeroCuota,
                        'fecha_maxima_a_pagar' => Carbon::create($this->ultimaCuota->fecha_maxima_a_pagar)->addMonth($i)->format('Y-m') . '-15',
                        'total_estimado_a_pagar' => $this->totalAbonarProximosMeses,
                        'id_venta' => $this->venta->id_venta,
                    ]);
                }
            } else {

                for ($i = 1; $i <= 6; $i++) {
                    $numeroCuota++;
                    DetalleVenta::create([
                        'numero_cuota' => $numeroCuota,
                        'fecha_maxima_a_pagar' => Carbon::create($this->ultimaCuota->fecha_maxima_a_pagar)->addMonth($i)->format('Y-m') . '-15',
                        'total_estimado_a_pagar' => $this->totalAbonarProximosMeses,
                        'id_venta' => $this->venta->id_venta,
                    ]);
                }
            }


            DB::commit();
            return redirect()->route('clientes.estado', $this->venta->id_cliente)->with(
                'success',
                "Actualización de cuotas para los proximos 6 meses guardado correctamente."
            );
        } catch (\Throwable $e) {

            DB::rollback();

            // dd($e->getMessage());
            return redirect()->route('clientes.estado', $this->venta->id_cliente)->with('error', 'Error al realizar la actualización de cuotas para los proximos 6 meses, contacte con al administrador.');
        }
    }

    public function render()
    {
        return view('livewire.form-actualizar-precios');
    }
}