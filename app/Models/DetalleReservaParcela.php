<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReservaParcela extends Model
{
    use HasFactory;

    protected $table = 'detalle_reserva_parcela';
    protected $primaryKey = 'id_detalle_reserva_parcela';
    protected $fillable = [
        'id_reserva_parcela',
        'fecha_pago',
        'forma_pago',
        'concepto_de',
        'importe_pago',
    ];

    public function reservaParcela()
    {
        return $this->belongsTo(ReservaParcela::class, 'id_reserva_parcela', 'id_reserva_parcela');
    }
}