<?php

namespace App\Http\Livewire;

use App\Enums\MonedaPago;
use App\Models\DetalleReservaParcela;
use Livewire\Component;

class RealizarPagoReserva extends Component
{

    public $reserva;
    public $detalleReserva;
    public $formasDePagos;
    public $montoTotal;
    public $montoActualAbonado;
    public $conceptoDe;
    public $importeEntrega;

    public $importeEntregaCalcular;
    public $montoTotalCalcular;
    public $montoTotalAbonadoCalcular;

    public $formaPago = "";

    public $isDisabled = true;

    public $monedaPago = "";
    public $monedasDePagos = [];

    // protected $rules = [
    //     'formaPago' => 'required',
    //     'importeEntrega' => 'required|numeric|',
    //     'conceptoDe' => 'required|string',
    // ];

    public function mount($reserva, $detalleReserva, $formasDePagos)
    {
        $this->monedasDePagos = MonedaPago::toArray();
        $this->reserva = $reserva;
        $this->detalleReserva = $detalleReserva;
        $this->formasDePagos = $formasDePagos;

        $this->montoTotalCalcular = $this->reserva->monto_total;
        $this->montoTotalAbonadoCalcular = $this->detalleReserva->sum('importe_pago');
        
        $this->montoTotal = "$" . number_format($this->reserva->monto_total, 2, ',', '.');
        $this->montoActualAbonado = "$" . number_format($this->detalleReserva->sum('importe_pago'), 2, ',', '.');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->isDisabled = false;
    }

    public function rules()
    {
        return [
            'formaPago' => 'required',
            'importeEntrega' => 'required|numeric|',
            'conceptoDe' => 'required|string',
        ];
    }

    public function validarImporteEntrega()
    {
        $this->validate();


        // Validar y mostrar mensaje si el importe de entrega es mayor
        if ($this->importeEntrega > floatval($this->montoTotalCalcular)) {
            $this->isDisabled = true;
            $this->addError('importeEntrega', 'El importe de entrega no puede ser mayor que el monto total.');
        }
        // Validar y mostrar mensaje si la suma de importeEntrega y montoActualAbonado es mayor que montoTotal
        if (($this->importeEntrega + floatval($this->montoTotalAbonadoCalcular)) > floatval($this->montoTotalCalcular)) {
            $this->isDisabled = true;
            $this->addError('importeEntrega', 'La suma del importe de entrega y el monto actual abonado no puede ser mayor que el monto total.');
        } else {
            $this->validateOnly('importeEntrega');
        }
    }

    public function submit()
    {
        $this->validate();


        $detalle = DetalleReservaParcela::create([
            'id_reserva_parcela' => $this->reserva->id_reserva_parcela,
            'fecha_pago' => now(),
            'forma_pago' => $this->formaPago,
            'importe_pago' => $this->importeEntrega,
            'concepto_de' => $this->conceptoDe,
            'moneda_pago' => $this->monedaPago,
        ]);


        $this->montoTotalAbonadoCalcular = DetalleReservaParcela::where('id_reserva_parcela', $this->reserva->id_reserva_parcela)->sum('importe_pago');

        if ($this->montoTotalCalcular == $this->montoTotalAbonadoCalcular) {

            $this->reserva->update([
                'estado_reserva' => true,
            ]);

            $detalle->update([
                'cancelado' => true,
            ]);

        }

        return redirect()->route('reservaParcela.payments', $this->reserva->id_reserva_parcela);

    }


    public function render()
    {
        return view('livewire.realizar-pago-reserva');
    }
}