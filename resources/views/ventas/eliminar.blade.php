@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Eliminar Cliente
                    </h1>
                </div>
            </div>

            <form class="forms-sample" method="POST" action="{{route('clientes.eliminar',$persona->id_persona)}}">
                @csrf
                @method('DELETE')

                <h3><b>Cliente:</b> {{$persona->nombre}} {{$persona->apellido}}</h3>
                <button type="submit" class="btn btn-danger mr-2 mb-2 form-control">Eliminar</button>
                <a href="{{route('clientes.index')}}" class="btn btn-warning form-control">Cancelar</a>
            </form>

        </div>
    </div>

</div>



@endsection