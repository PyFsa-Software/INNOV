<?php

namespace App\Http\Controllers;

use App\DataTables\VentasDataTable;
use App\Enums\ConceptoDeVenta;
use App\Enums\FormasPago;
use App\Enums\PeriodosActualizacion;
use App\Models\DetalleReservaParcela;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\Precio;
use App\Models\ReservaParcela;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VentasDataTable $dataTable)
    {

        $ventas = Venta::all();
        return $dataTable->render('ventas_realizadas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Persona::where([
            ['cliente', '=', '1'],
            ['activo', '=', '1'],
        ])->get();

        $parcelas = [];

        $promedioCemento = Precio::orderBy('fecha', 'desc')->first();

        $formasDePagos = FormasPago::toArray();

        $conceptosDe = ConceptoDeVenta::toArray();

        $periodosActualizacion = PeriodosActualizacion::toArray();

        return view('ventas.crear', compact('clientes', 'parcelas', 'promedioCemento', 'formasDePagos', 'conceptosDe', 'periodosActualizacion'));
    }

    /**
     * Generate a payment voucher for a specific sale.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generarVolantePago($id)
    {
        try {
            // Obtener la venta por su ID
            $venta = Venta::findOrFail($id);

            // Obtener la persona asociada a la venta
            $cliente = Persona::where('id_persona', $venta->id_cliente)->first();


            $parcela = Parcela::where('id_parcela', $venta->id_parcela)->with('lote')->first();

            $pathLogo = Storage::path('public/img/logoInnova.jpg');
            $logo = file_get_contents($pathLogo);
            $html = '<img src="data:image/svg+xml;base64,' . base64_encode($logo) . '"  width="100" height="100" />';

            $fecha_venta = Carbon::parse($venta->fecha_venta);
            $fecha_venta->format('d/m/Y');

            // Generar el volante de pago en formato PDF
            $pdf = Pdf::loadView('ventas_realizadas.volante_pago', compact('venta', 'cliente', 'html', 'fecha_venta', 'parcela'))->setPaper('cart', 'vertical');

            return $pdf->stream(date('d-m-Y') . ".pdf", array('Attachment' => 0));
            // Descargar el PDF
            // return $pdf->download('volante_pago.pdf');
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => "Se produjo un error al generar el volante de pago. Por favor, contacte al administrador.",
            ], 400);
        }
    }


    public function eliminarVenta(Venta $venta)
    {
        DB::beginTransaction(); // Inicia la transacción para asegurar consistencia

        try {
            // 1. Eliminar los registros relacionados en detalleVentas
            $venta->detalleVenta()->delete();

            // 2. Obtener el id_parcela de la venta
            $idParcela = $venta->id_parcela;

            // 3. Buscar en la tabla ReservaParcela si existe un registro relacionado
            $reservaParcela = ReservaParcela::where('id_parcela', $idParcela)->first();

            if ($reservaParcela) {
                // 4. Buscar en la tabla DetalleReservaParcela los registros relacionados
                DetalleReservaParcela::where('id_reserva_parcela', $reservaParcela->id_reserva_parcela)->delete();
                // 6. Eliminar la reserva parcela
                $reservaParcela->delete();
            }
            // 5. Actualizar el campo disponible de la parcela a 1
            Parcela::where('id_parcela', $idParcela)->update(['disponible' => 1]);

            // 7. Finalmente, eliminar la venta
            $venta->delete();

            DB::commit(); // Confirma los cambios en la base de datos
            return redirect()->back()->with('success', 'Venta eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revierte los cambios en caso de error
            return redirect()->back()->withErrors('Ocurrió un error al eliminar la venta: ' . $e->getMessage());
        }
    }
}
