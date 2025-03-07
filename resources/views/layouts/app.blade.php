<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('titulo')</title>

    <link rel="shortcut icon" href="{{ url('/img/favicon.png') }}" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/assets/datatables/css/responsive.bootstrap4.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

    <livewire:styles />

</head>

<body>
    <div class="container-scroller">
        {{-- NAVBAR --}}
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="{{ url('inicio') }}">
                    <h3 class="mr-2" alt="logo"><b>INNOV S.R.L</b></h3>
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ url('inicio') }}">
                    <h3 class="p-2" alt="logo">IN</h3>
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                </ul>
                <ul class="navbar-nav navbar-nav-right">

                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            {{ auth()->user()->nombre_usuario }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a href={{ route('inicioSesion.desloguearse') }} class="dropdown-item">
                                <i class="ti-power-off text-primary"></i>
                                CERRAR SESION
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        {{-- SIDEBAR SKINS --}}

        <div class="container-fluid page-body-wrapper">
            {{-- SIDEBAR --}}

            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('inicio') }}">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">INICIO</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clientes.index') }}" aria-expanded="false"
                            aria-controls="tables">
                            <i class="icon-head menu-icon"></i>
                            <span class="menu-title">CLIENTES</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('ventasCanceladas.index') }}">
                                <i class="icon-ban text-primary"></i>
                                Pagos Multiples
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="dropdown">
                            <i class="icon-circle-plus menu-icon"></i>
                            <span class="menu-title">VENTAS</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('reservaParcela.crear') }}">
                                <i class="icon-plus text-primary"></i>
                                Realizar Pre-Venta
                            </a>
                            <a class="dropdown-item" href="{{ route('ventas.crear') }}">
                                <i class="icon-plus text-primary"></i>
                                Realizar Venta
                            </a>
                            <a class="dropdown-item" href="{{ route('reservaParcela.index') }}">
                                <i class="icon-check text-primary"></i>
                                Pre-Ventas Realizadas
                            </a>
                            {{-- <a class="dropdown-item" href="{{ route('ventas.listado') }}">
                                <i class="icon-check text-primary"></i>
                                Ventas Realizadas
                            </a> --}}
                            <a class="dropdown-item" href="{{ route('ventasCanceladas.index') }}">
                                <i class="icon-ban text-primary"></i>
                                Ventas Liquidadas
                            </a>
                        </div>
                    </li>
                    {{-- <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            NOMBRE-USUARIO
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a class="dropdown-item">
                                <i class="ti-settings text-primary"></i>
                                Settings
                            </a>
                            <a class="dropdown-item">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lotes.index') }}">
                            <i class="icon-columns menu-icon"></i>
                            <span class="menu-title">LOTES</span>
                            <i class="menu-arrow"></i>
                        </a>
                    </li>
                    {{-- <a class="nav-link" data-toggle="collapse" href={{ route('lotes.index') }}
                        aria-expanded="false" aria-controls="form-elements">
                        <i class="icon-columns menu-icon"></i>
                        <span class="menu-title">LOTES</span>
                        <i class="menu-arrow"></i>
                    </a> --}}
                    {{-- <div class="collapse" id="form-elements">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic
                                    Elements</a>
                            </li>
                        </ul>
                    </div> --}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('parcelas.index') }}">
                            <i class="icon-bar-graph menu-icon"></i>
                            <span class="menu-title">PARCELAS</span>
                            <i class="menu-arrow"></i>
                        </a>
                        {{-- <div class="collapse" id="charts">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
                                </li>
                            </ul>
                        </div> --}}
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('precios.index') }}" aria-expanded="false"
                            aria-controls="icons">
                            <i class="icon-contract menu-icon"></i>
                            <span class="menu-title">PRECIOS</span>
                            <i class="menu-arrow"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                            aria-controls="ui-basic">
                            <i class="icon-layout menu-icon"></i>
                            <span class="menu-title">REPORTES</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link"
                                        href="{{ route('reportes.planilla') }}">Planilla</a></li>
                                {{-- <li class="nav-item"> <a class="nav-link"
                                        href="pages/ui-features/dropdowns.html">Dropdowns</a></li>
                                <li class="nav-item"> <a class="nav-link"
                                        href="pages/ui-features/typography.html">Typography</a>
                                </li> --}}
                            </ul>
                        </div>
                    </li>
                    {{-- add option for comprobantes --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('comprobantes.index') }}" aria-expanded="false"
                            aria-controls="auth">
                            <i class="ti-receipt menu-icon"></i>
                            <span class="menu-title
                            ">COMPROBANTES</span>
                            <i class="menu-arrow
                            "></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('resumenDiario.index') }}" aria-expanded="false"
                            aria-controls="auth">
                            <i class="ti-book menu-icon"></i>
                            <span class="menu-title
                            ">RESUMEN DIARIO</span>
                            <i class="menu-arrow
                            "></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href={{ route('inicioSesion.desloguearse') }} aria-expanded="false"
                            aria-controls="auth">
                            <i class="ti-power-off menu-icon"></i>
                            <span class="menu-title">CERRAR SESION</span>
                            <i class="menu-arrow"></i>
                        </a>
                    </li>

                </ul>
            </nav>
            @yield('contenido')
        </div>
    </div>
    <!-- Bootstrap JS (con dependencias) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('/assets/vendor-bundle-base/vendor.bundle.base.js') }}"></script>
    <script src="{{ url('/assets/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('/assets/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('/assets/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('/assets/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @vite(['resources/js/importFilesSistema.js'])
    @stack('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            $('#clienteCombo').select2({
                placeholder: 'Seleccione un cliente',
                allowClear: true,
            });

            // Sincronizar con Livewire
            $('#clienteCombo').on('change', function(e) {
                let value = $(this).val();
                Livewire.emit('updateClienteCombo', value); // Usar un evento emitido
            });

            // Reinicializar Select2 después de un renderizado Livewire
            Livewire.hook('message.processed', (message, component) => {
                $('#clienteCombo').select2();
            });

            $('#id_cliente').select2({
                placeholder: 'Seleccione un cliente',
                allowClear: true
            });

            // Detectar cambios en select2 y sincronizar con Livewire
            $('#id_cliente').on('change', function() {
                let value = $(this).val();
                Livewire.emit('setCliente', value); // Emitir evento para actualizar Livewire
            });

            // Mantener la selección después de cada renderizado de Livewire
            Livewire.hook('message.processed', () => {
                $('#id_cliente').select2();
            });
            $('#id_cliente_venta').select2({
                placeholder: 'Seleccione un cliente',
                allowClear: true
            });

            // Detectar cambios en select2 y sincronizar con Livewire
            $('#id_cliente_venta').on('change', function() {
                let value = $(this).val();
                Livewire.emit('setCliente', value); // Emitir evento correcto
            });

            // Mantener la selección después de cada renderizado de Livewire
            Livewire.hook('message.processed', () => {
                $('#id_cliente_venta').select2();
            });
        });
    </script>
    <livewire:scripts />


</body>

</html>
