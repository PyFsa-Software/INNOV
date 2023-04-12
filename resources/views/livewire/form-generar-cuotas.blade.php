<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="descripcion_parcela">Descripción Parcela: </label>
        <input type="text" class="form-control" name="descripcion_parcela" id="descripcion_parcela"
            value="{{old('descripcion_parcela', $parcela?->descripcion_parcela)}}" disabled>
    </div>
    <div class="form-group">
        <label for="superficie_parcela">Superficie Parcela (M^2): </label>
        <input type="text" class="form-control" name="superficie_parcela" id="superficie_parcela"
            value="{{old('superficie_parcela', $parcela?->superficie_parcela)}}" disabled>
    </div>
    <div class="form-group">
        <label for="cantidad_bolsas">Cantidad Bolsas Cemento: </label>
        <input type="text" class="form-control" name="cantidad_bolsas" id="cantidad_bolsas"
            value="{{old('cantidad_bolsas', $parcela?->cantidad_bolsas)}}" disabled>
    </div>
    <div class="form-group">
        <label for="manzana">Manzana: </label>
        <input type="text" class="form-control" name="manzana" id="manzana"
            value="{{old('manzana', $parcela?->manzana)}}" disabled>
    </div>
    <div class="form-group">
        <label for="cuotas">Total Cuotas: </label>
        <input type="text" class="form-control" name="cuotas" id="cuotas" value="{{old('cuotas', $venta?->cuotas)}}"
            disabled>
    </div>
    <div class="form-group">
        <label for="precio_total_terreno">Precio Total Terreno: </label>
        <input type="text" class="form-control" name="precio_total_terreno" id="precio_total_terreno"
            value="{{old('precio_total_terreno', $venta?->precio_total_terreno)}}" disabled>
    </div>
    <div class="form-group">
        <label for="cuota_mensual_bolsas_cemento">Cuota Mensual Bolsa Cemento: </label>
        <input type="text" class="form-control" name="cuota_mensual_bolsas_cemento" id="cuota_mensual_bolsas_cemento"
            value="{{old('cuota_mensual_bolsas_cemento', $venta?->cuota_mensual_bolsas_cemento)}}" disabled>
    </div>
    <div class="form-group">
        <label for="totalAbonadoMensual">Total Abonado Mensual: </label>
        <input type="text" class="form-control" name="totalAbonadoMensual" id="totalAbonadoMensual"
            value="{{old('totalAbonadoMensual', $ultimaCuota?->total_estimado_a_pagar)}}" disabled>
    </div>


    {{-- <div class="form-group">
        <label for="promedioCementoNuevo">Promedio Cemento Nuevo: </label>
        <input type="number" class="form-control" name="promedioCementoNuevo" id="promedioCementoNuevo"
            placeholder="Ingrese el promedio del cemento para calcular la actualización"
            wire:model="promedioCementoNuevo" wire:keyup.debounce.500ms="calcularActualizacion">
    </div> --}}
    <div class="form-group">
        @if (count($listaPromedioCemento) == 0)
                  <div class="alert alert-danger">No existen precios de cementos</div>  
        @else
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th scope="col">Mes</th>
                    <th scope="col">Bercomat</th>
                    <th scope="col">San Cayetano</th>
                    <th scope="col">Rio Colorado</th>
                    <th scope="col">Promedio</th>
                </tr>
            </thead>
            <tbody>
            
                @foreach ($listaPromedioCemento as $promedioCemento)

                <tr>
                    <th scope="row">{{getMesEnLetraConAnio($promedioCemento?->fecha)}}</th>
                    <td>{{$promedioCemento?->precio_bercomat}}</td>
                    <td>{{$promedioCemento?->precio_sancayetano}}</td>
                    <td>{{$promedioCemento?->precio_rio_colorado}}</td>
                    <td>{{$promedioCemento?->precio_promedio}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <br>
        <h6 class="text-success"><b>Promedio de 6 meses: {{$promedio6Meses}}</b></h6>
        @if ( !fechaIgualMesActual($listaPromedioCemento[0]?->fecha))
        <hr>
        <h6><b class="text-danger mt-2">Aún no se han encargado los precios del cemento del mes actual!.</b></h6>
        @endif
    </div>
    @endif

    <div class="form-group">
        <label for="totalAbonarProximosMeses">Total Abonar Proximo 6 Meses: </label>
        <input type="text" class="form-control" name="totalAbonarProximosMeses" id="totalAbonarProximosMeses"
            value="{{$totalAbonarProximosMeses}}" wire:model="totalAbonarProximosMeses" >
    </div>
    <div wire:loading>
        Calculando abono..
    </div>


    <button class="btn btn-primary mr-2 mb-2 form-control" type="button" {{$isDisabled ? 'disabled' : '' }}
        data-toggle="modal" data-target="#actualizarPrecios">Generar Cuotas</button>

    <a href="{{ route('clientes.estado', $venta?->id_cliente) }}" class="btn btn-danger form-control">Cancelar</a>
    <x-alertas />


    <!-- Modal -?->
    <div class="modal fade" id="actualizarPrecios" tabindex="-1" role="dialog" aria-labelledby="actualizarPreciosLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="actualizarPreciosLabel">¿Generar las cuotas de esta venta?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Una vez realizada esta acción ya no se podra modificar.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Continuar</button>
                </div>
            </div>
        </div>
    </div>

</form>