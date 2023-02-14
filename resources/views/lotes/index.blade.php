@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')

<div class="main-panel">

    <div class="content-wrapper d-flex justify-content-center">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @if (count($lotes) === 0)
            <h1 class="text-center text-danger"><i class="fa fa-desktop fa-lg"></i> Aun no hay lotes cargados
            </h1>
            <a href="{{route('lotes.crear')}}" class="btn btn-success">Agregar Lote</a>
            @else

            <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i>Loteos
            </h1>

            <x-alertas />

            <a href="{{route('lotes.crear')}}" class="btn btn-success mb-2">Agregar Lote</a>

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