<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function getFechaFormateadoAttribute()
    {
        $fecha = Carbon::parse($this->fecha);
        return Str::ucfirst($fecha->monthName) . " " . $fecha->year;
    }
}