<?php

namespace App\DataTables;

use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class CuotasVentasDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // $queryWithWhere = $query->select(['id_persona', 'nombre', 'apellido', 'dni', 'correo', 'celular', 'cliente', 'activo'])
        //     ->from('personas')
        //     ->where('cliente', '=', '1');

        return (new EloquentDataTable($query))
            ->filterColumn('pagado', function ($query, $keyword) {
                $query->whereRaw('pagado like ?', ["%{$keyword}%"]);
            })
            ->addColumn('pagado', function ($data) {
                if ($data->pagado == 'no') {
                    return "<span class='badge badge-danger'>NO</span>";
                }
                return "<span class='badge badge-success'>SI</span>";
            })
            ->addColumn('cobrar', function ($data) {
                if ($data->pagado === 'si') {
                    return "-";
                }
                return "<a href='" . route('clientes.cobrarCuota', $data->id_detalle_venta) . "' class='btn btn-warning btn-sm'><i class='ti-ticket'></i></a>";
            })
            ->editColumn('fecha_maxima_a_pagar', function ($data) {
                $estadoCuota = $data->fecha_maxima_a_pagar < date('Y-m-d') && $data->pagado === 'no' ? 'text-danger' : 'text-success';

                return "<b class='$estadoCuota'>" . Carbon::createFromFormat('Y-m-d', $data->fecha_maxima_a_pagar)->format('d/m/Y') . "</b>";
            })
            ->addColumn('volantePago', function ($data) {
                return "<a href='" . route('clientes.volantePago', $data->id_detalle_venta) . "' class='btn btn-info btn-sm' target='_blank'><i class='ti-download'></i></a>";
            })
            ->rawColumns(['pagado', 'cobrar', 'volantePago', 'fecha_maxima_a_pagar']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cliente $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {

        return DetalleVenta::where('id_venta', '=', $this->idVenta)->orderBy('fecha_maxima_a_pagar', 'desc');
        // return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tablaCuotasClientes')
            ->columns($this->getColumns())
            ->minifiedAjax()
        //->dom('Bfrtip')
        // ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'language' => ['url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json'],

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

            ['name' => 'numero_cuota', 'title' => 'Cuota', 'data' => 'numero_cuota', 'searchable' => false, 'orderable' => false, 'className' => 'text-center'],
            ['name' => 'fecha_maxima_a_pagar', 'title' => 'Fecha Maxima Pagar', 'data' => 'fecha_maxima_a_pagar', 'searchable' => false, 'orderable' => false, 'className' => 'text-center'],
            ['name' => 'total_estimado_a_pagar', 'title' => 'Estimado Pagar', 'data' => 'total_estimado_a_pagar', 'searchable' => false, 'orderable' => false, 'className' => 'text-center'],
            ['name' => 'pagado', 'title' => 'Pagado', 'data' => 'pagado', 'searchable' => true, 'orderable' => false, 'className' => 'text-center'],
            ['name' => 'cobrar', 'title' => 'Cobrar', 'data' => 'cobrar', 'searchable' => false, 'orderable' => false, 'className' => 'text-center'],
            ['name' => 'volantePago', 'title' => 'Volante Pago', 'data' => 'volantePago', 'searchable' => false, 'orderable' => false, 'className' => 'text-center', 'className' => 'text-center'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CuotasVentas_' . date('YmdHis');
    }
}