<form class="forms-sample" method="POST" action="{{route('parcelas.crear')}}" wire:submit.prevent="submit">
    @csrf
    <div class="form-group">
        <label for="lote">Lote</label>
        <select class="form-control" name="lote" id="lote" wire:model="idLote">
            <option value="0" selected disabled>Seleccione una opcion</option>
            @foreach ($lotes as $lote)
            <option value="{{$lote->id_lote}}" @selected(old('lote')==$lote->id_lote)>{{$lote->nombre_lote}}
            </option>
            @endforeach
        </select>
        @error('idLote')
        <div class="text-danger"><b>{{ $message }}</b></div>
        @enderror
    </div>
    <div class="form-group">
        <label for="manzana">Manzana</label>
        <input type="text" class="form-control" name="manzana" id="manzana" placeholder="Ingrese la manzana"
            value="{{old('manzana')}}" wire:model.debounce.500ms="manzana" wire:model.uppercase>
        @error('manzana')
        <div class="text-danger"><b>{{ $message }}</b></div>
        @enderror
    </div>
    <div class="form-group">
        <label for="cantidad_parcelas">Cantidad de Parcelas</label>
        <input type="nummber" class="form-control" name="cantidad_parcelas" id="cantidad_parcelas"
            placeholder="Ingrese las cantidades de parcelas a generar" value="{{old('cantidad_parcelas')}}"
            wire:model.debounce.1000ms="cantidadParcelas">
        @error('cantidadParcelas')
        <div class="text-danger"><b>{{ $message }}</b></div>
        @enderror
    </div>

    {{--
    <x-alertas /> --}}
    <br>
    <div wire:loading>
        Procesando
    </div>
    <div style="border: 1px dashed black; padding: 1em;" class="mb-4">
        <b>Esta sección se genera automaticamente segun la cantidad de parcelas que desea crear</b>
        <hr>
        @if ($cantidadParcelas > 0)
        @foreach ($inputs as $index => $input)

        @php
        $numeroParcela = $index + 1;
        @endphp

        <b class="text-danger">Parcela N°: {{$numeroParcela}}</b>
        <div class="form-group">
            <label for="descripcion_parcela_{{ $index }}" class="control-label">Descripción de la parcela:</label>
            <input type="text" id="descripcion_parcela_{{ $index }}" name="descripcion_parcela[]"
                class="form-control @error('inputs.'.$index.'.descripcion_parcela') is-invalid @enderror"
                wire:model="inputs.{{ $index }}.descripcion_parcela" placeholder="Ingrese una descripción">
            @error('inputs.'.$index.'.descripcion_parcela')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="superficie_parcela_{{ $index }}" class="control-label">Superficie de la parcela:</label>
            <input type="text" id="superficie_parcela_{{ $index }}" name="superficie_parcela[]"
                class="form-control @error('inputs.'.$index.'.superficie_parcela') is-invalid @enderror"
                wire:model="inputs.{{ $index }}.superficie_parcela" placeholder="Ingrese la superficie">
            @error('inputs.'.$index.'.superficie_parcela')
            <div class="text-danger"><b>{{ $message }}</b></div>
            @enderror
        </div>
        <div class="form-group">
            <label for="manzana_{{ $index }}" class="control-label">Manzana:</label>
            <input type="text" id="manzana_{{ $index }}" name="manzana[]"
                class="form-control @error('inputs.'.$index.'.manzana') is-invalid @enderror"
                wire:model="inputs.{{ $index }}.manzana" placeholder="Ingrese la manzana" disabled>
            @error('inputs.'.$index.'.manzana')
            <div class="text-danger"><b>{{ $message }}</b></div>
            @enderror
        </div>
        <div class="form-group">
            <label for="cantidad_bolsas_{{ $index }}" class="control-label">Cantidad de bolsas:</label>
            <input type="text" id="cantidad_bolsas_{{ $index }}" name="cantidad_bolsas[]"
                class="form-control @error('inputs.'.$index.'.cantidad_bolsas') is-invalid @enderror"
                wire:model="inputs.{{ $index }}.cantidad_bolsas" placeholder="Ingrese la cantidad">
            @error('inputs.'.$index.'.cantidad_bolsas')
            <div class="text-danger"><b>{{ $message }}</b></div>
            @enderror
        </div>
        <div class="form-group">
            <label for="ancho_{{ $index }}" class="control-label">Ancho:</label>
            <input type="text" id="ancho_{{ $index }}" name="ancho[]"
                class="form-control @error('inputs.'.$index.'.ancho') is-invalid @enderror"
                wire:model="inputs.{{ $index }}.ancho" placeholder="Ingrese el ancho">
            @error('inputs.'.$index.'.ancho')
            <div class="text-danger"><b>{{ $message }}</b></div>
            @enderror
        </div>
        <div class="form-group">
            <label for="largo_{{ $index }}" class="control-label">Largo:</label>
            <input type="text" id="largo_{{ $index }}" name="largo[]"
                class="form-control @error('inputs.'.$index.'.largo') is-invalid @enderror"
                wire:model="inputs.{{ $index }}.largo" placeholder="Ingrese el largo">
            @error('inputs.'.$index.'.largo')
            <div class="text-danger"><b>{{ $message }}</b></div>
            @enderror
        </div>
        <hr class="mb-4">
        @endforeach
        @endif
    </div>

    <button type="button" data-target="#crearMultiplesParcelas" data-toggle="modal" class="btn btn-primary mr-2 mb-2 form-control" {{ $isDisabled ? 'disabled' : ''
        }}>Guardar</button>
    <a href="{{route('parcelas.index')}}" class="btn btn-danger form-control">Cancelar</a>

        <!-- Modal -->
        <div class="modal fade" id="crearMultiplesParcelas" tabindex="-1" role="dialog" aria-labelledby="crearMultiplesParcelasLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearMultiplesParcelasLabel">¿Generar múltiples parcelas para este lote?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Continuar</button>
                </div>
            </div>
        </div>
    </div>
<script>
    // Obtener el botón "Continuar"
const continuarBtn = document.querySelector('#crearMultiplesParcelas button[type="submit"]');

// Escuchar el evento "click" en el botón "Continuar"
continuarBtn.addEventListener('click', () => {
    // Obtener el modal
    const modal = document.querySelector('#crearMultiplesParcelas');

    // Cerrar el modal
    $(modal).modal('hide');
});
</script>
</form>