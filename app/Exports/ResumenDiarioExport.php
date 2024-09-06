<?php

namespace App\Exports;

use App\Models\DetalleReservaParcela;
use App\Models\DetalleVenta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ResumenDiarioExport implements WithMultipleSheets
{
    protected $fechaDesde;
    protected $fechaHasta;

    public function __construct($fechaDesde, $fechaHasta)
    {
        $this->fechaDesde = $fechaDesde;
        $this->fechaHasta = $fechaHasta;
    }

    public function sheets(): array
    {
        return [
            new CuotasSheet($this->fechaDesde, $this->fechaHasta),
            new PreVentasSheet($this->fechaDesde, $this->fechaHasta),
        ];
    }
}

class CuotasSheet implements FromCollection, WithHeadings
{
    protected $fechaDesde;
    protected $fechaHasta;

    public function __construct($fechaDesde, $fechaHasta)
    {
        $this->fechaDesde = $fechaDesde;
        $this->fechaHasta = $fechaHasta;
    }

    public function collection()
    {
        $cuotas = DetalleVenta::with('venta.cliente', 'venta.parcela.lote')
            ->whereBetween('fecha_pago', [$this->fechaDesde, $this->fechaHasta])
            ->orderBy('fecha_pago', 'desc')
            ->get();

        return $cuotas->map(function ($cuota) {
            return [
                'Tipo' => 'Cuota',
                'Cliente' => $cuota->venta->cliente->nombre,
                'Fecha de Pago' => $cuota->fecha_pago,
                'Método de Pago' => $cuota->forma_pago,
                'Parcela' => $cuota->venta->parcela->descripcion_parcela,
                'Lote' => $cuota->venta->parcela->lote->nombre_lote,
                'Monto de Pago' => $cuota->total_pago,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Cliente',
            'Fecha de Pago',
            'Método de Pago',
            'Parcela',
            'Lote',
            'Monto de Pago',
        ];
    }
}

class PreVentasSheet implements FromCollection, WithHeadings
{
    protected $fechaDesde;
    protected $fechaHasta;

    public function __construct($fechaDesde, $fechaHasta)
    {
        $this->fechaDesde = $fechaDesde;
        $this->fechaHasta = $fechaHasta;
    }

    public function collection()
    {
        $fechaDesde = date('Y-m-d 00:00:00', strtotime($this->fechaDesde));
        $fechaHasta = date('Y-m-d 23:59:59', strtotime($this->fechaHasta));

        $preVentas = DetalleReservaParcela::with('reservaParcela.cliente')
            ->whereBetween('fecha_pago', [$fechaDesde, $fechaHasta])
            ->orderBy('fecha_pago', 'desc')
            ->get();

        return $preVentas->map(function ($preVenta) {
            return [
                'Tipo' => 'Pre-Venta',
                'Cliente' => $preVenta->reservaParcela->cliente->nombre . ' ' . $preVenta->reservaParcela->cliente->apellido,
                'Parcela' => $preVenta->reservaParcela->id_parcela, // Correcto: Parcela
                'Fecha de Pago' => $preVenta->fecha_pago, // Correcto: Fecha de Pago
                'Forma de Pago' => $preVenta->forma_pago, // Correcto: Forma de Pago
                'Importe de Pago' => $preVenta->importe_pago, // Correcto: Importe de Pago
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Cliente',
            'Parcela',
            'Fecha de Pago',
            'Forma de Pago',
            'Importe de Pago',
        ];
    }
}