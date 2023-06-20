<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    @method('PUT')

    {{-- <div class="form-group">
        <label for="numero_cuota">Numero Cuota: </label>
        <input type="number" class="form-control" name="numero_cuota" id="numero_cuota" value="" disabled>
    </div> --}}
    <div class="form-group">
        <label for="fecha_maxima_a_pagar">Fecha Maxima a Pagar: </label>
        <input type="date" class="form-control" name="fecha_maxima_a_pagar" id="fecha_maxima_a_pagar" value=""
            disabled>
    </div>
    <div class="form-group">
        <label for="total_estimado_a_pagar">Total Estimado a Pagar: </label>
        <input type="number" class="form-control" name="total_estimado_a_pagar" id="total_estimado_a_pagar"
            value="" disabled>
    </div>

    <div class="form-group">
        <label for="total_intereses">Interes: </label>
        <input type="number" class="form-control" name="total_intereses" id="total_intereses" value=""
            wire:model.debounce.500ms="totalIntereses" wire:keyup='calcularAbono'>
    </div>

    {{-- <div class="form-group">
        <label for="incrementoInteres">Incremento: </label>
        <input type="number" class="form-control" name="incrementoInteres" id="incrementoInteres"
            value="{{old('incrementoInteres', $incrementoInteres)}}" wire:model="incrementoInteres" disabled>
    </div> --}}

    <div class="form-group">
        <label for="total_pago">Total a Abonar: </label>
        <input type="number" class="form-control" name="total_pago" id="total_pago" value="" disabled>
    </div>

    <div wire:loading>
        Calculando abono..
    </div>


    <button class="btn btn-primary mr-2 mb-2 form-control" type="button" data-toggle="modal"
        data-target="#cobroCuota">Realizar
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
