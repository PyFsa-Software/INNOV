@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


    <div class="main-panel">

        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado Cuotas
                </h1>
                <a href="{{ route('clientes.estado', $idCliente) }}" class="btn btn-warning mb-2">Volver Atrás</a>
                <a href="{{ route('clientes.cobrarTodo', $idCliente) }}" class="btn btn-success mb-2">Pagar Todo <i
                        class='ti-ticket'></i></a>
                <x-alertas />
                <p class="text-center"><b style="color: red;">DD-MM-AAAA</b> Cuota Vencida <b>|</b>
                    🟥 Debe Actualizar el Precio de la Cuota</p>
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
