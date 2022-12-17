<?php

namespace App\DataTables;

use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class VentasDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $queryWithWhere = $query->select(['p1.id_persona', 'p1.nombre', 'p1.apellido', 'p1.dni', 'p1.cliente', 'v1.fecha_venta', 'p2.superficie_parcela', 'p2.id_lote', 'v1.cuotas', 'v1.id_venta'])
            ->from('ventas', 'v1')
            ->join('personas as p1', 'p1.id_persona', '=', 'v1.id_cliente')
            ->join('parcelas as p2', 'p2.id_parcela', '=', 'v1.id_parcela')
            ->where('p1.cliente', '=', '1');
        // dd($queryWithWhere);

        return (new EloquentDataTable($queryWithWhere))
            ->addColumn('nombre_apellido_cliente', function ($data) {
                return $data->nombre . ' ' . $data->apellido;
            })
            ->addColumn('lote', function ($data) {
                $lote = Lote::find($data->id_lote);
                return $lote->nombre_lote;
            })
            ->addColumn('cantidad_cuotas_pagadas', function ($data) {
                $totalRegistrosDetalleVenta = DetalleVenta::where(
                    [
                        ['id_venta', '=', $data->id_venta],
                        ['pagado', '=', '1'],
                    ]
                );

                if (!$totalRegistrosDetalleVenta) {
                    return "0/" . $data->cuotas;
                }

                return $totalRegistrosDetalleVenta->count() . "/" . $data->cuotas;
            })
            ->addColumn('editar', function ($data) {
                $btn = "<a href='" . route('clientes.editar', $data->id_persona) . "' class='btn btn-warning btn-sm'>Editar</a>";
                return $btn;
            })
            ->rawColumns(['nombre_apellido_cliente', 'lote', 'cantidad_cuotas_pagadas', 'editar'])
            ->setRowId('id_persona');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cliente $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Venta $model): QueryBuilder
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
            ->setTableId('tablaVentas')
            ->columns($this->getColumns())
            ->minifiedAjax()
        //->dom('Bfrtip')
            ->orderBy(1)
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
            ['name' => 'nombre_apellido_cliente', 'title' => 'Cliente', 'data' => 'nombre_apellido_cliente'],
            ['name' => 'dni', 'title' => 'DNI', 'data' => 'dni'],
            ['name' => 'superficie_parcela', 'title' => 'Superficie Parcela', 'data' => 'superficie_parcela'],
            ['name' => 'lote', 'title' => 'Lote', 'data' => 'lote'],
            ['name' => 'cantidad_cuotas_pagadas', 'title' => 'Cantidad Cuotas Pagadas', 'data' => 'cantidad_cuotas_pagadas'],
            ['name' => 'fecha_venta', 'title' => 'Fecha Venta', 'data' => 'fecha_venta'],
            ['name' => 'editar', 'title' => 'Editar', 'data' => 'editar'],
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
        return 'Ventas_' . date('YmdHis');
    }
}