@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Actualizar Precios Desactualizados
                    </h1>
                </div>
            </div>


            <form class="forms-sample" method="POST" action="{{route('clientes.guardarPreciosCuotasVencidas', $parcela->id_parcela)}}">
                @csrf
                <div class="form-group">
                    <label for="nombre">Nuevo Precio Cuotas<span class="text-danger">*</span></label>
                    <br>
                    <input type="number" class="form-control" name="preciosCuotasVencidas" id="preciosCuotasVencidas"
                        placeholder="Ingrese el nuevo precio para las cuotas" autofocus value="{{old('preciosCuotasVencidas')}}" required>
                </div>

                <div class="form-group">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Nro Cuota</th>
                                <th scope="col">Precio Cuota Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cuotasPrecioVencido as $cuotaPrecioVencido)
                            <tr>
                                <td>{{$cuotaPrecioVencido->numero_cuota}}</td>
                                <td>{{$cuotaPrecioVencido->total_estimado_a_pagar}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                <a href="{{route('clientes.estadoCuotas', $parcela->id_parcela)}}" class="btn btn-danger form-control">Cancelar</a>
            </form>

            <x-alertas />
        </div>
    </div>

</div>



@endsection