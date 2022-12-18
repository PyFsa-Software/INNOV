<?php

namespace App\DataTables;

use App\Models\Parcela;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class ParcelasDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $queryWithWhere = $query->select(['id_parcela', 'descripcion_parcela', 'superficie_parcela', 'manzana', 'cantidad_bolsas', 'ancho', 'largo', 'nombre_lote', 'disponible'])
            ->from('parcelas')
            ->join('lotes', 'parcelas.id_lote', '=', 'lotes.id_lote');

        return (new EloquentDataTable($queryWithWhere))
            ->addColumn('estado', function ($data) {

                if ($data->disponible === 1) {
                    $spamDisponible = "<span class='badge badge-success'>Disponible</span>";
                    return $spamDisponible;
                }

                $spamVendido = "<span class='badge badge-danger'>Vendido</span>";

                return $spamVendido;
            })
            ->addColumn('editar', function ($data) {

                // dd($data->disponible);
                if ($data->disponible === 0) {
                    return "<span class='badge badge-warning'>No disponible</span>";
                } else {
                    return "<a href='" . route('parcelas.editar', $data->id_parcela) . "' class='btn btn-warning btn-sm'>Editar</a>";
                }
            })
            ->addColumn('eliminar', function ($data) {

                if ($data->disponible === 0) {
                    return "<span class='badge badge-warning'>No disponible</span>";
                } else {
                    return "<a href='" . route('parcelas.eliminar', $data->id_parcela) . "' class='btn btn-danger btn-sm'>Eliminar</a>";
                }

            })
            ->rawColumns(['estado', 'editar', 'eliminar'])
            ->setRowId('id_lote');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cliente $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Parcela $model): QueryBuilder
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
            ->setTableId('tablaParcelas')
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
            ['name' => 'descripcion_parcela', 'title' => 'Descripción', 'data' => 'descripcion_parcela'],
            ['name' => 'superficie_parcela', 'title' => 'Superficie', 'data' => 'superficie_parcela'],
            ['name' => 'manzana', 'title' => 'Manzana', 'data' => 'manzana'],
            ['name' => 'cantidad_bolsas', 'title' => 'Bolsas de Cemento', 'data' => 'cantidad_bolsas'],
            ['name' => 'estado', 'title' => 'Estado', 'data' => 'estado'],
            ['name' => 'nombre_lote', 'title' => 'Lote', 'data' => 'nombre_lote'],
            ['name' => 'ancho', 'title' => 'Ancho', 'data' => 'ancho'],
            ['name' => 'largo', 'title' => 'Largo', 'data' => 'largo'],
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
        return 'Parcelas_' . date('YmdHis');
    }
}