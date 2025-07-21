<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaParcela extends Model
{
    use HasFactory;

    protected $table = 'reserva_parcela';
    protected $primaryKey = 'id_reserva_parcela';
    protected $fillable = [
        'id_cliente',
        'id_parcela',
        'fecha_reserva',
        'monto_total',
        'estado_reserva',
    ];

    public function cliente()
    {
        return $this->belongsTo(Persona::class, 'id_cliente', 'id_persona');
    }

    public function parcela()
    {
        return $this->belongsTo(Parcela::class, 'id_parcela', 'id_parcela');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleReservaParcela::class, 'id_reserva_parcela', 'id_reserva_parcela');
    }
}
