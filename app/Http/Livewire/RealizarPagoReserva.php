<?php

namespace App\Http\Livewire;

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

    public $formaPago = "";

    public $isDisabled = true;


    protected $rules = [
        'formaPago' => 'required',
        'importeEntrega' => 'required|numeric|',
        'conceptoDe' => 'required|string',
    ];

    public function mount($reserva, $detalleReserva, $formasDePagos)
    {
        $this->reserva = $reserva;
        $this->detalleReserva = $detalleReserva;
        $this->formasDePagos = $formasDePagos;
    
        $this->montoTotal = "$" . number_format($this->reserva->monto_total, 2, ',', '.');
        $this->montoActualAbonado = "$" . number_format($this->detalleReserva->sum('importe_pago'), 2, ',', '.');

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

        // Validar y mostrar mensaje si el importe de entrega es mayor
        if ($this->importeEntrega > $this->montoTotal) {
            $this->isDisabled = true;
            $this->addError('importeEntrega', 'El importe de entrega no puede ser mayor que el monto total.');
        } else {
            $this->isDisabled = false;
        }
    }

    // public function submit()
    // {
    //     $this->validate([
    //         'formaPago' => 'required',
    //     ]);

    //     $this->reserva->monto_abonado = $this->reserva->monto_abonado + $this->montoActualAbonado;
    //     $this->reserva->save();

    //     $this->emit('realizarPago');
    // }


    public function render()
    {
        return view('livewire.realizar-pago-reserva');
    }
}