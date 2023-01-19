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

        $totalCuotas = DetalleVenta::select(['id_lote', 'nombre', 'apellido', 'descripcion_parcela', 'numero_cuota', 'total_pago', 'pagado', 'manzana', 'precio_total_terreno', 'cuotas'])
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('personas', 'ventas.id_cliente', '=', 'personas.id_persona')
            ->join('parcelas', 'ventas.id_parcela', '=', 'parcelas.id_parcela')
            ->whereYear('fecha_pago', '=', $this->anioSeleccionado)
            ->whereMonth('fecha_pago', '=', $this->mesSeleccionado)
            ->orderByRaw("manzana ASC")
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
        $fecha = getMesEnLetraConAnio("01-$this->mesSeleccionado-$this->anioSeleccionado");

        $planillaExcel->addRow(["PAGO DE CUOTAS: $fecha"]);
        $planillaExcel->addRow(['', '']);

        foreach ($lotes as $lote) {

            $totalDuenio = 0;
            $totalDiego = 0;

            $planillaExcel->addRow(['Lote: ', $lote->nombre_lote]);
            $planillaExcel->addRow(['', '']);

            // ORDENAR POR MANZANA
            $ordenadoPorManzana = collect($cuotasAgrupadas[$lote->id_lote])->reduce(function ($array, $item) {
                $array[$item->manzana][] = $item;
                return $array;
            }, []);

            // ORDENAR POR DESCRIPCION DE PARCELA DE FORMA ASC
            $ordenadoPorDescripcion = collect($ordenadoPorManzana)->map(function ($cuotas) {

                $cuotasOrdenadas = collect($cuotas)->sortBy([
                    fn($a, $b) => (int) $a['descripcion_parcela'] <=> (int) $b['descripcion_parcela'],
                ]);

                return $cuotasOrdenadas;
            });

            collect($ordenadoPorDescripcion)->each(function ($item) use ($planillaExcel, &$totalDuenio, &$totalDiego) {

                $total = 0;

                $planillaExcel->addHeader(['Parcela', 'Cliente', 'Manzana', 'Precio', 'Cuotas', 'Cuota', 'Monto', 'Pagado', 'Porcentaje', 'Dueño', 'Innov']);

                collect($item)->each(function ($cuota) use ($planillaExcel, &$total, &$totalDuenio, &$totalDiego) {

                    $descuento = $cuota->total_pago * 0.20;

                    $planillaExcel->addRow([
                        $cuota->descripcion_parcela,
                        "$cuota->apellido $cuota->nombre",
                        $cuota->manzana,
                        $cuota->precio_total_terreno,
                        $cuota->cuotas,
                        $cuota->numero_cuota,
                        $cuota->total_pago,
                        $cuota->pagado,
                        "20%",
                        ($cuota->total_pago - $descuento),
                        $descuento,
                    ]);

                    $total += $cuota->total_pago;
                    $totalDuenio += $cuota->total_pago - $descuento;
                    $totalDiego += $descuento;

                });

                $planillaExcel->addRow([
                    'Total x Mes: ',
                    $total,
                ]);
                $planillaExcel->addRow(['', '']);

            });

            $planillaExcel->addRow([
                'Total Dueño',
                $totalDuenio,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ]);
            $planillaExcel->addRow([
                'Total Innov',
                $totalDiego,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ]);

            $planillaExcel->addRow(['', '']);
            $planillaExcel->addRow(['', '']);

        }

        return response()->streamDownload(function () use ($planillaExcel) {
            $planillaExcel->close();
        }, "Planilla " . $this->meses[$this->mesSeleccionado] . "-" . $this->anios[$this->anioSeleccionado] . ".xlsx");

    }

    public function render()
    {
        return view('livewire.reporte-planilla');
    }
}
