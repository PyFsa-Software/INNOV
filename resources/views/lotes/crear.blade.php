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
                    <label for="nombre_lote">Nombre del Lote</label>
                    <input type="text" class="form-control" name="nombre_lote" id="nombreLote"
                        placeholder="Ingrese el Nombre" autofocus value="{{old('nombre_lote')}}">
                </div>
                <div class="form-group">
                    <label for="hectareas_lote">Hectáreas del Lote</label>
                    <input type="text" class="form-control" name="hectareas_lote" id="hectareasLote"
                        placeholder="Ingrese las Hectáreas" value="{{old('hectareas_lote')}}">
                </div>
                <div class="form-group">
                    <label for="cantidad_manzanas">Cantidad de Manzanas</label>
                    <input type="text" class="form-control" name="cantidad_manzanas" id="cantidadManzanas"
                        placeholder="Ingrese la Cantidad" value="{{old('cantidad_manzanas')}}">
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicacion</label>
                    <input type="text" class="form-control" name="ubicacion" id="ubicacion"
                        placeholder="Ingrese la Ubicacion" value="{{old('ubicacion')}}">
                </div>
                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                <a href="{{route('lotes.index')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>

            <x-alertas />
        </div>
    </div>

</div>



@endsection