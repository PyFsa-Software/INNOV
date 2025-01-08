<form class="forms-sample" method="POST" wire:submit.prevent="submit">
    <div class="form-group">
        <label for="descripcion_comprobante">Descripción del Comprobante <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="descripcion_comprobante" id="descripcion_comprobante"
            placeholder="Ingrese la descripción del comprobante" value="{{ old('descripcion_comprobante') }}"
            wire:model="descripcionComprobante" autofocus>
    </div>

    <div class="form-group">
        <label for="id_cliente">Cliente <span class="text-danger">(Si no selecciona un cliente, se generará un
                comprobante sin cliente.)</span></label>
        <br>
        <select id="clienteCombo" class="form-control select2" wire:model="clienteCombo">
            <option value="">Seleccione un cliente</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id_persona }}">
                    {{ $cliente->nombre }} {{ $cliente->apellido }}
                </option>
            @endforeach
        </select>


    </div>

    {{-- if  $clienteCombo == '' show next div --}}

    @if ($clienteCombo == '')
        <div class="form-group">
            <label for="sr_sra">Sr/Sra: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="sr_sra" id="sr_sra" placeholder="Ingrese el Sr/Sra"
                value="{{ old('sr_sra') }}" wire:model="srSra">
        </div>
        <div class="form-group">
            <label for="dni">Dni: <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="dni" id="dni" placeholder="Ingrese el DNI"
                value="{{ old('dni') }}" wire:model="dni">
        </div>
        <div class="form-group">
            <label for="domicilio">Domicilio del Alquiler: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="domicilio" id="domicilio"
                placeholder="Ingrese el domicilio" value="{{ old('domicilio') }}" wire:model="domicilio">
        </div>

        <div class="form-group">
            <label for="domicilioAlquiler">Alquiler: </label>
            {{-- combo de FAMILIAR o COMERCIAL --}}
            <select class="form-control" name="domicilioAlquiler" id="domicilioAlquiler" wire:model="domicilioAlquiler">
                <option value="" disabled>Seleccione una opción</option>
                {{-- domiciliosAlquiler --}}
                @foreach ($domiciliosAlquiler as $key => $value)
                    <option value="{{ $key }}" @if ($domicilioAlquiler === $key) selected @endif>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- add combo ventas with name ventasClienteCombo --}}
    <div class="form-group">
        <label for="id_venta">Venta</label>
        <br>
        <select name="id_venta" id="id_venta" class="form-control" autofocus wire:model="ventasClienteCombo">
            <option value="" selected>Ninguno</option>
            @if ($ventasCliente)

                @foreach ($ventasCliente as $venta)
                    <option value="{{ $venta->id_venta }}" @selected(old('id_venta') == $venta->id_venta)>
                        Parcela ({{ $venta->parcela->descripcion_parcela }}) Manzana: ({{ $venta->parcela->manzana }})
                        Lote: ({{ $venta->parcela->lote->nombre_lote }})
                    </option>
                @endforeach

            @endif
        </select>
    </div>
    <div class="form-group">
        <label for="forma_pago">Forma de Pago: <span class="text-danger">*</span></label>
        <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago">
            <option value="" disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($formaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>
    {{-- add combo moneda_pago --}}
    <div class="form-group">
        <label for="moneda_pago">Moneda de Pago: <span class="text-danger">*</span></label>
        <select class="form-control" name="moneda_pago" id="moneda_pago" wire:model="monedaPago">
            <option value="" disabled>Seleccione una moneda de pago</option>
            @foreach ($monedasPagos as $key => $value)
                <option value="{{ $key }}" @if ($monedaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="importeTotal">Importe Total: <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="importeTotal" id="importeTotal"
            placeholder="Ingrese el importe total" value="{{ old('importeTotal') }}" wire:model="importeTotal">
    </div>

    <div class="form-group">
        <label for="conceptoDe">Concepto De: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="conceptoDe" id="conceptoDe" cols="30" rows="3"
            wire:model="conceptoDe"></textarea>
    </div>


    <div wire:loading>
        Calculando
    </div>
    <div class="form-group">
        <x-alertas />
    </div>

    <button type="button" class="btn btn-primary mr-2 mb-2 form-control" {{ $isDisabled ? 'disabled' : '' }}
        data-toggle="modal" data-target="#ventaParcela">Guardar</button>
    {{-- volver atras --}}

    <a href="{{ route('comprobantes.index') }}" class="btn btn-danger mr-2 mb-2 form-control">Volver</a>

    <!-- Modal -->
    <div class="modal fade" id="ventaParcela" tabindex="-1" role="dialog" aria-labelledby="ventaParcelaLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ventaParcelaLabel">Advertencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Desea guardar esta venta?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</form>
