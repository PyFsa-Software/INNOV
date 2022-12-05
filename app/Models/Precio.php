<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    use HasFactory;

    protected $table = 'precios_cemento';
    protected $primaryKey = 'id_precio_cemento';
    public $timestamps = false;

    protected $fillable = [
        'precio_bercomat',
        'precio_sancayetano',
        'precio_rio_colorado',
        'precio_promedio',
    ];
}