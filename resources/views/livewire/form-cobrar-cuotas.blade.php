<form class="forms-sample" wire:submit.prevent="submit">

    @csrf
    @method('PUT')
    @if ($diferenciasDias > 0)
        <div class="form-group">
            <div class="alert alert-danger alert-dismissible fade show mt-2">
                <strong>La cuota esta vencida, se debio haber abonado hace {{ $diferenciasDias }} días.</strong>
            </div>
        </div>
    @endif
    <div class="form-group">
        <label for="numero_cuota">Numero Cuota: </label>
        <input type="number" class="form-control" name="numero_cuota" id="numero_cuota"
            value="{{ old('numero_cuota', $cuota->numero_cuota) }}" disabled>
    </div>
    <div class="form-group">
        <label for="fecha_maxima_a_pagar">Fecha Maxima a Pagar: </label>
        <input type="date" class="form-control" name="fecha_maxima_a_pagar" id="fecha_maxima_a_pagar"
            value="{{ old('fecha_maxima_a_pagar', $cuota->fecha_maxima_a_pagar) }}" disabled>
    </div>
    <div class="form-group">
        <label for="total_estimado_a_pagar">Total Estimado a Pagar: </label>
        <input type="number" class="form-control" name="total_estimado_a_pagar" id="total_estimado_a_pagar"
            value="{{ old('total_estimado_a_pagar', $cuota->total_estimado_a_pagar) }}" disabled>
    </div>

    <div class="form-group">
        <label for="total_abonado">Formas de Pago: </label>
        {{-- <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago">
            <option value="" selected disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select> --}}
        <select class="form-control" name="forma_pago" id="forma_pago" wire:model="formaPago">
            <option value="" disabled>Seleccione una forma de pago</option>
            @foreach ($formasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($formaPago === $key) selected @endif>
                    {{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="moneda_pago">Moneda de Pago: </label>
        <select class="form-control" name="moneda_pago" id="moneda_pago" wire:model="monedaPago">
            <option value="" disabled>Seleccione una moneda</option>
            @foreach ($monedasDePagos as $key => $value)
                <option value="{{ $key }}" @if ($monedaPago === $key) selected @endif>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="total_intereses">Interés en %: </label>
        <select class="form-control" name="total_intereses" id="total_intereses" wire:model="interes"
            @if ($diferenciasDias == 0) disabled @endif>
            <option value="" disabled>Seleccione un interés</option>
            @foreach ($intereses as $key => $value)
                <option value="{{ $value }}" @if ($interes == $value) selected @endif>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="incrementoInteres">Incremento: </label>
        <input type="number" class="form-control" name="incrementoInteres" id="incrementoInteres"
            value="{{ old('incrementoInteres', $incrementoInteres) }}" wire:model="incrementoInteres" disabled>
    </div>

    <div class="form-group">
        <label for="total_pago">Total a Abonar: </label>
        <input type="number" class="form-control" name="total_pago" id="total_pago"
            value="{{ old('total_pago', $totalAbonar) }}" disabled>
    </div>

    {{-- add select conceptoDeOpcionesSelect --}}
    <div class="form-group">
        <label for="conceptoDe">Concepto De: </label>
        <select class="form-control" name="conceptoDe" id="conceptoDe" wire:model="conceptoDe">
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

    {{-- Sección de Configuración de Fechas --}}
    @if ($puedeConfigurarFechas)
        <div class="form-group">
            <div class="card border-info shadow-sm">
                <div class="card-header bg-light border-info">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" id="configurarFechas" wire:model="configurarFechas" class="mr-3"
                            style="width: 18px; height: 18px; cursor: pointer;">
                        <label for="configurarFechas" class="mb-0 cursor-pointer" style="cursor: pointer;">
                            <i class="fa fa-calendar text-info mr-2"></i>
                            <strong>Configurar fechas de vencimiento personalizadas</strong>
                        </label>
                    </div>
                    <small class="text-muted mt-2 d-block" style="margin-left: 33px;">
                        <i class="fa fa-info-circle text-info"></i> Permite personalizar las fechas de vencimiento de
                        cuotas futuras no vencidas
                    </small>
                </div>

                @if ($mostrarConfiguracion)
                    <div class="card-body">
                        @error('configuracion_fechas')
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <i class="fa fa-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror

                        <div class="row">
                            {{-- Configurar fecha de cuota actual (solo si no está vencida) --}}
                            @if (!Carbon\Carbon::createFromFormat('Y-m-d', $cuota->fecha_maxima_a_pagar)->isPast())
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nuevaFechaCuotaActual" class="form-label">
                                            <i class="fa fa-calendar text-primary"></i>
                                            <strong>Nueva fecha para cuota actual</strong>
                                            <span class="badge badge-primary ml-1">{{ $cuota->numero_cuota }}</span>
                                        </label>
                                        <input type="date" class="form-control" id="nuevaFechaCuotaActual"
                                            wire:model="nuevaFechaCuotaActual" min="{{ date('Y-m-d') }}"
                                            placeholder="Seleccionar fecha">
                                        @error('nuevaFechaCuotaActual')
                                            <div class="invalid-feedback d-block">
                                                <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Configurar fechas de cuotas siguientes --}}
                            @if (!empty($cuotasSiguientes))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nuevaFechaSiguientes" class="form-label">
                                            <i class="fa fa-calendar-plus-o text-success"></i>
                                            <strong>Nueva fecha base para cuotas siguientes</strong>
                                        </label>
                                        <input type="date" class="form-control" id="nuevaFechaSiguientes"
                                            wire:model="nuevaFechaSiguientes" min="{{ date('Y-m-d') }}"
                                            placeholder="Seleccionar fecha base">
                                        @error('nuevaFechaSiguientes')
                                            <div class="invalid-feedback d-block">
                                                <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cantidadCuotasConfigurar" class="form-label">
                                            <i class="fa fa-list-ol text-warning"></i>
                                            <strong>Cantidad de cuotas a configurar</strong>
                                        </label>
                                        <select class="form-control" id="cantidadCuotasConfigurar"
                                            wire:model="cantidadCuotasConfigurar">
                                            @for ($i = 1; $i <= count($cuotasSiguientes); $i++)
                                                <option value="{{ $i }}">{{ $i }}
                                                    cuota{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                        @error('cantidadCuotasConfigurar')
                                            <div class="invalid-feedback d-block">
                                                <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Información sobre cuotas disponibles --}}
                        @if (!empty($cuotasSiguientes))
                            <div class="alert alert-info" style="border-left: 4px solid #17a2b8;">
                                <div class="d-flex align-items-start">
                                    <i class="fa fa-info-circle text-info mr-2 mt-1"></i>
                                    <div>
                                        <strong>Cuotas disponibles:</strong> {{ count($cuotasSiguientes) }} cuotas
                                        futuras no vencidas
                                        <br>
                                        <small class="text-muted">
                                            <i class="fa fa-lightbulb-o"></i>
                                            <strong>Tip:</strong> Las fechas se aplicarán mes a mes a partir de la fecha
                                            base configurada
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div wire:loading>
        Calculando abono..
    </div>


    <button class="btn btn-primary mr-2 mb-2 form-control" type="button" {{ $isDisabled ? 'disabled' : '' }}
        data-toggle="modal" data-target="#cobroCuota">Realizar
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
                    <p>Una vez realizada esta acción ya no se podrá modificar.</p>

                    @if ($configurarFechas)
                        <div class="alert alert-info">
                            <h6><i class="fa fa-calendar"></i> <strong>Configuración de fechas aplicada:</strong></h6>

                            @if ($nuevaFechaCuotaActual && !Carbon\Carbon::createFromFormat('Y-m-d', $cuota->fecha_maxima_a_pagar)->isPast())
                                <p><small><strong>Cuota actual ({{ $cuota->numero_cuota }}):</strong>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $nuevaFechaCuotaActual)->format('d/m/Y') }}</small>
                                </p>
                            @endif

                            @if ($nuevaFechaSiguientes && !empty($cuotasSiguientes))
                                <p><small><strong>{{ $cantidadCuotasConfigurar }} cuota(s) siguiente(s):</strong> A
                                        partir del
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $nuevaFechaSiguientes)->format('d/m/Y') }}</small>
                                </p>
                            @endif
                        </div>
                    @endif
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
        const continuarBtn = document.querySelector('#cobroCuota button[type="submit"]');

        // Escuchar el evento "click" en el botón "Continuar"
        continuarBtn.addEventListener('click', () => {
            // Obtener el modal
            const modal = document.querySelector('#cobroCuota');

            // Cerrar el modal
            $(modal).modal('hide');
        });
    </script>
</form>
