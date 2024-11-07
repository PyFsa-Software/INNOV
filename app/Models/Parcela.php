<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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

    public function getCantidadDeudasAttribute()
    {
        $idVenta = Venta::all()->where('id_parcela', '=', $this->id_parcela)->value('id_venta');

        $debeCuotas = DetalleVenta::where('id_venta', $idVenta)
            ->where('fecha_maxima_a_pagar', '<', date('Y-m-d'))
            ->where('pagado', '!=', 'si')
            ->count();

        return $debeCuotas;
    }

    public function getActualizarPrecioCuotaFechaLimiteAttribute()
    {
        //Devuelve la venta a la cual corresponde.
        $idVenta = Venta::select('id_venta','fecha_actualizacion_precio','cuotas')->where('id_parcela', '=', $this->id_parcela)->get();

        //Devuelve el Total de cuotas pagadas.
        $cuotasPagadas = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
            ->where('pagado','=','si')->count('id_detalle_venta');
        //Devuelve el total de cuotas no pagadas.
        $cuotasPorPagar = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
            ->where('pagado','=','no')->count('id_detalle_venta');

        
        //Devuelve el total de los precios que hay que actualizar.
        $preciosPorActualizar = DetalleVenta::whereHas('venta', function($query) {
            $query->where('pagado', '=', 'no')
                  ->whereColumn('id_parcela', '=', 'ventas.id_parcela')
                  ->where('fecha_actualizacion_precio', '<', getFechaActualizacion())->where('fecha_actualizacion','=',null);
        })->get();
        
        //Devuelve el total de los precios ya actualizados.   

        // $cantidadPreciosActualizados = DetalleVenta::whereHas('venta', function($query) {
        //     $query->where('fecha_actualizacion', '!=', '')
        //           ->whereColumn('id_parcela', '=', 'ventas.id_parcela');
                 
        // })->get();
        
        // dd(count($cantidadPreciosActualizados));
     
            // var_dump(count($preciosPorActualizar));
            // var_dump(count($cantidadPreciosActualizados));
        //Equivale al total de cuotas.
        $totalCuotas = $cuotasPagadas + $cuotasPorPagar;

        //Fecha de actualizacion de los precios de las cuotas.
        $fechaActualizacionPrecio = Carbon::parse($idVenta[0]?->fecha_actualizacion_precio)->format('Y-m');


            //Debe retornar TRUE para que aparezca el boton de "Actualizar Precios".
            return 
            (((getFechaActual() > $fechaActualizacionPrecio) && ($totalCuotas != $idVenta[0]->cuotas) && (count($preciosPorActualizar) == 0)));
        


    }

    public function getGenerarNuevasCuotasAttribute()
    {
            //Devuelve la venta a la cual corresponde.
            $idVenta = Venta::select('id_venta','fecha_actualizacion_precio','cuotas')->where('id_parcela', '=', $this->id_parcela)->get();

            //Devuelve el Total de cuotas pagadas.
            $cuotasPagadas = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','si')->count('id_detalle_venta');
            //Devuelve el total de cuotas no pagadas.
            $cuotasPorPagar = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','no')->count('id_detalle_venta');
            
            //Equivale al total de cuotas.
            $totalCuotas = $cuotasPagadas + $cuotasPorPagar;


            //Fecha de actualizacion de los precios de las cuotas.
            $fechaActualizacionPrecio = Carbon::parse($idVenta[0]->fecha_actualizacion_precio)->format('Y-m');

            //Debe retornar TRUE para que aparezca el boton de "Generar nuevas Cuotas".
            return 
            (($cuotasPagadas === $totalCuotas) && ($fechaActualizacionPrecio !== getFechaActual() && $totalCuotas != $idVenta[0]->cuotas));

    }


    public function getVerificarCancelacionPlanAttribute()
    {
            //Devuelve la venta a la cual corresponde.
            $idVenta = Venta::select('id_venta','fecha_actualizacion_precio','cuotas')->where('id_parcela', '=', $this->id_parcela)->get();

            //Devuelve el Total de cuotas pagadas.
            $cuotasPagadas = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','si')->count('id_detalle_venta');

            //Devuelve el total de cuotas no pagadas.
            $cuotasPorPagar = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','no')->count('id_detalle_venta');

            //Equivale al total de cuotas.
            $totalCuotas = $cuotasPagadas + $cuotasPorPagar;

            //Debe retornar TRUE para marcar como cancelado.
            return ($totalCuotas === $idVenta[0]->cuotas && $totalCuotas === $cuotasPagadas);
            
    }
        

    public function getVerificarCuotasEditarAttribute()
    {
            //Devuelve la venta a la cual corresponde.
            $idVenta = Venta::select('id_venta','fecha_actualizacion_precio','cuotas')->where('id_parcela', '=', $this->id_parcela)->get();

            //Cuotas que no estan pagadas
            $cuotasPorPagar = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
            ->where('pagado','=','no')->count('id_detalle_venta');

            //Total de cuotas que deben ser actualizadas.
            $preciosPorActualizar = DetalleVenta::whereHas('venta', function($query) {
                $query->where('pagado', '=', 'no')
                      ->whereColumn('id_parcela', '=', 'ventas.id_parcela')
                      ->where('fecha_actualizacion_precio', '<', getFechaActualizacion())->where('fecha_actualizacion','=',null);
            })->get();

            //Total de cuotas ya actualizadas.
            // $cantidadPreciosActualizados = DetalleVenta::whereHas('venta', function($query) {
            //     $query->where('fecha_actualizacion', '!=', '')
            //           ->whereColumn('id_parcela', '=', 'ventas.id_parcela')
            //           ->where('fecha_actualizacion', '>', getFechaActualizacion());
            // })->get();
            
           
                

            //Fecha de actualizacion de los precios de las cuotas.
            $fechaActualizacion = Carbon::create($idVenta[0]->fecha_actualizacion_precio)->format('Y-m');


            //Debe retornar TRUE para mostrar el mensaje de advertencia  
            return  ((getFechaActualEditarCuota() > $fechaActualizacion) && ($cuotasPorPagar > 0) && (count($preciosPorActualizar) != 0));
             

    }



    }


    
    