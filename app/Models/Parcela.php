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

}