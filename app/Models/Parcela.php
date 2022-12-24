<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $table = 'parcelas';
    protected $primaryKey = 'id_parcela';
    public $timestamps = false;

    protected $fillable = [
        'descripcion_parcela',
        'superficie_parcela',
        'manzana',
        'cantidad_bolsas',
        'ancho',
        'largo',
        'id_lote',
        'disponible',
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote');
    }

    public function getCantidadDeudasAttribute()
    {
        $idVenta = Venta::all()->where('id_parcela', '=', $this->id_parcela)->value('id_venta');

        $debeCuotas = DetalleVenta::where('id_venta', $idVenta)
            ->where('fecha_maxima_a_pagar', '<', date('Y-m-d'))
            ->where('pagado', '!=', 'si')
            ->count();

        return $debeCuotas;
    }

}