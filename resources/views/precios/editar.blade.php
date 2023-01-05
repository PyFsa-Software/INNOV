@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Editar Precio
                    </h1>
                </div>
            </div>

            <form class="forms-sample" method="POST" action="{{route('precios.modificar', $precio)}}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="precio_bercomat">Precio Bercomat</label>
                    <input type="number" class="form-control" name="precio_bercomat" id="precio_bercomat"
                        placeholder="Ingrese el precio de Bercomat" autofocus
                        value="{{old('precio_bercomat', $precio->precio_bercomat)}}">
                </div>
                <div class="form-group">
                    <label for="precio_sancayetano">Precio San Cayetano</label>
                    <input type="number" class="form-control" name="precio_sancayetano" id="precio_sancayetano"
                        placeholder="Ingrese el precio de San Cayetano"
                        value="{{old('precio_sancayetano', $precio->precio_sancayetano)}}">
                </div>
                <div class="form-group">
                    <label for="precio_rio_colorado">Precio Rio Colorado</label>
                    <input type="number" class="form-control" name="precio_rio_colorado" id="precio_rio_colorado"
                        placeholder="Ingrese el precio de Rio Colorado"
                        value="{{old('precio_rio_colorado', $precio->precio_rio_colorado)}}">
                </div>
                <button type="submit" class="btn btn-warning mr-2 mb-2 form-control">Editar</button>
                <a href="{{route('precios.index')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>


            <x-alertas />
        </div>
    </div>

</div>



@endsection