<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        'pagado',
        'total_pago',
        'id_venta',
        'numero_recibo',
        'fecha_actualizacion',
        'forma_pago',
        'concepto_de',
        'moneda_pago',
        'leyenda'
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
        $venta = Venta::where('id_venta', $this->id_venta)->value('fecha_actualizacion_precio');

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


    /**
     * Cuotas de un recibo, opcionalmente acotadas a una venta.
     */
    public function scopeDelRecibo($query, $numeroRecibo, $idVenta = null)
    {
        return $query->where('numero_recibo', $numeroRecibo)
            ->when($idVenta, fn ($q) => $q->where('id_venta', $idVenta));
    }

    /**
     * Devuelve el siguiente numero de recibo (compartido entre cuotas y comprobantes).
     *
     * Toma un lock sobre la fila de la tabla numeracion_recibos, por lo que dos cobros
     * simultaneos nunca obtienen el mismo numero. Debe llamarse dentro de la misma
     * transaccion que guarda el recibo: el lock se libera recien al commit/rollback.
     * Si el contador quedo por detras de los datos, se toma el maximo real.
     */
    public static function getSiguienteNumeroRecibo()
    {
        return DB::transaction(function () {
            $contador = DB::table('numeracion_recibos')->lockForUpdate()->first();

            if (!$contador) {
                DB::table('numeracion_recibos')->insert(['id' => 1, 'ultimo' => 0]);
                $contador = DB::table('numeracion_recibos')->lockForUpdate()->first();
            }

            $ultimoNumeroRecibo = (int) DetalleVenta::whereNotNull('numero_recibo')
                ->orderByRaw('CAST(numero_recibo AS UNSIGNED) DESC')
                ->value('numero_recibo');

            $ultimoNumeroReciboComprobante = (int) Comprobante::whereNotNull('numero_recibo')
                ->orderByRaw('CAST(numero_recibo AS UNSIGNED) DESC')
                ->value('numero_recibo');

            $maximo = max((int) $contador->ultimo, $ultimoNumeroRecibo, $ultimoNumeroReciboComprobante);
            $siguiente = $maximo > 0 ? $maximo + 1 : 1500;

            DB::table('numeracion_recibos')->where('id', $contador->id)->update(['ultimo' => $siguiente]);

            return $siguiente;
        });
    }
}
