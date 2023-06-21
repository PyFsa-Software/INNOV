<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FormCobrarTodo extends Component
{
    public $venta;
    public $cuotasGeneradas;
    public $cuotasPagadas;



    public function submit()
    {

    }

    public function render()
    {
        return view('livewire.form-cobrar-todo');
    }
}