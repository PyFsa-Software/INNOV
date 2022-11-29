<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personas extends Model
{
    use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'cuit',
        'domicilio',
        'celular',
        'correo',
        'cliente',
    ];
}