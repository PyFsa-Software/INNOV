<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    @method('PUT')
    @if ($diferenciasDias > 0)
    <div class="form-group">
        <div class="alert alert-danger alert-dismissible fade show mt-2">
            <strong>La cuota esta vencida, se debio haber abonado hace {{$diferenciasDias}} días.</strong>
        </div>
    </div>
    @endif
    <div class="form-group">
        <label for="numero_cuota">Numero Cuota: </label>
        <input type="number" class="form-control" name="numero_cuota" id="numero_cuota"
            value="{{old('numero_cuota', $cuota->numero_cuota)}}" disabled>
    </div>
    <div class="form-group">
        <label for="fecha_maxima_a_pagar">Fecha Maxima a Pagar: </label>
        <input type="date" class="form-control" name="fecha_maxima_a_pagar" id="fecha_maxima_a_pagar"
            value="{{old('fecha_maxima_a_pagar', $cuota->fecha_maxima_a_pagar)}}" disabled>
    </div>
    <div class="form-group">
        <label for="total_estimado_a_pagar">Total Estimado a Pagar: </label>
        <input type="number" class="form-control" name="total_estimado_a_pagar" id="total_estimado_a_pagar"
            value="{{old('total_estimado_a_pagar', $cuota->total_estimado_a_pagar)}}" disabled>
    </div>

    <div class="form-group">
        <label for="total_intereses">Interes: </label>
        <input type="number" class="form-control" name="total_intereses" id="total_intereses"
            value="{{old('total_intereses', $totalIntereses)}}" wire:model.debounce.500ms="totalIntereses"
            wire:keyup='calcularAbono'>
    </div>

    <div class="form-group">
        <label for="incrementoInteres">Incremento: </label>
        <input type="number" class="form-control" name="incrementoInteres" id="incrementoInteres"
            value="{{old('incrementoInteres', $incrementoInteres)}}" wire:model="incrementoInteres" disabled>
    </div>

    <div class="form-group">
        <label for="total_pago">Total a Abonar: </label>
        <input type="number" class="form-control" name="total_pago" id="total_pago"
            value="{{old('total_pago', $totalAbonar)}}" disabled>
    </div>

    <div wire:loading>
        Calculando abono..
    </div>


    <button type="submit" class="btn btn-primary mr-2 mb-2 form-control" {{$isDisabled ? 'disabled' : '' }}>Realizar
        Cobro</button>

    <a href="{{ url()->previous() }}" class="btn btn-danger form-control">Cancelar</a>
    <x-alerta />
</form>