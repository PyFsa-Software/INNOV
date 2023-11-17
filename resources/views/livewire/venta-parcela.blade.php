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
        <label for="cuotas">Plan de cuotas</label>
        <input type="number" class="form-control" name="cuotas" id="cuotas"
            placeholder="Ingrese la cantidad de cuotas para la venta" value="{{ old('cuotas') }}"
            wire:model="cantidadCuotas" wire:keyup.debounce.500ms="calcularPlan">
    </div>

    <div class="form-group">
        <label for="precio_total_terreno">Precio Terreno</label>
        <input type="text" class="form-control" name="precio_total_terreno" id="precio_total_terreno"
            placeholder="Ingrese el precio total del terreno" value="{{ old('precio_total_terreno') }}" disabled
            wire:model="precioTotalTerreno">
    </div>

    {{-- <div class="form-group">
        <label for="precio_total_entrega">Valor Entrega</label>
        <input type="number" class="form-control" name="precio_total_entrega" id="precio_total_entrega"
            placeholder="Ingrese el precio total de la entrega del terreno" value="{{old('precio_total_entrega')}}"
            wire:model="precioTotalEntrega" wire:keyup.debounce.500ms="calcularPlan">
    </div> --}}


    <div class="form-group">
        <label for="promedio_cemento">Promedio Cemento</label>
        <input type="number" class="form-control" name="promedio_cemento" id="promedio_cemento"
            placeholder="Ingrese el precio total de la entrega del terreno" value="{{ old('promedio_cemento') }}"
            wire:model="promedioCemento" wire:keyup.debounce.500ms="calcularPlan">
        <b class="mt-4">
            {{ $promedioCementoDelMes?->fecha_formateado }}: Promedio
            ${{ $promedioCementoDelMes?->precio_promedio }}
        </b>
    </div>

    <div class="form-group">
        <label for="fecha_desde">Fecha Desde (Detalle Plan)</label>
        <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" disabled
            wire:model="fechaDesdeDetallePlan">
    </div>

    <div class="form-group">
        <label for="fecha_hasta">Fecha Hasta (Detalle Plan)</label>
        <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" disabled
            wire:model="fechaHastaDetallePlan">
    </div>

    <div class="form-group">
        <label for="cuota_mensual_bolsas_cemento">Cuota Mensual Bolsas Cemento</label>
        <input type="text" class="form-control" name="cuota_mensual_bolsas_cemento" id="cuota_mensual_bolsas_cemento"
            disabled wire:model="bolsasCementoMensual">
    </div>
    <div class="form-group">
        <label for="valor_cuota">Valor Cuota</label>
        <input type="number" class="form-control" name="valor_cuota" id="valor_cuota" disabled
            wire:model="valorCuotaMensual">
    </div>

    <div class="form-group">
        <label for="total_abonado">Formas de Pago: </label>
        <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago">
            <option value="" disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($formaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="importeEntrega">Importe Entrega</label>
        <input type="number" class="form-control" name="importeEntrega" id="importeEntrega"
            wire:model="importeEntrega">
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
