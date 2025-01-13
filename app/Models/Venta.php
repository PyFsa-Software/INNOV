<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'importe_entrega',
        'forma_pago',
        'id_parcela',
        'id_cliente',
        'concepto_de',
        'update_period'
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

    public function maxNumeroRecibo()
    {
        return $this->hasOne(DetalleVenta::class, 'id_venta')
            ->select('id_venta', DB::raw('MAX(CAST(numero_recibo AS UNSIGNED)) as max_numero_recibo'))
            ->groupBy('id_venta');
    }
}