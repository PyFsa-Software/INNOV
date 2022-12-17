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

                        @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_persona }}" @selected(old('id_cliente')==$cliente->id_persona)>
                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                            ({{ $cliente->dni }})
                        </option>
                        @endforeach

                        {{-- <option value="1">Marcos Franco (43711063)</option> --}}
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_parcela">Parcela</label>
                    <select name="id_parcela" id="id_parcela" class="form-control">
                        <option value="" disabled selected>Seleccione una parcela</option>
                        @foreach ($parcelas as $parcela)
                        <option value="{{ $parcela->id_parcela }}" @selected(old('id_parcela')==$parcela->id_parcela)>
                            {{ $parcela->superficie_parcela }}
                            (Lote: {{ $parcela->lote->nombre_lote }})
                            (Bolsas Cemento: {{ $parcela->cantidad_bolsas }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="cuotas">Cantidad de cuotas</label>
                    <input type="number" class="form-control" name="cuotas" id="cuotas"
                        placeholder="Ingrese la cantidad de cuotas para la venta" value="{{old('cuotas')}}">
                </div>

                <div class="form-group">
                    <label for="precio_total_terreno">Precio Terreno</label>
                    <input type="text" class="form-control" name="precio_total_terreno" id="precio_total_terreno"
                        placeholder="Ingrese el precio total del terreno" value="{{old('precio_total_terreno')}}"
                        disabled>
                </div>

                <div class="form-group">
                    <label for="precio_total_entrega">Valor Entrega</label>
                    <input type="number" class="form-control" name="precio_total_entrega" id="precio_total_entrega"
                        placeholder="Ingrese el precio total de la entrega del terreno"
                        value="{{old('precio_total_entrega')}}">
                </div>


                <div class="form-group">
                    <label for="promedio_cemento">Promedio Cemento</label>
                    <input type="number" class="form-control" name="promedio_cemento" id="promedio_cemento"
                        placeholder="Ingrese el precio total de la entrega del terreno"
                        value="{{old('promedio_cemento')}}">
                    <b class="mt-4">
                        {{$promedioCemento->fecha_formateado}}: Promedio
                        ${{$promedioCemento->precio_promedio}}
                    </b>
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
                    <label for="cuota_mensual_bolsas_cemento">Cuota Mensual Bolsas Cemento</label>
                    <input type="text" class="form-control" name="cuota_mensual_bolsas_cemento"
                        id="cuota_mensual_bolsas_cemento" disabled>
                </div>
                <div class="form-group">
                    <label for="valor_cuota">Valor Cuota</label>
                    <input type="number" class="form-control" name="valor_cuota" id="valor_cuota" disabled>
                </div>

                <div class="form-group">
                    <label for="valorTotalFinanciar">Valor Total A Financiar</label>
                    <input type="number" class="form-control" name="valorTotalFinanciar" id="valorTotalFinanciar"
                        disabled>
                </div>


                <button type="submit" class="btn btn-primary mr-2 mb-2 form-control"
                    id="btnGuardarVenta">Guardar</button>
                {{-- <a href="{{route('ventas.index')}}" class="btn btn-danger form-control">Cancelar</a> --}}
            </form>

            <div id="alertaVenta">

            </div>

            <x-alerta />
        </div>
    </div>

</div>



@endsection