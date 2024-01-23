<?php

namespace App\DataTables;

use App\Models\Lote;
use App\Models\ReservaParcela;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReservaParcelaDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('nombre_apellido_cliente', function ($data) {
            return $data?->cliente?->nombre . ' ' . $data?->cliente?->apellido;
        })
        ->addColumn('dni', function ($data) {
            return $data?->cliente?->dni;
        })
        ->addColumn('lote', function ($data) {
            return $data?->parcela?->lote?->nombre_lote;
        })
        ->addColumn('parcela', function ($data) {
            return $data?->parcela?->descripcion_parcela;
        })
        
        ->addColumn('Estado', function ($data) {
            $estado = ($data->estado_reserva == 1) ? 'CANCELADO' : 'PENDIENTE';
            return "<span class='badge badge-" . (($data->estado_reserva == 1) ? 'success' : 'danger') . "'>$estado</span>";
        })

        ->addColumn('fecha_reserva', function ($data) {
            $fecha = Carbon::parse($data?->fecha_reserva);
            return $fecha->format('d/m/Y');
        })
        ->addColumn('Pagos', function ($data) {
            return "<a href='" . route('reservaParcela.payments', $data->id_reserva_parcela) . "' class='btn btn-info btn-sm'><i class='ti-files'></i></a>";
        })
        ->filterColumn('nombre_apellido_cliente', function ($query, $keyword) {
            $query->whereHas('cliente', function ($q) use ($keyword) {
                $q->whereRaw("CONCAT(nombre,' ',apellido) like ?", ["%{$keyword}%"]);
            });
        })
        ->filterColumn('dni', function ($query, $keyword) {
            $query->whereHas('cliente', function ($q) use ($keyword) {
                $q->where('dni', 'like', "%{$keyword}%");
            });
        })
        ->rawColumns(['nombre_apellido_cliente','dni', 'lote','parcela', 'Estado','fecha_reserva', 'Pagos'])
        ->setRowId('id_persona');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ReservaParcela $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ReservaParcela $model): QueryBuilder
    {
        return $model::with('cliente', 'parcela.lote')->orderBy('fecha_reserva', 'desc');

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('reservaparcela-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            ['name' => 'nombre_apellido_cliente', 'title' => 'Cliente', 'data' => 'nombre_apellido_cliente'],
            ['name' => 'dni', 'title' => 'DNI', 'data' => 'dni', 'className' => 'text-center'],
            ['name' => 'lote', 'title' => 'Lote', 'data' => 'lote', 'className' => 'text-center'],
            ['name' => 'parcela', 'title' => 'Parcela', 'data' => 'parcela', 'className' => 'text-center'],
            ['name' => 'Estado', 'title' => 'Estado', 'data' => 'Estado', 'className' => 'text-center'],
            ['name' => 'fecha_reserva', 'title' => 'Fecha Pre-Venta', 'data' => 'fecha_reserva', 'className' => 'text-center'],
            ['name' => 'Pagos', 'title' => 'Pagos', 'data' => 'Pagos', 'className' => 'text-center'],
            // ['name' => 'volante_pago', 'title' => 'Volante de Pago', 'data' => 'volante_pago', 'className' => 'text-center'],
            // ['name' => 'eliminar', 'title' => 'Eliminar', 'data' => 'eliminar'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ReservaParcela_' . date('YmdHis');
    }
}