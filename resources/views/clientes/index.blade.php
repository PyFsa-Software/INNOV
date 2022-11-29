@extends('layouts.app')

@section('titulo', 'INNOVA')

@section('contenido')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">

<div class="main-panel">

    <div class="content-wrapper d-flex justify-content-center">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @if (count($clientes) === 0)
            <h1 class="text-center text-danger"><i class="fa fa-desktop fa-lg"></i> Aun no hay registros de clientes
            </h1>
            <a href="{{route('clientes.crear')}}" class="btn btn-success">Agregar Cliente</a>
            @else

            <h1 class="text-center"><i class="fa fa-desktop fa-lg"></i> Listado de Clientes
            </h1>
            <a href="{{route('clientes.crear')}}" class="btn btn-success mb-2">Agregar Cliente</a>
            <div class="table-responsive">

                {{ $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) }}
            </div>
            @endif
        </div>

    </div>
</div>

{{-- <script type="text/javascript">
    $(function () {
    $('#tablaClientes').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('clientes.index') }}",
        columns: [
            {data: 'nombre_apellido', name: 'nombre'},
            {data: 'dni', name: 'dni'},
            {data: 'celular', name: 'celular'},
            {data: 'correo', name: 'correo'},
            {data: 'editar', name: 'editar', orderable: false, searchable: false},
            {data: 'eliminar', name: 'eliminar', orderable: false, searchable: false},
        ],
        language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Registros del _START_ al _END_ de _TOTAL_ registros",
        sInfoEmpty: "Registros del 0 al 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
        },
        oAria: {
        sSortAscending:
        ": Activar para ordenar la columna de manera ascendente",
        sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
        },
        },
        responsive: true,
    });
  });
</script> --}}
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush