@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Generar Cuotas
                    </h1>
                </div>
            </div>

            <livewire:form-generar-cuotas :venta="$venta" :parcela="$parcela" :ultimaCuota="$ultimaCuota" />

        </div>
    </div>

</div>



@endsection