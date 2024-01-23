@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


    <div class="main-panel">

        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                @if (count($reservas) === 0)
                    <a href="{{ route('reservaParcela.crear') }}" class="btn btn-success mb-2">Realizar Pre-Venta</a>
                    <h1 class="text-center text-danger"><i class="fa fa-desktop fa-lg"></i> Aun no hay registros de Pre-Ventas
                    </h1>
                @else
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado de Pre-Ventas
                    </h1>
                    <x-alertas />
                    <a href="{{ route('reservaParcela.crear') }}" class="btn btn-success mb-2">Realizar Pre-Venta</a>
                    <div class="table-responsive">

                        {{ $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) }}
                    </div>
                @endif
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
