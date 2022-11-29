<?php

namespace App\DataTables;

use App\Models\Personas;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class ClientesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $queryWithWhere = $query->select(['id_persona', 'nombre', 'apellido', 'dni', 'correo', 'celular', 'cliente'])
            ->from('personas')
            ->where('cliente', '=', '1');

        return (new EloquentDataTable($queryWithWhere))
            ->addColumn('nombre_apellido', function ($data) {
                return $data->nombre . ' ' . $data->apellido;
            })
            ->addColumn('editar', function ($data) {
                $btn = '<a href="{}" class="btn btn-warning btn-sm">Editar</a>';
                return $btn;
            })
            ->addColumn('eliminar', function ($data) {
                $btn = '<a href="{}" class="btn btn-danger btn-sm">Eliminar</a>';
                return $btn;
            })

            ->rawColumns(['nombre_apellido', 'editar', 'eliminar'])
            ->setRowId('id_persona');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cliente $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Personas $model): QueryBuilder
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
            ->setTableId('tablaClientes')
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
            'nombre_apellido', 'dni', 'correo', 'celular', 'editar', 'eliminar',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Clientes_' . date('YmdHis');
    }
}