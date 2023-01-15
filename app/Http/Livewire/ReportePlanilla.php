<?php

namespace App\Http\Livewire;

use App\Models\DetalleVenta;
use App\Models\Lote;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportePlanilla extends Component
{
    public $anioSeleccionado = "";
    public $mesSeleccionado = "";
    public $isDisabled = true;

    public $anios = [];
    public $meses = ['1' => 'ENERO', '2' => "FEBRERO", '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'];

    public function rules()
    {
        return [
            'anioSeleccionado' => [
                'required',
                'numeric',
                'integer',
                Rule::in(array_keys($this->anios)),
            ],
            'mesSeleccionado' => 'required|numeric|integer|between:1,12',

        ];
    }

    public function mount()
    {
        $mesActual = collect($this->meses)->search(function ($value, $key) {
            return $key === now()->month;
        });

        $this->mesSeleccionado = $mesActual;

        $anioActual = collect($this->anios)->search(function ($value, $key) {
            return $key === now()->year;
        });

        $this->anioSeleccionado = $anioActual;

        $this->isDisabled = false;

    }

    public function updated($propertyName)
    {
        $this->isDisabled = true;
        $this->validateOnly($propertyName);
        $this->isDisabled = false;
    }

    public function boot()
    {
        $anioActual = (int) date('Y');
        $this->anios[$anioActual] = $anioActual;
        for ($i = 1; $i <= 4; $i++) {
            $anioCalculado = $anioActual - $i;
            $this->anios[$anioCalculado] = $anioCalculado;
        }

    }

    public function submit()
    {

        $totalCuotas = DetalleVenta::select(['id_lote', 'nombre', 'apellido', 'descripcion_parcela', 'numero_cuota', 'total_pago', 'pagado'])
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('personas', 'ventas.id_cliente', '=', 'personas.id_persona')
            ->join('parcelas', 'ventas.id_parcela', '=', 'parcelas.id_parcela')
            ->whereYear('fecha_pago', '=', $this->anioSeleccionado)
            ->whereMonth('fecha_pago', '=', $this->mesSeleccionado)
            ->get();

        if (count($totalCuotas) === 0) {
            return session()->flash('error', 'No se encontraron registros con los parametros seleccionados');
        }

        return $this->armarPlanilla($totalCuotas);

        // $planillaExcel->toBrowser();
        // return Excel::download(new PlanillaExport($this->anioSeleccionado, $this->mesSeleccionado), "Planilla " . $this->meses[$this->mesSeleccionado] . "-" . $this->anios[$this->anioSeleccionado] . ".xlsx");
    }

    private function armarPlanilla($totalCuotas)
    {

        $cuotasAgrupadas = [];

        foreach ($totalCuotas as $cuota) {
            $cuotasAgrupadas[$cuota->id_lote][] = $cuota;
        }

        $lotes = Lote::all();

        $planillaExcel = SimpleExcelWriter::streamDownload("planilla.xlsx");

        $planillaExcel->noHeaderRow();
        foreach ($lotes as $lote) {
            $planillaExcel->addRow(['Lote: ', $lote->nombre_lote]);

            $total = 0;

            $planillaExcel->addHeader(['Cliente', 'Parcela', 'Numero Cuota', 'Pago Mensual', 'Pagado', 'Porcentaje', 'Dueño', 'Diego']);

            foreach ($cuotasAgrupadas[$lote->id_lote] as $cuotas) {
                $descuento = $cuotas->total_pago * 0.20;

                $planillaExcel->addRow([
                    "$cuotas->nombre $cuotas->apellido", $cuotas->descripcion_parcela, $cuotas->numero_cuota,
                    $cuotas->total_pago,
                    $cuotas->pagado,
                    "20%",
                    ($cuotas->total_pago - $descuento),
                    $descuento,
                ]);

                $total += $cuotas->total_pago;

            }
            $planillaExcel->addRow(['Total: ', $total]);
            $planillaExcel->addRow(['', '']);
        }

// return $planillaExcel->toBrowser();
        return response()->streamDownload(function () use ($planillaExcel) {
            $planillaExcel->close();
        }, "Planilla " . $this->meses[$this->mesSeleccionado] . "-" . $this->anios[$this->anioSeleccionado] . ".xlsx");

    }

    public function render()
    {
        return view('livewire.reporte-planilla');
    }
}
