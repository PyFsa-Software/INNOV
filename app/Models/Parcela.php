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
        'superficie_parcela',
        'manzana',
        'cantidad_bolsas',
        'ancho',
        'largo',
        'id_lote',
    ];
}