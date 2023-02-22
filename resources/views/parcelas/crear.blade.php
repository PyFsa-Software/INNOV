@extends('layouts.app')

@section('titulo', 'INNOV')

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

            <x-alertas />

            <form class="forms-sample" method="POST" action="{{route('parcelas.guardar')}}">
                @csrf
                <div class="form-group">
                    <label for="descripcion_parcela">Descripción</label>
                    <input type="text" class="form-control" name="descripcion_parcela" id="descripcion_parcela"
                        placeholder="Ingrese una descripción" value="{{old('descripcion_parcela')}}" autofocus>
                </div>
                <div class="form-group">
                    <label for="superficie_parcela">Superficie</label>
                    <input type="number" class="form-control" name="superficie_parcela" id="superficie_parcela"
                        placeholder="Ingrese la superficie" value="{{old('superficie_parcela')}}">
                </div>
                <div class="form-group">
                    <label for="manzana">Manzana</label>
                    <input type="number" class="form-control" name="manzana" id="manzana"
                        placeholder="Ingrese la manzana" value="{{old('manzana')}}">
                </div>
                <div class="form-group">
                    <label for="cantidad_bolsas">Cantidad de Bolsas de Cemento</label>
                    <input type="number" class="form-control" name="cantidad_bolsas" id="cantidad_bolsas"
                        placeholder="Ingrese la Cantidad" value="{{old('cantidad_bolsas')}}">
                </div>
                <div class="form-group">
                    <label for="ancho">Ancho</label>
                    <input type="number" class="form-control" name="ancho" id="ancho" placeholder="Ingrese el ancho"
                        value="{{old('ancho')}}">
                </div>
                <div class="form-group">
                    <label for="largo">Largo</label>
                    <input type="number" class="form-control" name="largo" id="largo" placeholder="Ingrese el largo"
                        value="{{old('largo')}}">
                </div>
                <div class="form-group">
                    <label for="lote">Lote</label>
                    <select class="form-control" name="lote" id="lote">
                        <option value="0" selected disabled>Seleccione una opcion</option>
                        @foreach ($lotes as $lote)
                        <option value="{{$lote->id_lote}}" @selected(old('lote')==$lote->id_lote)>{{$lote->nombre_lote}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                <a href="{{route('parcelas.index')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>

           
        </div>
    </div>

</div>



@endsection