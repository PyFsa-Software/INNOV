<?php

namespace App\DataTables;

use App\Models\DetalleReservaParcela;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DetalleReservaParcelaDataTable extends DataTable
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
            ->addColumn('fecha_pago', function ($data) {
                return Carbon::parse($data->fecha_pago)->format('d/m/Y');
            })
            ->addColumn('importe_pago', function ($data) {
                return '$ ' . number_format($data->importe_pago, 2, ',', '.');
            })
            ->addColumn('forma_pago', function ($data) {
                return $data->forma_pago;
            })
            ->addColumn('concepto_de', function ($data) {
                return $data->concepto_de;
            })
            ->addColumn('volante_pago', function ($data) {
                return "<a href='" . route('reservaParcela.volantePago', $data->id_detalle_reserva_parcela) . "' class='btn btn-info btn-sm' target='_blank'><i class='ti-download'></i></a>";
            })
            ->rawColumns(['fecha_pago', 'importe_pago', 'forma_pago', 'concepto_de', 'volante_pago'])
            ->setRowId('id_detalle_reserva_parcela');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DetalleReservaParcela $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DetalleReservaParcela $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('detallereservaparcela-table')
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
            ['name' => 'fecha_pago', 'title' => 'Fecha de pago', 'data' => 'fecha_pago', 'className' => 'text-center', 'className' => 'text-center'],
            ['name' => 'importe_pago', 'title' => 'Importe de pago', 'data' => 'importe_pago', 'className' => 'text-center', 'className' => 'text-center'],
            ['name' => 'forma_pago', 'title' => 'Forma de pago', 'data' => 'forma_pago', 'className' => 'text-center', 'className' => 'text-center'],
            ['name' => 'concepto_de', 'title' => 'Concepto de', 'data' => 'concepto_de', 'className' => 'text-center', 'className' => 'text-center'],
            ['name' => 'volante_pago', 'title' => 'Volante de pago', 'data' => 'volante_pago', 'className' => 'text-center', 'className' => 'text-center'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'DetalleReservaParcela_' . date('YmdHis');
    }
}