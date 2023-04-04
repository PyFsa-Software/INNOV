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

            // dd($venta->fecha_inicio_pago);

            $fechaInicioPago = Carbon::create($venta->fecha_inicio_pago);
            

            $ultimaCuota = DetalleVenta::where('id_venta', $venta->id_venta)
                ->orderByRaw("CAST(numero_cuota AS UNSIGNED) DESC")
                ->first();

                // dd($ultimaCuota->fecha_maxima_a_pagar);

            $fechaActualSistema = Carbon::now();

            $fechaActualizacionPrecio = Carbon::parse($venta->fecha_actualizacion_precio);

            $mesAnioActualizacion = $fechaActualizacionPrecio->format('Y-m');
            $mesAnioActual  = $fechaActualSistema->format('Y-m');

            // $limiteActualizarCuota = $fechaInicioPago->diffInMonths($fechaActualSistema);           
            

            // $resultMismoMesCuota = Carbon::create($ultimaCuota->fecha_maxima_a_pagar)->isSameMonth();

        

            // $noHayCuotas = DetalleVenta::where('id_venta', $venta)
            // ->where('pagado','=','no')->count('id_detalle_venta');

            if (true) {
                $request->merge(['venta' => $venta, 'ultimaCuota' => $ultimaCuota]);

                return $next($request);
            }

            return redirect()->route('clientes.index')->with('error', 'Aún no se puede aplicar la actualización de las cuotas al registro seleccionado');

        } catch (\Throwable$e) {
            dd($e->getMessage());
            return redirect()->route('clientes.index')->with('error', 'Aún no se puede aplicar la actualización de las cuotas al registro seleccionado');
        }

    }
}