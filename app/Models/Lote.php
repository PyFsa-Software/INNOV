<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';
    public $timestamps = false;

    protected $fillable = [
        'nombre_lote',
        'hectareas_lote',
        'cantidad_manzanas',
        'ubicacion',
    ];
}