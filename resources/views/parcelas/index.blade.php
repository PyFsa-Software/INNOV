@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper d-flex justify-content-center">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @if (count($parcelas) === 0)
            <a href="{{route('parcelas.crear')}}" class="btn btn-success">Agregar Parcela</a>

            <h2 class="text-center mt-5 text-danger"><i class="fa fa-desktop fa-lg"></i> Aun no hay Parcelas cargadas
            </h2>
            @else

            <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado de Parcelas
            </h1>

            <x-alertas />

            <a href="{{route('parcelas.crear')}}" class="btn btn-success mb-2">Agregar Parcela</a>

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