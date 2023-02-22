<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\DetalleVenta;
use Carbon\Carbon;

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
        $idVenta = Venta::select('id_venta','fecha_actualizacion_precio','cuotas')->where('id_parcela', '=', $this->id_parcela)->get();

        $cuotasPagadas = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
            ->where('pagado','=','si')->count('id_detalle_venta');

        $cuotasPorPagar = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
            ->where('pagado','=','no')->count('id_detalle_venta');

        $totalCuotas = $cuotasPagadas + $cuotasPorPagar;



   
        $fechaActualSistema = Carbon::now();

        $fechaActualizacionPrecio = Carbon::parse($idVenta[0]->fecha_actualizacion_precio);

        //Simula la fecha del sistema
        // $fechaPrueba = Carbon::create('2024/02/22');
        
   
        $mesAnioActualizacion = $fechaActualizacionPrecio->format('Y-m');
        $mesAnioActual  = $fechaActualSistema->format('Y-m');
            

        

            return 
            (($mesAnioActualizacion === $mesAnioActual && $totalCuotas != $idVenta[0]->cuotas));
        


        }

        public function getGenerarNuevasCuotasAttribute()
        {

            $idVenta = Venta::select('id_venta','fecha_actualizacion_precio','cuotas')->where('id_parcela', '=', $this->id_parcela)->get();

            $cuotasPagadas = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','si')->count('id_detalle_venta');
    
            $cuotasPorPagar = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','no')->count('id_detalle_venta');
    
            $totalCuotas = $cuotasPagadas + $cuotasPorPagar;


            $fechaActualSistema = Carbon::now();

            $fechaActualizacionPrecio = Carbon::parse($idVenta[0]->fecha_actualizacion_precio);
    
            //Simula la fecha del sistema
            // $fechaPrueba = Carbon::create('2024/02/22');
            
       
            $mesAnioActualizacion = $fechaActualizacionPrecio->format('Y-m');
            $mesAnioActual  = $fechaActualSistema->format('Y-m');


            return 
            (($cuotasPagadas === $totalCuotas && $cuotasPorPagar === 0 && $idVenta[0]->cuotas != $cuotasPagadas) && ($mesAnioActualizacion !== $mesAnioActual && $totalCuotas != $idVenta[0]->cuotas));

        }


        public function getVerificarCancelacionPlanAttribute()
        {
        
            $idVenta = Venta::select('id_venta','fecha_actualizacion_precio','cuotas')->where('id_parcela', '=', $this->id_parcela)->get();

            $cuotasPagadas = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','si')->count('id_detalle_venta');

            $cuotasPorPagar = DetalleVenta::where('id_venta', $idVenta[0]->id_venta)
                ->where('pagado','=','no')->count('id_detalle_venta');

            $totalCuotas = $cuotasPagadas + $cuotasPorPagar;

            // dd(($totalCuotas === $idVenta[0]->cuotas && $totalCuotas === $cuotasPagadas));

            // dd(($totalCuotas === $idVenta[0]->cuotas && $totalCuotas === $cuotasPagadas))
            return ($totalCuotas === $idVenta[0]->cuotas && $totalCuotas === $cuotasPagadas);
            
        }
        




    }