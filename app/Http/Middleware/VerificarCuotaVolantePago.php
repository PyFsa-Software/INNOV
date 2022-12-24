<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarCuotaVolantePago
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

        if ($request?->cuota?->pagado === 'si') {
            return $next($request);
        }

        return redirect()->route('clientes.estadoCuotas', $request?->cuota?->idParcela)->with('error', 'No se puede realizar el volante de pago a una cuota que aún no ha sido pagada!');

    }
}