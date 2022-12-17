<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';
    protected $primaryKey = 'id_detalle_venta';
    public $timestamps = false;

    protected $fillable = [
        'numero_cuota',
        'fecha_maxima_a_pagar',
        'total_estimado_a_pagar',
        'total_intereses',
        'fecha_pago',
        'total_pago',
        'id_venta',
    ];
}