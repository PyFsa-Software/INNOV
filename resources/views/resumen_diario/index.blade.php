@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')
    <div class="main-panel">
        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center mb-5"><i class="fa fa-desktop fa-lg"></i> Resumen de Pagos</h1>
                <x-alertas />

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
            $('#cuotasTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "/resumen-cuotas",
                "pageLength": 5, // Limitar la cantidad de registros mostrados inicialmente a 5
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
                "ajax": "/resumen-preVentas",
                "pageLength": 5, // Limitar la cantidad de registros mostrados inicialmente a 5
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

            // $('#ventasTable').DataTable({
            //     "processing": true,
            //     "serverSide": true,
            //     "ajax": "{{ route('resumenPreVentas.resumenPreVentas') }}",
            //     "columns": [{
            //             "data": "id"
            //         },
            //         {
            //             "data": "name"
            //         },
            //         {
            //             "data": "created_at"
            //         },
            //         {
            //             "data": "updated_at"
            //         }
            //     ]
            // });
        });
    </script>
@endpush
