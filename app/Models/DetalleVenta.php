<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';
    protected $primaryKey = 'id_detalle_venta';
    public $timestamps = false;

    protected $fillable = [
        'numero_cuota',
        'fecha_maxima_a_pagar',
        'total_estimado_a_pagar',
        'total_intereses',
        'fecha_pago',
        'total_pago',
        'id_venta',
        'numero_recibo',
        'fecha_actualizacion',
        'forma_pago',
        'concepto_de',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    public function getIdParcelaAttribute()
    {
        $idParcela = Venta::all()->where('id_venta', '=', $this->id_venta)->value('id_parcela');
        return $idParcela;
    }


    public function getActualizarCuotasAttribute()
    {
        //Devuelve la fecha de actualizacion de de los precios de cuota.
        $venta = Venta::where('id_venta',$this->id_venta)->value('fecha_actualizacion_precio');
    
        //Fecha de actualizacion de los precios de las cuotas.
        $fechaActualizacionVenta = Carbon::create($venta)->format('Y-m');

        //Fecha de modificacion del precio de cuota.
        // $fechaModificacionPrecioCuota = Carbon::create($this->fecha_actualizacion)->format('Y-m');

    
        return (getFechaActualEditarCuota() > $fechaActualizacionVenta && $this->pagado != 'si' && $this->fecha_actualizacion === null);

    }




    public function getVerificarCuotaPagadaAttribute()
    {

        return $this->pagado === 'si';

    }
}