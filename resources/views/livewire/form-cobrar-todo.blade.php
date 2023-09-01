<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="cantidad_cuotas_generadas">Cantidad de Cuotas Generadas: </label>
        <input type="text" class="form-control" name="cantidad_cuotas_generadas" id="cantidad_cuotas_generadas"
            value="{{ $cuotasGeneradas }}" disabled>
    </div>
    <div class="form-group">
        <label for="cantidad_cuotas_pagadas">Cantidad de Cuotas Pagadas: </label>
        <input type="text" class="form-control" name="cantidad_cuotas_pagadas" id="cantidad_cuotas_pagadas"
            value="{{ $cuotasPagadas }}" disabled>
    </div>
    <div class="form-group">
        <label for="cantidad_cuotas_plan">Cantidad de Cuotas del Plan: </label>
        <input type="text" class="form-control" name="cantidad_cuotas_plan" id="cantidad_cuotas_plan"
            value="{{ $venta->cuotas }}" disabled>
    </div>
    <div class="form-group">
        <label for="precio_cuota">Precio actual de la Cuota: </label>
        <input type="number" class="form-control" disabled required name="precio_cuota" id="precio_cuota"
            value="{{ $precioActual }}">
    </div>
    <div class="form-group">
        <label for="total_cuotas_a_pagar">Ingrese el total de cuotas a pagar: </label>
        <input type="number" class="form-control" required name="total_cuotas_a_pagar" id="total_cuotas_a_pagar"
            wire:model.debounce="cantidadCuotasPagar">
        {{-- @error('cantidadCuotasPagar')
            <div class="text-danger">{{ $message }}</div>
        @enderror --}}
    </div>

    <div class="form-group">
        <label for="forma_pago">Forma de Pago: </label>
        <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago" required>
            <option value="" disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($formaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="conceptoDe">Concepto De: </label>
        <select class="form-control" name="conceptoDe" id="conceptoDe" wire:model="conceptoDe" required>
            <option value="" selected disabled>Seleccione un concepto</option>
            @foreach ($conceptoDeOpcionesSelect as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading>
        Calculando...
    </div>

    @if ($message !== '')
        <div class="form-group">
            <div class="alert alert-warning">
                {{ $message }}
            </div>
        </div>
    @endif

    <button class="btn btn-primary mr-2 mb-2 form-control" type="button" data-toggle="modal" data-target="#cobroCuota"
        {{ $isDisabled ? 'disabled' : '' }}>
        Realizar Cobro
    </button>

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
