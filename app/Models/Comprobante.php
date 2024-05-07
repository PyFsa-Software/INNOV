<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobantes';
    protected $primaryKey = 'id_comprobante';

    protected $fillable = [
        'descripcion_comprobante',
        'numero_recibo',
        'fecha_comprobante',
        'forma_pago',
        'importe_total',
        'concepto_de',
        'sr_sra',
        'dni',
        'domicilio',
        'domicilio_alquiler',
        'id_cliente',
        'id_venta',
    ];

    public function cliente()
    {
        return $this->belongsTo(Persona::class, 'id_cliente', 'id_persona');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }
}
