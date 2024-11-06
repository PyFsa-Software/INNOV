@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')
    <div class="main-panel">
        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center mb-5"><i class="fa fa-desktop fa-lg"></i> Calcular Deuda de Parcela:
                    {{ $parcela->descripcion_parcela }}</h1>
                <x-alertas />

                <!-- Formulario para seleccionar el interés y calcular la deuda -->
                <div class="mb-5">
                    <h3>Seleccionar Interés</h3>
                    <form id="calcularDeudaForm" class="form-inline">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="interes" class="sr-only">Porcentaje de Interés:</label>
                            <select name="interes" id="interes" class="form-control form-control-sm mr-2">
                                @foreach ($intereses as $key => $value)
                                    <option value="{{ $value }}">{{ $key }} ({{ $value }}%)</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="calcular" class="btn btn-primary form-control-sm mb-2">Calcular
                            Deuda</button>
                    </form>
                </div>

                <!-- Contenedor para mostrar el resultado del cálculo de deuda -->
                <div id="resultadoDeuda" class="table-responsive mb-5"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#calcular').on('click', function() {
                // Obtener el valor del interés seleccionado
                let interes = $('#interes').val();

                // Solicitud AJAX para obtener el cálculo de deuda
                $.ajax({
                    url: "{{ route('clientes.calcularDeudaResultado', $parcela->id_parcela) }}",
                    type: "POST",
                    data: {
                        interes: interes,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        // Crear el formateador de moneda
                        const formatoMoneda = new Intl.NumberFormat('es-ES', {
                            style: 'currency',
                            currency: 'ARG'
                        });

                        // Crear el contenido de la tabla de cuotas calculadas
                        let tablaCuotas = `
        <h3>Detalle de Cuotas Vencidas</h3>
        <table id="deudaTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Cuota</th>
                    <th>Monto Original</th>
                    <th>Días Vencidos</th>
                    <th>Monto con Interés</th>
                </tr>
            </thead>
            <tbody>
                ${data.cuotasCalculadas.map(cuota => `
                                                    <tr class="text-center">
                                                        <td>${cuota.cuota}</td>
                                                        <td>$ ${formatoMoneda.format(cuota.monto_original)}</td>
                                                        <td>${cuota.dias_vencidos} días</td>
                                                        <td>$ ${formatoMoneda.format(cuota.monto_con_interes)}</td>
                                                    </tr>
                                                `).join('')}
            </tbody>
        </table>
        <h3 class="text-center mt-3">Total a Pagar con Intereses: $ ${formatoMoneda.format(data.totalConInteres)}</h3>
    `;

                        // Renderizar el contenido en el div "resultadoDeuda"
                        $('#resultadoDeuda').html(tablaCuotas);

                        // Inicializar DataTable para la tabla generada
                        $('#deudaTable').DataTable({
                            "pageLength": 10
                        });
                    },

                    error: function(error) {
                        console.error('Error:', error);
                        $('#resultadoDeuda').html(
                            '<p class="text-danger">Hubo un error en el cálculo. Intente de nuevo.</p>'
                        );
                    }
                });
            });
        });
    </script>
@endpush
