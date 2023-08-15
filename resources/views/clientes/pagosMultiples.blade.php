@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


    <div class="main-panel">

        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <h1 class="text-center mb-5"><i class="fa fa-desktop fa-lg"></i> Listado Pagos Multiples
                </h1>
                <a href="{{ route('clientes.estadoCuotas', $idParcela) }}" class="btn btn-warning mb-2">Volver Atrás</a>
                <x-alertas />

                <div class="table-responsive">
                    {{ $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) }}
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
