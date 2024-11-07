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

        $cuotaAnterior = DetalleVenta::where('id_detalle_venta',$request?->cuota?->id_detalle_venta - 1)->where('pagado','=','si')->first();

        if ($cuotaAnterior != null) {
            return $next($request);
        }
      


        return redirect()->route('clientes.estadoCuotas', $request?->cuota?->idParcela)->with('error', 'Debe pagar la cuota anterior!. Siga el orden de menor a mayor');
    }
}
