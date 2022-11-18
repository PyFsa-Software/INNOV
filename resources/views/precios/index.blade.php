@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-12 grid-margin transparent">

            <div class="app-title mb-5">
                @if (count($precios) === 0)
                <h1 class="text-center text-danger"><i class="fa fa-desktop fa-lg"></i> Aun no hay precios cargados
                </h1>
                <a href="{{route('precios.crear')}}" class="btn btn-success">Agregar Precio</a>
                @else

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado de Precios
                    </h1>
                    <a href="{{route('precios.crear')}}" class="btn btn-success">Agregar Precio</a>
                </div>

                <table id="myTable" class="table">

                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>SUPERFICIE</th>
                            <th>CANTIDAD DE MANZANAS</th>
                            <th>UBICACION</th>
                            <th>MODIFICAR</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>

                    <tbody>


                        <td>asdasd</td>
                        <td>asdas</td>

                        <td>asdasd</td>
                        <td>asdasd</td>

                        <td>asdas</td>
                        <td>asdas</td>

                    </tbody>
                </table>
                @endif

            </div>

        </div>
    </div>

</div>



@endsection