<?php

namespace App\Http\Middleware;

use App\Models\DetalleVenta;
use App\Models\Venta;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VerificarActualizacionCuotas
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

        try {

            $venta = Venta::all()->where('id_parcela', '=', $request?->parcela?->id_parcela)->first();

            $ultimaCuota = DetalleVenta::where('id_venta', $venta->id_venta)
                ->orderBy('fecha_maxima_a_pagar', 'desc')->first();

                // dd($ultimaCuota);

            $resultMismoMesCuota = Carbon::create($ultimaCuota->fecha_maxima_a_pagar)->isSameMonth();

        

            $noHayCuotas = DetalleVenta::where('id_venta', $venta)
            ->where('pagado','=','no')->count('id_detalle_venta');

            if ($noHayCuotas === 0 || $resultMismoMesCuota) {
                $request->merge(['venta' => $venta, 'ultimaCuota' => $ultimaCuota]);

                return $next($request);
            }

            return redirect()->route('clientes.index')->with('error', 'Aún no se puede aplica la actualización de las cuotas al registro seleccionado');

        } catch (\Throwable$e) {
            dd($e->getMessage());
            return redirect()->route('clientes.index')->with('error', 'Aún no se puede aplica la actualización de las cuotas al registro seleccionado');
        }

    }
}