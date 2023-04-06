@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')

    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">



            <div class="row">
                <div class="col-md-12 grid-margin transparent">
                    <div class="row">
                        <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card bg-gradient-dark">
                                <div class="card-body">
                                    <p class="mb-4">TOTAL PARCELAS</p>
                                    <p class="fs-30 mb-2">{{ $totalParcelas }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card bg-gradient-primary">
                                <div class="card-body">
                                    <p class="mb-4">PARCELAS VENDIDAS</p>
                                    <p class="fs-30 mb-2">{{ $totalParcelasVendidas }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4  stretch-card transparent">
                            <div class="card bg-gradient-info">
                                <div class="card-body">
                                    <p class="mb-4">PARCELAS DISPONIBLES</p>
                                    <p class="fs-30 mb-2">{{ $totalParcelasDisponibles }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card bg-gradient-danger">
                                <div class="card-body">
                                    <p class="mb-4">TOTAL CLIENTES</p>
                                    <p class="fs-30 mb-2">{{ $totalClientes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card bg-gradient-warning btn" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <div class="card-body">
                                    <p class="mb-4">CLIENTES CON CUOTAS VENCIDAS</p>
                                    <p class="fs-30 mb-2">{{ count($clientesCuotasVencidas) }}</p>
                                </div>
                                <div class="collapse" id="collapseExample"">
                                        <div class="card-body justify-content-end">
                                    @foreach ($clientesCuotasVencidas as $item)

                                            @foreach ($item as $persona)
                                                <a class="dropdown-item"
                                                    href="{{route('clientes.estado', $persona->venta->cliente->id_persona)}}">{{ $persona->venta->cliente->nombre }}
                                                    {{ $persona->venta->cliente->apellido }}</a>
                                            @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card bg-gradient-success">
                            <div class="card-body">
                                <p class="mb-4">CLIENTES A LOS QUE SE DEBE ACTUALIZAR SUS CUOTAS</p>
                                <p class="fs-30 mb-2">{{ $totalClientes }}</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
                {{-- <div class="row">
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card bg-gradient-info">
                            <div class="card-body">
                                <p class="mb-4">CLIENTES A LOS QUE SE DEBE GENERAR CUOTAS</p>
                                <p class="fs-30 mb-2">{{ $totalClientes }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card bg-gradient-secondary">
                            <div class="card-body">
                                <p class="mb-4">CLIENTES CON CUOTAS DESACTUALIZADAS</p>
                                <p class="fs-30 mb-2">{{ $totalClientes }}</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{--
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a
                    href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
                BootstrapDash. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i
                    class="ti-heart text-danger ml-1"></i></span>
        </div>
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a
                    href="https://www.themewagon.com/" target="_blank">Themewagon</a></span>
        </div>
    </footer>
    <!-- partial --> --}}
    {{--
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a
                    href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
                BootstrapDash. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i
                    class="ti-heart text-danger ml-1"></i></span>
        </div>
    </footer> --}}

    </div>


@endsection
