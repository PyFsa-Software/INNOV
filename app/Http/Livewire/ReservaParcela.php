<?php

namespace App\Http\Livewire;

use App\Enums\MonedaPago;
use App\Models\DetalleReservaParcela;
use App\Models\Parcela;
use App\Models\ReservaParcela as ModelsReservaParcela;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReservaParcela extends Component
{

    public $clientes;
    public $parcelas;
    public $formasDePagos;
    public $conceptoDe;
    public $importeEntrega;
    public $montoTotal;
    public $estadoReserva = false;
    public $parcelaById;



    public $formaPago = "";
    public $clienteCombo = "";
    public $parcelaCombo = "";

    public $isDisabled = true;

    public $monedaPago = "";
    public $monedasDePagos = [];

    protected $rules = [
        'clienteCombo' => 'required|numeric|integer',
        'parcelaCombo' => 'required|string',
        'formaPago' => 'required|string',
        'conceptoDe' => 'required|string',
        'importeEntrega' => 'required|numeric|min:1',
        'montoTotal' => 'required|numeric|min:1',
    ];

    public function mount()
    {
        $this->monedasDePagos = MonedaPago::toArray();
    }

    protected $listeners = ['setCliente'];

    public function setCliente($value)
    {
        $this->clienteCombo = $value;
    }

    public function updated($propertyName)
    {
        $this->isDisabled = true;
        $this->validateOnly($propertyName);
        $this->isDisabled = false;
    }

    public function validarImporteEntrega()
    {
        $this->validate();

        $this->parcelaById = Parcela::where([
            ['id_parcela', '=', $this->parcelaCombo],
        ])->first();

        // Validar y mostrar mensaje si el importe de entrega es mayor
        if ($this->importeEntrega > $this->montoTotal) {
            $this->isDisabled = true;
            $this->addError('importeEntrega', 'El importe de entrega no puede ser mayor que el monto total.');
        } else {
            $this->validateOnly('importeEntrega');
        }
    }

    public function submit()
    {
        $this->validate();
        $this->isDisabled = false;
        try {
            DB::beginTransaction();

            $this->importeEntrega == $this->montoTotal ? $this->estadoReserva = true : $this->estadoReserva = false;

            $reserva = ModelsReservaParcela::create([
                'id_cliente' => $this->clienteCombo,
                'id_parcela' => $this->parcelaCombo,
                'fecha_reserva' => now(),
                'monto_total' => $this->montoTotal,
                'estado_reserva' => $this->estadoReserva,
            ]);

            $reserva->save();


            DetalleReservaParcela::create([
                'id_reserva_parcela' => $reserva->id_reserva_parcela,
                'fecha_pago' => now(),
                'forma_pago' => $this->formaPago,
                'concepto_de' => $this->conceptoDe,
                'importe_pago' => $this->importeEntrega,
                'cancelado' => $this->importeEntrega == $this->montoTotal ? 1 : 0,
                'moneda_pago' => $this->monedaPago,
            ]);

            DB::commit();

            return redirect()->route('reservaParcela.index')->with('success', "Pre-Venta realizada correctamente.");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function render()
    {
        return view('livewire.reserva-parcela');
    }
}
