<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    @method('PUT')

    {{-- <div class="form-group">
        <label for="numero_cuota">Numero Cuota: </label>
        <input type="number" class="form-control" name="numero_cuota" id="numero_cuota" value="" disabled>
    </div> --}}
    <div class="form-group">
        <label for="cantidad_cuotas_generadas">Cantidad de Cuotas Generadas: </label>
        <input type="text" class="form-control" name="cantidad_cuotas_generadas" id="cantidad_cuotas_generadas"
            value={{ $cuotasGeneradas }} disabled>
    </div>
    <div class="form-group">
        <label for="cantidad_cuotas_pagadas">Cantidad de Cuotas Pagadas: </label>
        <input type="text" class="form-control" name="cantidad_cuotas_pagadas" id="cantidad_cuotas_pagadas"
            value={{ $cuotasPagadas }} disabled>
    </div>
    <div class="form-group">
        <label for="cantidad_cuotas_plan">Cantidad de Cuotas del Plan: </label>
        <input type="text" class="form-control" name="cantidad_cuotas_plan" id="cantidad_cuotas_plan"
            value={{ $venta->cuotas }} disabled>
    </div>
    <div class="form-group">
        <label for="total_cuotas_a_pagar">Ingrese el total de cuotas a pagar: </label>
        <input type="number" class="form-control" required name="total_cuotas_a_pagar" id="total_cuotas_a_pagar"
            value="" wire:model="validaciones">
    </div>

    <div class="form-group">
        <label for="precio_cuota">Precio de la cuota: </label>
        <input type="number" class="form-control" required name="precio_cuota" id="precio_cuota" value=""
            wire:model="precioCuotas">
    </div>

    {{-- <div class="form-group">
        <label for="total_intereses">Interes: </label>
        <input type="number" class="form-control" name="total_intereses" id="total_intereses" value=""
            wire:model.debounce.500ms="totalIntereses" wire:keyup='calcularAbono'>
    </div> --}}

    {{-- <div class="form-group">
        <label for="incrementoInteres">Incremento: </label>
        <input type="number" class="form-control" name="incrementoInteres" id="incrementoInteres"
            value="{{old('incrementoInteres', $incrementoInteres)}}" wire:model="incrementoInteres" disabled>
    </div> --}}

    {{-- <div class="form-group">
        <label for="total_pago">Total de cuotas que se van a pagar: </label>
        <input type="number" class="form-control" name="total_pago" id="total_pago" value="" disabled>
    </div> --}}

    <div wire:model="cantidadCuotasPagar" class="form-group">
        <div class=" alert aler-warning">
            @if ($cuotasSinPagar > 0)
                <p>Se generaran {{ $cantidadCuotasPagar }} cuotas ya que hay {{ $cuotasSinPagar }} cuota/s generadas sin
                    pagar
                </p>
            @endif
        </div>
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
