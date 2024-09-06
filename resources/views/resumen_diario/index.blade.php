@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')
    <div class="main-panel">
        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center mb-5"><i class="fa fa-desktop fa-lg"></i> Resumen de Pagos</h1>
                <x-alertas />

                <div class="mb-5">
                    <h3>Seleccionar Rango de Fechas</h3>
                    <form id="fechaForm" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="fecha_desde" class="sr-only">Fecha Desde:</label>
                            <input type="date" id="fecha_desde" name="fecha_desde"
                                class="form-control form-control-sm mr-2" placeholder="Fecha Desde">
                        </div>
                        <div class="form-group mb-2">
                            <label for="fecha_hasta" class="sr-only">Fecha Hasta:</label>
                            <input type="date" id="fecha_hasta" name="fecha_hasta"
                                class="form-control form-control-sm mr-2" placeholder="Fecha Hasta">
                        </div>
                        <button type="button" id="buscar" class="btn btn-primary form-control-sm  mb-2">Buscar</button>
                        <button type="button" id="exportar" class="btn btn-success form-control-sm mb-2 ml-2">Exportar a
                            Excel</button>
                    </form>
                </div>

                <div class="table-responsive mb-5">
                    <h3>Pagos de Cuotas</h3>
                    <table id="cuotasTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Parcela</th>
                                <th>Lote</th>
                                <th>Fecha de Pago</th>
                                <th>Forma de Pago</th>
                                <th>Importe de Pago</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="table-responsive mb-5">
                    <h3>Pagos de Pre-Ventas</h3>
                    <table id="preVentaTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Parcela</th>
                                <th>Fecha de Pago</th>
                                <th>Forma de Pago</th>
                                <th>Importe de Pago</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#exportar').on('click', function() {
                var fechaDesde = $('#fecha_desde').val();
                var fechaHasta = $('#fecha_hasta').val();
                window.location.href =
                    `/exportar-resumen?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`;
            });


            function cargarTablas(fechaDesde, fechaHasta) {
                $('#cuotasTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "destroy": true, // Esto es necesario para destruir la tabla anterior y crear una nueva
                    "ajax": {
                        "url": "/resumen-cuotas",
                        "data": {
                            "fecha_desde": fechaDesde,
                            "fecha_hasta": fechaHasta
                        }
                    },
                    "pageLength": 5,
                    "columns": [{
                            "data": "cliente"
                        },
                        {
                            "data": "parcela"
                        },
                        {
                            "data": "lote"
                        },
                        {
                            "data": "fecha_pago"
                        },
                        {
                            "data": "metodo_pago"
                        },
                        {
                            "data": "monto_pago"
                        }
                    ]
                });

                $('#preVentaTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "destroy": true, // Esto es necesario para destruir la tabla anterior y crear una nueva
                    "ajax": {
                        "url": "/resumen-preVentas",
                        "data": {
                            "fecha_desde": fechaDesde,
                            "fecha_hasta": fechaHasta
                        }
                    },
                    "pageLength": 5,
                    "columns": [{
                            "data": "cliente"
                        },
                        {
                            "data": "parcela"
                        },
                        {
                            "data": "fecha_pago"
                        },
                        {
                            "data": "forma_pago"
                        },
                        {
                            "data": "importe_pago"
                        }
                    ]
                });
            }

            $('#buscar').on('click', function() {
                var fechaDesde = $('#fecha_desde').val();
                var fechaHasta = $('#fecha_hasta').val();
                cargarTablas(fechaDesde, fechaHasta);
            });

            // Inicialmente no cargar las tablas hasta que se haga una búsqueda
        });
    </script>
@endpush
