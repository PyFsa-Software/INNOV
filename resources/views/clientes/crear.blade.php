@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Agregar Cliente
                    </h1>
                </div>
            </div>


            <form class="forms-sample" method="POST" action="{{route('clientes.guardar')}}">
                @csrf
                <div class="form-group">
                    <label for="nombre">Nombre Cliente<span class="text-danger">*</span></label>
                    <br>
                    <small class="text-danger">Para añadir varios varios nombres usar el siguiente caracter para separarlos: <b>"/".</b></small>
                    <input type="text" class="form-control" name="nombre" id="nombre"
                        placeholder="Ingrese el nombre del cliente" autofocus value="{{old('nombre')}}">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido Cliente<span class="text-danger">*</span></label>
                    <br>
                    <small class="text-danger">Para añadir varios varios apellidos usar el siguiente caracter para separarlos: <b>"/".</b></small>
                    <input type="text" class="form-control" name="apellido" id="apellido"
                        placeholder="Ingrese el apellido del cliente" value="{{old('apellido')}}">
                </div>

                <div class="form-group">
                    <label for="dni">Dni Cliente<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dni" id="dni" placeholder="Ingrese el dni del cliente"
                        value="{{old('dni')}}">
                </div>

                <div class="form-group">
                    <label for="domicilio">Domicilio Cliente<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="domicilio" id="domicilio"
                        placeholder="Ingrese el domicilio del cliente" value="{{old('domicilio')}}">
                </div>

                <div class="form-group">
                    <label for="celular">Celular Cliente</label>
                    <input type="number" class="form-control" name="celular" id="celular"
                        placeholder="Ingrese el celular del cliente" value="{{old('celular')}}">
                </div>

                <div class="form-group">
                    <label for="correo">Correo Cliente</label>
                    <input type="text" class="form-control" name="correo" id="correo"
                        placeholder="Ingrese el correo del cliente" value="{{old('correo')}}">
                </div>

                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                <a href="{{route('clientes.index')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>

            <x-alertas />
        </div>
    </div>

</div>



@endsection