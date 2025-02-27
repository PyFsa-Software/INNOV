@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


    <div class="main-panel">

        <div class="content-wrapper  d-flex justify-content-center">

            <div class="col-md-6 grid-margin transparent">

                <div class="app-title mb-5">

                    <div>
                        <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Agregar Venta
                        </h1>
                    </div>
                </div>

                <livewire:venta-parcela :clientes="$clientes" :parcelas="$parcelas" :promedioCementoDelMes="$promedioCemento" :formasDePagos="$formasDePagos"
                    :conceptosDe="$conceptosDe" :periodosActualizacion="$periodosActualizacion" />

            </div>
        </div>

    </div>



@endsection
