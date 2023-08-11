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
        'fecha_actualizacion_precio',
        'precio_final',
        'id_parcela',
        'id_cliente',
    ];

    public function cliente()
    {
        return $this->belongsTo(Persona::class, 'id_cliente', 'id_persona');
    }

    public function parcela()
    {
        return $this->belongsTo(Parcela::class, 'id_parcela', 'id_parcela');
    }

    public function detalleVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }
}
