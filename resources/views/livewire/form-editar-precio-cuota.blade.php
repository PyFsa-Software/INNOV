

<form class="forms-sample" wire:submit.prevent="submit">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="precio">Precio Actual de Cuota</label>
        <input type="text" class="form-control" name="precio" id="precio"
            placeholder="Ingrese el Nuevo Precio" autofocus
            value="{{old('precio', $cuota->total_estimado_a_pagar)}}" disabled>
    </div>

    <div class="form-group">
        <label for="promedioCementoNuevo">Promedio Cemento Nuevo: </label>
        <input type="number" class="form-control" name="promedioCementoNuevo" id="promedioCementoNuevo"
            placeholder="Ingrese el promedio del cemento para calcular la actualización"
            wire:model="promedioCementoNuevo" wire:keyup.debounce.500ms="calcularActualizacion">
    </div>
    <div class="form-group">
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
                    <th scope="row">{{getMesEnLetraConAnio($promedioCemento->fecha)}}</th>
                    <td>{{$promedioCemento->precio_bercomat}}</td>
                    <td>{{$promedioCemento->precio_sancayetano}}</td>
                    <td>{{$promedioCemento->precio_rio_colorado}}</td>
                    <td>{{$promedioCemento->precio_promedio}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <br>
        {{-- <h6 class="text-success"><b>Promedio de 6 meses: {{$promedio6Meses}}</b></h6> --}}
        @if ( !fechaIgualMesActual($listaPromedioCemento[0]->fecha))
        <hr>
        <h6><b class="text-danger mt-2">Aún no se han encargado los precios del cemento del mes actual!.</b></h6>
        @endif
    </div>

    <div class="form-group">
        <label for="totalAbonarProximosMeses">Precio Nuevo de Cuota  </label>
        <input type="text" class="form-control" name="totalAbonarProximosMeses" id="totalAbonarProximosMeses"
            value="{{$totalAbonarProximosMeses}}" disabled>
    </div>
    <div wire:loading>
        Calculando abono..
    </div>

    <button type="submit" class="btn btn-primary mr-2 mb-2 form-control">Guardar</button>
    <a href="{{route('clientes.estadoCuotas', $idParcela)}}" class="btn btn-danger form-control">Cancelar</a>
</form>
