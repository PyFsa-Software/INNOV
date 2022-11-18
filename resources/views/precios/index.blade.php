@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">

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
                            <th>BERCOMAT</th>
                            <th>SAN CAYETANO</th>
                            <th>RIO COLORADO</th>
                            <th>PROMEDIO</th>
                            <th>FECHA</th>
                            <th>MODIFICAR</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($precios as $precio)
                        <tr>
                            <td>{{ $precio?->precio_bercomat }}</td>
                            <td>{{ $precio?->precio_sancayetano }}</td>
                            <td>{{ $precio?->precio_rio_colorado }}</td>
                            <td>{{ $precio?->precio_promedio }}</td>
                            <td>{{ $precio?->fecha }}</td>
                            <td><a href="{{ route('precios.editar', $precio->id_precio_cemento)}}"
                                    class="btn btn-warning">EDITAR</a></td>
                            <td><a href="{{route('precios.borrar', $precio->id_precio_cemento)}}"
                                    class="btn btn-danger">ELIMINAR</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

            </div>

        </div>
    </div>

</div>



@endsection