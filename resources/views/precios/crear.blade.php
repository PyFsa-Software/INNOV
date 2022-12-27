@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Agregar Precio
                    </h1>
                </div>
            </div>

            <form class="forms-sample" method="POST" action="{{route('precios.guardar')}}">
                @csrf
                <div class="form-group">
                    <label for="precio_bercomat">Precio Bercomat</label>
                    <input type="number" class="form-control" name="precio_bercomat" id="precio_bercomat"
                        placeholder="Ingrese el precio de Bercomat" autofocus>
                </div>
                <div class="form-group">
                    <label for="precio_sancayetano">Precio San Cayetano</label>
                    <input type="number" class="form-control" name="precio_sancayetano" id="precio_sancayetano"
                        placeholder="Ingrese el precio de San Cayetano">
                </div>
                <div class="form-group">
                    <label for="precio_rio_colorado">Precio Rio Colorado</label>
                    <input type="number" class="form-control" name="precio_rio_colorado" id="precio_rio_colorado"
                        placeholder="Ingrese el precio de Rio Colorado">
                </div>
                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                <a href="{{route('precios.index')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>


            <x-alertas />
        </div>
    </div>

</div>



@endsection