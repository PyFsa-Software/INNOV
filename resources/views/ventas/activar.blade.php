@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Activar Cliente
                    </h1>
                </div>
            </div>

            <form class="forms-sample" method="POST" action="{{route('clientes.activar',$persona->id_persona)}}">
                @csrf
                @method('PATCH')

                <h3><b>Cliente:</b> {{$persona->nombre}} {{$persona->apellido}}</h3>
                <button type="submit" class="btn btn-success mr-2 mb-2 form-control">Activar</button>
                <a href="{{route('clientes.index')}}" class="btn btn-warning form-control">Cancelar</a>
            </form>

        </div>
    </div>

</div>



@endsection