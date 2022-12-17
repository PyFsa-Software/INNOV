<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    public $timestamps = false;

    protected $fillable = [
        'cuotas',
        'precio_total_terreno',
        'cuota_mensual_bolsas_cemento',
        'precio_total_entrega',
        'precio_final',
        'id_parcela',
        'id_cliente',
    ];
}