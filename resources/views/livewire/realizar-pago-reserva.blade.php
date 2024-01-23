<form class="forms-sample" method="POST" wire:submit.prevent="submit">
    {{-- @csrf --}}
    <div class="form-group">
        <x-alertas />
        <label for="montoTotal">Monto Total</label>
        <input type="text" class="form-control" name="montoTotal" id="montoTotal" value="{{ old('montoTotal') }}"
            wire:model="montoTotal" disabled>
    </div>

    <div class="form-group">
        <label for="montoActualAbonado">Abonado hasta Ahora</label>
        <input type="text" class="form-control" name="montoActualAbonado" id="montoActualAbonado"
            value="{{ old('montoActualAbonado') }}" wire:model="montoActualAbonado" disabled>
    </div>

    <div class="form-group">
        <label for="forma_pago">Formas de Pago: </label>
        <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago">
            <option value="" disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($formaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="conceptoDe">Concepto De: </label>
        <textarea class="form-control" name="conceptoDe" id="conceptoDe" cols="30" rows="3" wire:model="conceptoDe"></textarea>
    </div>

    <div class="form-group">
        <label for="importeEntrega">Importe Entrega</label>
        <input type="number" class="form-control" name="importeEntrega" id="importeEntrega" wire:model="importeEntrega"
            wire:keyup.debounce.500ms="validarImporteEntrega">
    </div>



    <div wire:loading>
        Calculando
    </div>

    <button type="button" class="btn btn-primary mr-2 mb-2 form-control" {{ $isDisabled ? 'disabled' : '' }}
        data-toggle="modal" data-target="#ventaParcela">Guardar</button>



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
