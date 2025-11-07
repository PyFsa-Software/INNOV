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
    {{-- add monedaDePago --}}
    <div class="form-group">
        <label for="moneda_pago">Moneda de Pago: </label>
        <select class="form-control" name="moneda_pago" id="moneda_pago" wire:model="monedaPago" required>
            <option value="" disabled>Seleccione una moneda</option>
            @foreach ($monedasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($monedaPago === $key) selected @endif>
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

    <div class="form-group">
        <div class="form-group">
            <label for="leyenda ">Leyenda: </label>
            <input type="text" class="form-control" name="leyenda" id="leyenda" wire:model="leyenda"
                value="{{ old('leyenda', $leyenda) }}">
        </div>
    </div>

    <!-- Nueva sección para configuración de fechas -->
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="mismaFechaParaTodas" 
                   wire:model="mismaFechaParaTodas">
            <label class="custom-control-label" for="mismaFechaParaTodas">
                <strong>Todas las cuotas con la misma fecha estimada a pagar</strong>
            </label>
        </div>
        <small class="form-text text-muted">
            Si marca esta opción, todas las cuotas tendrán la misma fecha estimada a pagar dentro del mismo mes.
        </small>
    </div>

    @if($mismaFechaParaTodas)
    <div class="form-group">
        <label for="fechaUnica">Fecha Estimada a Pagar (opcional):</label>
        <input type="date" class="form-control" name="fechaUnica" id="fechaUnica" 
               wire:model="fechaUnica">
        <small class="form-text text-muted">
            Si no especifica una fecha, se usará el día 15 del mes actual ({{ \Carbon\Carbon::now()->format('Y-m-15') }}).
        </small>
    </div>
    @endif

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

    <a href="{{ route('clientes.estadoCuotas', $parcela->parcela->id_parcela) }}"
        class="btn btn-danger form-control">Cancelar</a>
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
