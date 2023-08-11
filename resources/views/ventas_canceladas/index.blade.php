@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper d-flex justify-content-center">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @if (count($ventasCanceladas) === 0)

            <h2 class="text-center mt-5 text-danger"><i class="fa fa-desktop fa-lg"></i> Aun no hay Ventas Canceladas
            </h2>
            @else

            <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado de Ventas Canceladas
            </h1>

            <x-alertas />


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