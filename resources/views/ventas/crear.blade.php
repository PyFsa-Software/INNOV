@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')


<div class="main-panel">

    <div class="content-wrapper  d-flex justify-content-center">

        <div class="col-md-6 grid-margin transparent">

            <div class="app-title mb-5">

                <div>
                    <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Agregar Venta
                    </h1>
                </div>
            </div>


            <form class="forms-sample" method="POST" action="{{route('ventas.guardar')}}">
                @csrf
                <div class="form-group">
                    <label for="id_cliente">Cliente</label>
                    <select name="id_cliente" id="id_cliente" class="form-control" autofocus>
                        <option value="" disabled selected>Seleccione un cliente</option>
                        <option value="1">Marcos Franco (43711063)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_parcela">Parcela</label>
                    <select name="id_parcela" id="id_parcela" class="form-control">
                        <option value="" disabled selected>Seleccione una parcela</option>
                        <option value="1">Parcela 1 - Lote 1</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cuotas">Cantidad de cuotas</label>
                    <input type="text" class="form-control" name="cuotas" id="cuotas"
                        placeholder="Ingrese la cantidad de cuotas para la venta" value="{{old('cuotas')}}">
                </div>

                <div class="form-group">
                    <label for="fecha_desde">Fecha Desde (Detalle Plan)</label>
                    <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" disabled>
                </div>

                <div class="form-group">
                    <label for="fecha_hasta">Fecha Hasta (Detalle Plan)</label>
                    <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" disabled>
                </div>

                <div class="form-group">
                    <label for="valor_cuota">Valor Cuota</label>
                    <input type="number" class="form-control" name="valor_cuota" id="valor_cuota" disabled>
                </div>


                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
                <a href="{{route('ventas.index')}}" class="btn btn-danger form-control">Cancelar</a>
            </form>

            <x-alerta />
        </div>
    </div>

</div>



@endsection