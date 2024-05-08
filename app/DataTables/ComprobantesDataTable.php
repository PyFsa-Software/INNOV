<?php

namespace App\DataTables;

use App\Models\Comprobante;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;
class ComprobantesDataTable extends DataTable
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
            ->addColumn('descripcion_comprobante', function ($data) {
                return $data->descripcion_comprobante;
            })
            ->addColumn('fecha_comprobante', function ($data) {
                return $data->fecha_comprobante;
            })
            ->addColumn('forma_pago', function ($data) {
                return $data->forma_pago;
            })
            ->addColumn('moneda_pago', function ($data) {
                return $data->moneda_pago;
            })
            ->addColumn('importe_total', function ($data) {
                return $data->importe_total;
            })
            ->addColumn('concepto_de', function ($data) {
                return $data->concepto_de;
            })
            ->addColumn('cliente', function ($data) {
                if (!isset($data->cliente->nombre)) {
                    return 'No especificado';
                }
                return $data?->cliente?->nombre . ' ' . $data?->cliente?->apellido;
            })
            ->addColumn('parcela', function ($data) {
                if (!isset($data->venta->parcela->descripcion_parcela)) {
                    return 'No especificado';
                }
                return $data?->venta?->parcela?->descripcion_parcela;
            })
            ->addColumn('manzana', function ($data) {
                // dd($data->venta->parcela);
                if (!isset($data->venta->parcela->manzana)) {
                    return 'No especificado';
                }
                return $data?->venta?->parcela?->manzana;
            })
            ->addColumn('lote', function ($data) {
                if (!isset($data->venta->parcela->lote->nombre_lote)) {
                    return 'No especificado';
                }
                return $data?->venta?->parcela?->lote?->nombre_lote;
            })
            // add colum for href comprobante
            ->addColumn('comprobante', function ($data) {
                return '<a href="' . route('comprobantes.pdf', $data->id_comprobante) . '" class="btn btn-info btn-sm" target="_blank">Comprobante</a>';
            })
            ->filterColumn('descripcion_comprobante', function ($query, $keyword) {
                $query->whereRaw("descripcion_comprobante like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('fecha_comprobante', function ($query, $keyword) {
                $query->whereRaw("fecha_comprobante like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('forma_pago', function ($query, $keyword) {
                $query->whereRaw("forma_pago like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('importe_total', function ($query, $keyword) {
                $query->whereRaw("importe_total like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('moneda_pago', function ($query, $keyword) {
                $query->whereRaw("moneda_pago like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('concepto_de', function ($query, $keyword) {
                $query->whereRaw("concepto_de like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('cliente', function ($query, $keyword) {
                // verify if coinciden to text No especificado get all records equals to null
                if ($keyword == 'no') {
                    return $query->whereNull('id_cliente');
                }
                $query->whereHas('cliente', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(nombre,' ',apellido) like ?", ["%{$keyword}%"]);
                });
            })
            ->filterColumn('parcela', function ($query, $keyword) {
                // verify if coinciden to text No especificado get all records equals to null
                if ($keyword == 'no') {
                    return $query->whereNull('id_venta');
                }
                $query->whereHas('venta.parcela', function ($q) use ($keyword) {
                    $q->whereRaw("descripcion_parcela like ?", ["%{$keyword}%"]);
                });
            })
            ->filterColumn('manzana', function ($query, $keyword) {
                $query->whereHas('venta.parcela.lote', function ($q) use ($keyword) {
                    $q->whereRaw("manzana like ?", ["%{$keyword}%"]);
                });
            })
            ->filterColumn('lote', function ($query, $keyword) {
                $query->whereHas('venta.parcela.lote', function ($q) use ($keyword) {
                    $q->whereRaw("nombre_lote like ?", ["%{$keyword}%"]);
                });
            })
            ->rawColumns(['descripcion_comprobante', 'fecha_comprobante', 'forma_pago', 'moneda_pago', 'importe_total', 'concepto_de', 'cliente','parcela', 'manzana', 'lote', 'comprobante'])
            ->setRowId('id_comprobante');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cliente $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Comprobante $model): QueryBuilder
    {
        return $model::with('cliente', 'venta.parcela.lote')->orderBy('id_comprobante', 'desc');
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tablaListadoComprobantes')
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
            'descripcion_comprobante' => ['title' => 'Descripcion'],
            'fecha_comprobante' => ['title' => 'Fecha'],
            'forma_pago' => ['title' => 'Forma Pago'],
            'moneda_pago' => ['title' => 'Moneda Pago'],
            'importe_total' => ['title' => 'Importe Total'],
            'concepto_de' => ['title' => 'Concepto De'],
            'cliente' => ['title' => 'Cliente'],
            'parcela' => ['title' => 'Parcela'],
            'manzana' => ['title' => 'Manzana'],
            'lote' => ['title' => 'Lote'],
            'comprobante' => ['title' => 'Comprobante'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Comprobantes_' . date('YmdHis');
    }
}
