@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper d-flex justify-content-center">

        <div class="col-md-12 grid-margin transparent">

            <div class="app-title mb-5">
                <h3 class="text-center"><i class="fa fa-desktop fa-lg"></i> Estado Cliente:
                    {{$persona->nombre}}-{{$persona->apellido}}
                </h3>
                <a href="{{route('clientes.index')}}" class="btn btn-warning">Volver Atras</a>

            </div>
            @foreach ($parcelas as $parcela)
            <div class="card text-center mt-3">
                <div class="card-header ">
                    {{$parcela->descripcion_parcela}}
                </div>
                <div class="card-body">
                    <h5 class="card-title {{$parcela->cantidadDeudas > 0 ? 'text-danger': 'text-success'}}">Estado:
                        {{$parcela->cantidadDeudas > 0 ? 'Hay cuotas pendientes': 'Normal'}}</h5>
                    {{-- <ul class="text-left">
                        <li>*</li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul> --}}
                    <a href="{{route('clientes.estadoCuotas', $parcela->id_parcela)}}" class="btn btn-primary">Ver
                        Detalle</a>
                </div>
                {{-- <div class="card-footer text-muted">
                    2 days ago
                </div> --}}
            </div>
            @endforeach

        </div>
    </div>

</div>



@endsection