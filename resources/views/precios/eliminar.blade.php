@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Eliminar Precio
                    </h1>
                </div>
            </div>

            <form class="forms-sample" method="POST" action="{{route('precios.eliminar',$precio->id_precio_cemento)}}">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label for="precio_bercomat">Precio Bercomat</label>
                    <input type="number" class="form-control" name="precio_bercomat" id="precio_bercomat"
                        placeholder="Ingrese el precio de Bercomat" autofocus
                        value="{{old('precio_bercomat', $precio->precio_bercomat)}}" disabled>
                </div>
                <div class="form-group">
                    <label for="precio_sancayetano">Precio San Cayetano</label>
                    <input type="number" class="form-control" name="precio_sancayetano" id="precio_sancayetano"
                        placeholder="Ingrese el precio de San Cayetano"
                        value="{{old('precio_sancayetano', $precio->precio_sancayetano)}}" disabled>
                </div>
                <div class="form-group">
                    <label for="precio_rio_colorado">Precio Rio Colorado</label>
                    <input type="number" class="form-control" name="precio_rio_colorado" id="precio_rio_colorado"
                        placeholder="Ingrese el precio de Rio Colorado"
                        value="{{old('precio_rio_colorado', $precio->precio_rio_colorado)}}" disabled>
                </div>

                <div class="form-group">
                    <label for="precio_promedio">Precio Promedio</label>
                    <input type="number" class="form-control" name="precio_promedio" id="precio_promedio"
                        placeholder="Ingrese el precio de Rio Colorado"
                        value="{{old('precio_promedio', $precio->precio_promedio)}}" disabled>
                </div>
                <button type="submit" class="btn btn-danger mr-2 mb-2 form-control">Eliminar</button>
                <a href="{{route('precios.index')}}" class="btn btn-warning form-control">Cancelar</a>
            </form>


            <x-alertas />
        </div>
    </div>

</div>



@endsection