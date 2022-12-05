<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moroso extends Model
{
    use HasFactory;

    protected $table = 'morosos';
    protected $primaryKey = 'id_moroso';
    public $timestamps = false;

    protected $fillable = [
        'fecha_fin',
        'total_intereses',
        'activo',
        'id_cliente',
    ];
}