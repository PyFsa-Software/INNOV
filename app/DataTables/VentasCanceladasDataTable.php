<?php

namespace App\DataTables;

use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class VentasCanceladasDataTable extends DataTable
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
                return $data->cliente->nombre . ' ' . $data->cliente->apellido;
            })

            ->addColumn('parcela', function ($data) {
                return $data->parcela->descripcion_parcela;
            })

            ->addColumn('total_cuotas', function ($data) {
                return $data->cuotas;
            })

            ->addColumn('lote', function ($data) {
                $lote = Lote::find($data->parcela->id_lote);
                return $lote->nombre_lote;
            })

            // add colum for href volante
            ->addColumn('volante', function ($data) {
                return '<a href="' . route('ventasCanceladas.imprimirVolanteCancelacion', $data->maxNumeroRecibo->max_numero_recibo) . '" class="btn btn-warning btn-sm" target="_blank">Volante</a>';
            })

            ->rawColumns(['nombre_apellido_cliente', 'total_cuotas', 'parcela', 'lote', 'volante'])
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
        return $model::whereHas('detalleVenta', function ($query) {
            $query->selectRaw('id_venta, COUNT(*) as total_cuotas')
                ->where('pagado', '=', 'si')
                ->groupBy('id_venta')
                ->havingRaw('total_cuotas = ventas.cuotas');
        })
            ->with('cliente', 'parcela', 'maxNumeroRecibo');
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tablaVentasCanceladas')
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
            'nombre_apellido_cliente' => ['title' => 'Cliente'],
            'total_cuotas' => ['title' => 'Total Cuotas'],
            'parcela' => ['title' => 'Parcela'],
            'lote' => ['title' => 'Lote'],
            'volante' => ['title' => 'Volante'],
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