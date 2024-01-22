<?php

namespace App\Http\Controllers;

use App\Enums\FormasPago;
use App\Models\Parcela;
use App\Models\Persona;
use App\Models\ReservaParcela;
use Illuminate\Http\Request;

class ReservaParcelaController extends Controller
{
    

    public function index()
    {

        $reservas = ReservaParcela::all();

        return view('reservas_realizadas.index', compact('reservas'));
    }

    public function create()
    {

        $clientes = Persona::where([
            ['cliente', '=', '1'],
            ['activo', '=', '1'],
        ])->get();

        $parcelas = Parcela::where([
            ['disponible', '=', '1'],
        ])->get();

        $formasDePagos = FormasPago::toArray();


        return view('reservas_realizadas.crear', compact('clientes', 'parcelas', 'formasDePagos'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nombre' => 'required',
    //         'apellido' => 'required',
    //         'dni' => 'required',
    //         'telefono' => 'required',
    //         'email' => 'required',
    //         'parcela' => 'required',
    //         'fecha' => 'required',
    //         'hora' => 'required',
    //         'monto' => 'required',
    //         'forma_pago' => 'required',
    //         'concepto' => 'required',
    //     ]);

    //     $reserva = new ReservaParcela([
    //         'nombre' => $request->get('nombre'),
    //         'apellido' => $request->get('apellido'),
    //         'dni' => $request->get('dni'),
    //         'telefono' => $request->get('telefono'),
    //         'email' => $request->get('email'),
    //         'parcela' => $request->get('parcela'),
    //         'fecha' => $request->get('fecha'),
    //         'hora' => $request->get('hora'),
    //         'monto' => $request->get('monto'),
    //         'forma_pago' => $request->get('forma_pago'),
    //         'concepto' => $request->get('concepto'),
    //     ]);
    //     $reserva->save();
    //     return redirect('/reserva_parcela')->with('success', 'Reserva guardada!');
    // }

    public function show($id)
    {
        // $reserva = ReservaParcela::find($id);
        return view('reserva_parcela.ver', compact('reserva'));
    }

    public function edit($id)
    {
        // $reserva = ReservaParcela::find($id);
        return view('reserva_parcela.editar', compact('reserva'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'dni' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'parcela' => 'required',
            'fecha' => 'required',
            'hora' => 'required',
            'monto' => 'required',
            'forma_pago' => 'required',
            'concepto' => 'required',
        ]);

        // $reserva = ReservaParcela::find($id);

    }
    


}