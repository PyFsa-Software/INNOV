<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    <div class="form-group" wire:ignore>
        <label for="anioSeleccionado">Seleccione un Año: </label>
        <select name="anioSeleccionado" id="anioSeleccionado" class="form-control"
            wire:model.debounce.500ms="anioSeleccionado">
            <option value="" disabled selected>Seleccione un año</option>
            {{-- <option value="1">Seleccione un año</option>
            <option value="2">Seleccione un año</option> --}}


            @foreach ($anios as $anio)
            <option value="{{$anio}}">{{$anio}}</option>
            @endforeach

        </select>
    </div>
    <div class="form-group" wire:ignore>
        <label for="mesSeleccionado">Seleccione un Mes: </label>
        <select name="mesSeleccionado" id="mesSeleccionado" class="form-control"
            wire:model.debounce.500ms="mesSeleccionado">
            <option value="" disabled selected>Seleccione un mes</option>
            {{-- <option value="1">Seleccione un año</option>
            <option value="2">Seleccione un año</option> --}}
            @foreach ($meses as $key => $mes)
            <option value="{{$key}}">{{$mes}}</option>
            @endforeach

        </select>
    </div>


    <div wire:loading>
        Cargando...
    </div>


    <button class="btn btn-primary mr-2 mb-2 form-control" type="submit" wire:loading.attr="disabled" {{$isDisabled
        ? 'disabled' : '' }}>Generar
        Planilla</button>

    <x-alertas />
</form>