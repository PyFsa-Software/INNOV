<?php

namespace App\DataTables;

use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Venta;
use Carbon\Carbon;
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
            ->addColumn('fecha_venta', function ($data) {
                $fecha = Carbon::parse($data?->fecha_venta);
                return $fecha->format('d/m/Y');
            })
            ->addColumn('volante_pago', function ($data) {
                return "<a href='" . route('ventas.volantePago', $data->id_venta) . "' class='btn btn-info btn-sm' target='_blank'><i class='ti-download'></i></a>";
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
            ->rawColumns(['nombre_apellido_cliente','dni', 'lote','parcela','fecha_venta', 'volante_pago'])
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
        return $model::with('cliente', 'parcela.lote');
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
            ['name' => 'dni', 'title' => 'DNI', 'data' => 'dni', 'className' => 'text-center'],
            ['name' => 'lote', 'title' => 'Lote', 'data' => 'lote', 'className' => 'text-center'],
            ['name' => 'parcela', 'title' => 'Parcela', 'data' => 'parcela', 'className' => 'text-center'],
            ['name' => 'fecha_venta', 'title' => 'Fecha Venta', 'data' => 'fecha_venta', 'className' => 'text-center'],
            ['name' => 'volante_pago', 'title' => 'Volante de Pago', 'data' => 'volante_pago', 'className' => 'text-center'],
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