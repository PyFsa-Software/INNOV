@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">


            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Eliminar Lote
                    </h1>
                </div>
            </div>

            <form class="forms-sample" method="POST" action="{{route('lotes.eliminar', $lote->id_lote)}}">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label for="nombre_lote">Nombre del Lote</label>
                    <input type="text" class="form-control" name="nombre_lote" id="nombreLote"
                        placeholder="Ingrese el Nombre" value="{{old('nombre_lote', $lote->nombre_lote)}} " disabled
                        autofocus>
                </div>
                <div class="form-group">
                    <label for="hectareas_lote">Hectáreas del Lote</label>
                    <input type="text" class="form-control" name="hectareas_lote" id="superficieLote"
                        placeholder="Ingrese la Superficie" value="{{old('hectareas_lote', $lote->hectareas_lote)}} "
                        disabled>
                </div>
                <div class="form-group">
                    <label for="cantidad_manzanas">Cantidad de Manzanas</label>
                    <input type="text" class="form-control" name="cantidad_manzanas" id="cantidadManzanas"
                        placeholder="Ingrese la Cantidad" value="{{old('cantidad_manzanas', $lote->cantidad_manzanas)}}"
                        disabled>
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicacion</label>
                    <input type="text" class="form-control" name="ubicacion" id="ubicacion"
                        placeholder="Ingrese la Ubicacion" value="{{old('ubicacion', $lote->ubicacion)}}" disabled>
                </div>
                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Eliminar</button>
                <a href="{{route('lotes')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>

            <x-alertas />
        </div>
    </div>

</div>



@endsection