<?php

namespace App\DataTables;

use App\Models\Precio;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class PreciosDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $queryWithWhere = $query->select(['id_precio_cemento', 'precio_bercomat', 'precio_sancayetano', 'precio_rio_colorado', 'precio_promedio', 'fecha'])
            ->from('precios_cemento');

        return (new EloquentDataTable($queryWithWhere))
            ->addColumn('editar', function ($data) {
                $btn = "<a href='" . route('precios.editar', $data->id_precio_cemento) . "' class='btn btn-warning btn-sm'>Editar</a>";
                return $btn;
            })
            ->addColumn('eliminar', function ($data) {
                return "<a href='" . route('precios.eliminar', $data->id_precio_cemento) . "' class='btn btn-danger btn-sm'>Eliminar</a>";
            })
            ->rawColumns(['editar', 'eliminar'])
            ->setRowId('id_precio_cemento');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cliente $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Precio $model): QueryBuilder
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
            ->setTableId('tablaPrecios')
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
            ['name' => 'precio_bercomat', 'title' => 'Bercomat', 'data' => 'precio_bercomat'],
            ['name' => 'precio_sancayetano', 'title' => 'San Cayetano', 'data' => 'precio_sancayetano'],
            ['name' => 'precio_rio_colorado', 'title' => 'Rio Colorado', 'data' => 'precio_rio_colorado'],
            ['name' => 'precio_promedio', 'title' => 'Promedio', 'data' => 'precio_promedio'],
            ['name' => 'fecha', 'title' => 'Fecha', 'data' => 'fecha'],
            ['name' => 'editar', 'title' => 'Editar', 'data' => 'editar'],
            ['name' => 'eliminar', 'title' => 'Eliminar', 'data' => 'eliminar'],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Precios_' . date('YmdHis');
    }
}