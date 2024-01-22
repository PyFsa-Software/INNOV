<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ReservaParcela extends Component
{

    public $clientes;
    public $parcelas;
    public $formasDePagos;

    


    public function render()
    {
        return view('livewire.reserva-parcela');
    }
}