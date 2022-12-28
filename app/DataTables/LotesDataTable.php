<?php

namespace App\DataTables;

use App\Models\Lote;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class LotesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $queryWithWhere = $query->select(['id_lote', 'nombre_lote', 'hectareas_lote', 'cantidad_manzanas', 'ubicacion'])
            ->from('lotes');

        return (new EloquentDataTable($queryWithWhere))
            ->addColumn('editar', function ($data) {
                $btn = "<a href='" . route('lotes.editar', $data->id_lote) . "' class='btn btn-warning btn-sm'>Editar</a>";
                return $btn;
            })
            ->addColumn('eliminar', function ($data) {
                return "<a href='" . route('lotes.eliminar', $data->id_lote) . "' class='btn btn-danger btn-sm'>Eliminar</a>";
            })
            ->rawColumns(['editar', 'eliminar'])
            ->setRowId('id_lote');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cliente $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Lote $model): QueryBuilder
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
            ->setTableId('tablaLotes')
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
            ['name' => 'nombre_lote', 'title' => 'Lote', 'data' => 'nombre_lote'],
            ['name' => 'hectareas_lote', 'title' => 'Hectareas del Lote', 'data' => 'hectareas_lote'],
            ['name' => 'cantidad_manzanas', 'title' => 'Cantidad de Manzanas', 'data' => 'cantidad_manzanas'],
            ['name' => 'ubicacion', 'title' => 'Ubicacion', 'data' => 'ubicacion'],
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
        return 'Lotes_' . date('YmdHis');
    }
}