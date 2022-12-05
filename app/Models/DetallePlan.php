<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePlan extends Model
{
    use HasFactory;

    protected $table = 'detalle_planes';
    protected $primaryKey = 'id_detalle_plan';
    public $timestamps = false;

    protected $fillable = [
        'fecha_desde',
        'fecha_hasta',
        'valor_cuota',
        'id_venta',
    ];
}