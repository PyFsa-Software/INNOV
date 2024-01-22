<form class="forms-sample" method="POST" wire:submit.prevent="submit">
    {{-- @csrf --}}
    <div class="form-group">
        <x-alertas />
        <label for="id_cliente">Cliente</label>
        <select name="id_cliente" id="id_cliente" class="form-control" autofocus wire:model="clienteCombo"
            wire:change.debounce.500ms="calcularPlan">
            <option value="" disabled selected>Seleccione un cliente</option>

            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id_persona }}" @selected(old('id_cliente') == $cliente->id_persona)>
                    {{ $cliente->nombre }} {{ $cliente->apellido }}
                    ({{ $cliente->dni }})
                </option>
            @endforeach

            {{-- <option value="1">Marcos Franco (43711063)</option> --}}
        </select>
    </div>

    <div class="form-group">
        <label for="id_parcela">Parcela</label>
        <select name="id_parcela" id="id_parcela" class="form-control" wire:model="parcelaCombo"
            wire:change.debounce.500ms="calcularPlan">
            <option value="" disabled selected>Seleccione una parcela</option>
            @foreach ($parcelas as $parcela)
                <option value="{{ $parcela->id_parcela }}" @selected(old('id_parcela') == $parcela->id_parcela)>
                    {{ $parcela->descripcion_parcela }}
                    (Lote: {{ $parcela->lote->nombre_lote }})
                    (Bolsas Cemento: {{ $parcela->cantidad_bolsas }})
                    (Manzana: {{ $parcela->manzana }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="cuotas">Monto Total</label>
        <input type="number" class="form-control" name="cuotas" id="cuotas"
            placeholder="Ingrese la cantidad de cuotas para la venta" value="{{ old('cuotas') }}"
            wire:model="cantidadCuotas" wire:keyup.debounce.500ms="calcularPlan">
    </div>

    <div class="form-group">
        <label for="forma_pago">Formas de Pago: </label>
        <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago"
            wire:keyup.debounce.500ms="calcularPlan">
            <option value="" disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($formaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="conceptoDe">Concepto De: </label>
        <textarea class="form-control" name="conceptoDe" id="conceptoDe" cols="30" rows="3" wire:model="conceptoDe"
            wire:keyup.debounce.500ms="calcularPlan"></textarea> --}}

        <select class="form-control" name="conceptoDe" id="conceptoDe" wire:model="conceptoDe"
            wire:keyup.debounce.500ms="calcularPlan">
            <option value="" selected>Seleccione un concepto</option>
            @foreach ($conceptosDe as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="importeEntrega">Importe Entrega</label>
        <input type="number" class="form-control" name="importeEntrega" id="importeEntrega" wire:model="importeEntrega"
            wire:keyup.debounce.500ms="calcularPlan">
    </div>
</form>
