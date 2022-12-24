@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">

<div class="main-panel">

    <div class="content-wrapper d-flex justify-content-center">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado Cuotas
            </h1>
            {{-- @if (count($clientes) === 0)
            <a href="{{route('clientes.crear')}}" class="btn btn-success">Agregar Cliente</a>
            @else

            <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado de Clientes
            </h1>
            <x-alertas />
            <a href="{{route('clientes.crear')}}" class="btn btn-success mb-2">Agregar Cliente</a>
            @endif --}}
            <x-alerta />
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