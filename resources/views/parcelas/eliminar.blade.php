@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


    <div class="main-panel">

        <div class="content-wrapper  d-flex justify-content-center">

            <div class="col-md-6 grid-margin transparent">


                <div class="app-title mb-5">

                    <div>
                        <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Eliminar Parcela
                        </h1>
                    </div>
                </div>

                <form class="forms-sample" method="POST" action="{{route('parcelas.eliminar',$parcela)}}">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <label for="exampleInputName1">Superficie</label>
                        <input type="text" class="form-control" name="superficie_parcela" id="nombreLote" placeholder="Ingrese la superficie"  value="{{old('superficie_parcela', $parcela->superficie_parcela)}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3">Manzana</label>
                        <input type="text" class="form-control"  name="manzana" id="superficieLote" placeholder="Ingrese la manzana"  value="{{old('manzana', $parcela->manzana)}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword4">Cantidad de Bolsas de Cemento</label>
                        <input type="text" class="form-control" name="cantidad_bolsas" id="cantidadManzanas" placeholder="Ingrese la Cantidad de bolsas" value="{{old('cantidad_bolsas', $parcela->cantidad_bolsas)}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Ancho</label>
                        <input type="text" class="form-control" name="ancho" id="ubicacion" placeholder="Ingrese el ancho" value="{{old('ancho', $parcela->ancho)}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Largo</label>
                        <input type="text" class="form-control" name="largo" id="ubicacion" placeholder="Ingrese el largo" value="{{old('largo', $parcela->largo)}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Lote</label>
                        <select class="form-control" name="lote" id="lote" disabled >
                            <option value="0" selected disabled>Seleccione una opcion</option>
                            <option selected value="{{old('lote', $loteSeleccionado->id_lote)}}">{{old('lote', $loteSeleccionado->nombre_lote)}}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Eliminar</button>
                    <a href="{{route('parcelas')}}" class="btn btn-danger form-control">Cancelar</a>
                </form>

                <x-alerta />
            </div>
        </div>

    </div>



@endsection
