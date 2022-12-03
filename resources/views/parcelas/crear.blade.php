@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


    <div class="main-panel">

        <div class="content-wrapper  d-flex justify-content-center">

            <div class="col-md-6 grid-margin transparent">


                <div class="app-title mb-5">

                    <div>
                        <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Agregar Parcela
                        </h1>
                    </div>
                </div>

                <form class="forms-sample" method="POST" action="{{route('parcelas.guardar')}}">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputName1">Superficie</label>
                        <input type="text" class="form-control" name="superficie_parcela" id="nombreLote" placeholder="Ingrese el Nombre">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3">Manzana</label>
                        <input type="text" class="form-control"  name="manzana" id="superficieLote" placeholder="Ingrese la Superficie">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword4">Cantidad de Bolsas de Cemento</label>
                        <input type="text" class="form-control" name="cantidad_bolsas" id="cantidadManzanas" placeholder="Ingrese la Cantidad">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Ancho</label>
                        <input type="text" class="form-control" name="ancho" id="ubicacion" placeholder="Ingrese la Ubicacion">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Largo</label>
                        <input type="text" class="form-control" name="largo" id="ubicacion" placeholder="Ingrese la Ubicacion">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Lote</label>
                        <select class="form-control" name="lote" id="lote">
                            <option value="0" selected disabled>Seleccione una opcion</option>
                            @foreach ($lotes as $lote)
                                <option value="{{$lote->id_lote}}">{{$lote->nombre_lote}}</option>
                            @endforeach

                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                    <a href="{{route('parcelas')}}" class="btn btn-danger form-control">Cancelar</a>
                </form>

                <x-alerta />
            </div>
        </div>

    </div>



@endsection
