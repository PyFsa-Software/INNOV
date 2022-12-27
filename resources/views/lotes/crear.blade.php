@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">


            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Agregar Lote
                    </h1>
                </div>
            </div>

            <form class="forms-sample" method="POST" action="{{route('lotes.guardar')}}">
                @csrf
                <div class="form-group">
                    <label for="exampleInputName1">Nombre del Lote</label>
                    <input type="text" class="form-control" name="nombre_lote" id="nombreLote"
                        placeholder="Ingrese el Nombre">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail3">Superficie del Lote</label>
                    <input type="text" class="form-control" name="superficie_lote" id="superficieLote"
                        placeholder="Ingrese la Superficie">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword4">Cantidad de Manzanas</label>
                    <input type="text" class="form-control" name="cantidad_manzanas" id="cantidadManzanas"
                        placeholder="Ingrese la Cantidad">
                </div>
                <div class="form-group">
                    <label for="exampleInputCity1">Ubicacion</label>
                    <input type="text" class="form-control" name="ubicacion" id="ubicacion"
                        placeholder="Ingrese la Ubicacion">
                </div>
                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                <a href="{{route('lotes')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>

            <x-alertas />
        </div>
    </div>

</div>



@endsection