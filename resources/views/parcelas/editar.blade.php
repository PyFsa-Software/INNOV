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

                <form class="forms-sample" method="POST" action="{{route('parcelas.modificar',$parcela)}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="exampleInputName1">Superficie</label>
                        <input type="text" class="form-control" name="superficie_parcela" id="nombreLote" placeholder="Ingrese la superficie"  value="{{old('superficie_parcela', $parcela->superficie_parcela)}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3">Manzana</label>
                        <input type="text" class="form-control"  name="manzana" id="superficieLote" placeholder="Ingrese la manzana"  value="{{old('manzana', $parcela->manzana)}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword4">Cantidad de Bolsas de Cemento</label>
                        <input type="text" class="form-control" name="cantidad_bolsas" id="cantidadManzanas" placeholder="Ingrese la Cantidad de bolsas" value="{{old('cantidad_bolsas', $parcela->cantidad_bolsas)}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Ancho</label>
                        <input type="text" class="form-control" name="ancho" id="ubicacion" placeholder="Ingrese el ancho" value="{{old('ancho', $parcela->ancho)}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Largo</label>
                        <input type="text" class="form-control" name="largo" id="ubicacion" placeholder="Ingrese el largo" value="{{old('largo', $parcela->largo)}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Lote</label>
                        <select class="form-control" name="lote" id="lote" >
                            <option value="0" selected disabled>Seleccione una opcion</option>
                            <option selected value="">{{old('lote', $parcela->id_lote)}}</option>
                            @foreach ($lotes as $lote)
                            <option selected value="">{{old('lote', $lote->nombre_lote)}}</option>
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
