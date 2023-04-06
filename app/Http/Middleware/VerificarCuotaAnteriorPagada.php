<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\DetalleVenta;


class VerificarCuotaAnteriorPagada
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     
    public function handle(Request $request, Closure $next)
    {

        $idDetalleVenta = $request?->cuota?->id_detalle_venta;

        $idVenta =  $request?->cuota?->id_venta;

        $ultimaCuotaPagada = DetalleVenta::where('id_venta',$idVenta)->where('pagado','si')->orderByRaw("CAST(numero_cuota AS UNSIGNED) DESC")->first();

    

        $cuotaActual = DetalleVenta::where('id_detalle_venta',$idDetalleVenta)->first();

        
        $primeraCuota = DetalleVenta::where('id_detalle_venta',$idDetalleVenta)->where('numero_cuota','=','1')->first();
        
       

        if ($primeraCuota?->pagado == 'no') {
            return $next($request);
        }

        if ($ultimaCuotaPagada && $cuotaActual && $cuotaActual->numero_cuota == ($ultimaCuotaPagada->numero_cuota + 1)) {
            return $next($request);
        }
      


        return redirect()->route('clientes.estadoCuotas', $request?->cuota?->idParcela)->with('error', 'Debe pagar la cuota anterior!. Siga el orden de menor a mayor');
    }
}
