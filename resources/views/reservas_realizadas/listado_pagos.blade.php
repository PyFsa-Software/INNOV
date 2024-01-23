@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


    <div class="main-panel">

        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @if (count($reservas) === 0)
                    <a href="{{ route('reservaParcela.pay', $reservas[0]->id_reserva_parcela) }}"
                        class="btn btn-success mb-2">Realizar Pago</a>
                    <a href="{{ route('reservaParcela.index') }}" class="btn btn-primary mb-2">Volver</a>



                    <h1 class="text-center text-danger"><i class="fa fa-desktop fa-lg"></i> Aun no hay registros de Pagos
                    </h1>
                @else
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado de Pagos
                    </h1>
                    <x-alertas />
                    <a href="{{ route('reservaParcela.index') }}" class="btn btn-primary mb-2">Volver</a>
                    <a href="{{ route('reservaParcela.pay', $reservas[0]->id_reserva_parcela) }}"
                        class="btn btn-success mb-2">Realizar Pago</a>

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
