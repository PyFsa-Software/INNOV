<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    @method('PUT')
    @if ($diferenciasDias > 0)
        <div class="form-group">
            <div class="alert alert-danger alert-dismissible fade show mt-2">
                <strong>La cuota esta vencida, se debio haber abonado hace {{ $diferenciasDias }} días.</strong>
            </div>
        </div>
    @endif
    <div class="form-group">
        <label for="numero_cuota">Numero Cuota: </label>
        <input type="number" class="form-control" name="numero_cuota" id="numero_cuota"
            value="{{ old('numero_cuota', $cuota->numero_cuota) }}" disabled>
    </div>
    <div class="form-group">
        <label for="fecha_maxima_a_pagar">Fecha Maxima a Pagar: </label>
        <input type="date" class="form-control" name="fecha_maxima_a_pagar" id="fecha_maxima_a_pagar"
            value="{{ old('fecha_maxima_a_pagar', $cuota->fecha_maxima_a_pagar) }}" disabled>
    </div>
    <div class="form-group">
        <label for="total_estimado_a_pagar">Total Estimado a Pagar: </label>
        <input type="number" class="form-control" name="total_estimado_a_pagar" id="total_estimado_a_pagar"
            value="{{ old('total_estimado_a_pagar', $cuota->total_estimado_a_pagar) }}" disabled>
    </div>

    <div class="form-group">
        <label for="total_abonado">Formas de Pago: </label>
        {{-- <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago">
            <option value="" selected disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select> --}}
        <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago">
            <option value="" disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($formaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>



    <div class="form-group">
        <label for="total_intereses">Interes: </label>
        <input type="number" class="form-control" name="total_intereses" id="total_intereses"
            value="{{ old('total_intereses', $totalIntereses) }}" wire:model.debounce.500ms="totalIntereses"
            wire:keyup='calcularAbono'>
    </div>

    {{-- <div class="form-group">
        <label for="incrementoInteres">Incremento: </label>
        <input type="number" class="form-control" name="incrementoInteres" id="incrementoInteres"
            value="{{old('incrementoInteres', $incrementoInteres)}}" wire:model="incrementoInteres" disabled>
    </div> --}}

    <div class="form-group">
        <label for="total_pago">Total a Abonar: </label>
        <input type="number" class="form-control" name="total_pago" id="total_pago"
            value="{{ old('total_pago', $totalAbonar) }}" disabled>
    </div>

    <div wire:loading>
        Calculando abono..
    </div>


    <button class="btn btn-primary mr-2 mb-2 form-control" type="button" {{ $isDisabled ? 'disabled' : '' }}
        data-toggle="modal" data-target="#cobroCuota">Realizar
        Cobro</button>

    <a href="{{ url()->previous() }}" class="btn btn-danger form-control">Cancelar</a>
    <x-alertas />


    <!-- Modal -->
    <div class="modal fade" id="cobroCuota" tabindex="-1" role="dialog" aria-labelledby="cobroCuotaLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cobroCuotaLabel">¿Realizar el cobro de esta cuota?</h5>
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
