<?php

namespace App\DataTables;

use App\Models\DetalleVenta;
use App\Models\PagosMultiple;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PagosMultiplesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // dd($query->get());
        return (new EloquentDataTable($query))
            ->addColumn('FechaPago', function ($data) {

                return $data->fecha_pago;
            })
            ->addColumn('Cuotas', function ($data) {

                return $data->min_numero_cuota . " al " . $data->max_numero_cuota;
            })
            ->addColumn('Cancelado', function ($data) {

                $cuotaMax = (int)$data->max_numero_cuota;

                if ($data->venta->cuotas == $cuotaMax && $data->pagado == 'si') {
                    return "<span class='badge badge-success'>CANCELADO</span>";
                }
                return "<span class='badge badge-danger'>NO</span>";
            })
            ->addColumn('pagado', function ($data) {
                if ($data->pagado == 'no') {
                    return "<span class='badge badge-danger'>NO</span>";
                }
                return "<span class='badge badge-success'>SI</span>";
            })
            ->addColumn('totalPago', function ($data) {
                return "$ " . number_format($data->total_pago_agrupado, 2, ',', '.');
            })
            ->addColumn('volantePago', function ($data) {
                $cuotaMax = (int)$data->max_numero_cuota;

                if ($data->venta->cuotas == $cuotaMax && $data->pagado == 'si') {
                    return $data->verificarCuotaPagada ? "<a href='" . route('ventasCanceladas.imprimirVolanteCancelacion', $data->numero_recibo) . "' class='btn btn-info btn-sm' target='_blank'><i class='ti-download'></i></a>" : "-";
                }

                return $data->verificarCuotaPagada ? "<a href='" . route('clientes.volantePagoMultiple', $data->numero_recibo) . "' class='btn btn-info btn-sm' target='_blank'><i class='ti-download'></i></a>" : "-";
            })
            ->rawColumns(['FechaPago','Cuotas','Cancelado','totalPago', 'pagado', 'volantePago']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PagosMultiple $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): QueryBuilder
    {
        $subquery = DetalleVenta::with('venta')->select(
            'numero_recibo',
            DB::raw('SUM(total_pago) as total_pago_agrupado'),
            DB::raw('MIN(CAST(numero_cuota AS UNSIGNED)) as min_numero_cuota'),
            DB::raw('MAX(CAST(numero_cuota AS UNSIGNED)) as max_numero_cuota')
        )
        ->where('id_venta', '=', $this->idVenta)
        ->groupBy('numero_recibo')
        ->havingRaw('COUNT(numero_recibo) > 1')
        ->groupBy('numero_recibo');

        
    
    return DetalleVenta::joinSub($subquery, 'sub', function ($join) {
        $join->on('detalle_ventas.numero_recibo', '=', 'sub.numero_recibo');
    })
        ->select('detalle_ventas.fecha_pago', 'detalle_ventas.pagado', 'sub.total_pago_agrupado', 'detalle_ventas.numero_recibo', 'detalle_ventas.id_venta', 'sub.min_numero_cuota', 'sub.max_numero_cuota')
        ->orderByRaw("CAST(numero_cuota AS UNSIGNED) DESC")
        ->groupBy('detalle_ventas.numero_recibo', 'detalle_ventas.fecha_pago', 'detalle_ventas.pagado', 'sub.total_pago_agrupado', 'detalle_ventas.id_venta', 'sub.min_numero_cuota', 'sub.max_numero_cuota');    

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
            ->setTableId('pagosmultiples-table')
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

            ['name' => 'FechaPago', 'title' => 'Fecha de Pago', 'data' => 'FechaPago', 'searchable' => false, 'orderable' => false, 'className' => 'text-center', 'className' => 'text-center'],
            
            ['name' => 'Cuotas', 'title' => 'Cuotas', 'data' => 'Cuotas', 'searchable' => false, 'orderable' => false, 'className' => 'text-center', 'className' => 'text-center'],

            ['name' => 'Cancelado', 'title' => 'Cancelado', 'data' => 'Cancelado', 'searchable' => false, 'orderable' => false, 'className' => 'text-center', 'className' => 'text-center'],

            ['name' => 'pagado', 'title' => 'Pagado', 'data' => 'pagado', 'searchable' => false, 'orderable' => false, 'className' => 'text-center', 'className' => 'text-center'],

            ['name' => 'totalPago', 'title' => 'Total Pagado', 'data' => 'totalPago', 'searchable' => false, 'orderable' => false, 'className' => 'text-center', 'className' => 'text-center'],

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
        return 'PagosMultiples_' . date('YmdHis');
    }
}