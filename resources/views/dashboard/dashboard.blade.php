@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')

    <!-- Dashboard Mejorado -->
    <div class="main-panel">
        <div class="content-wrapper">

            <!-- Título del Dashboard -->
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-2"><i class="fa fa-tachometer-alt text-primary"></i> <strong>Centro de Control
                            INNOV</strong></h2>
                    <p class="text-muted">Resumen ejecutivo y métricas clave del negocio</p>
                </div>
            </div>

            <!-- FILA 1: MÉTRICAS PRINCIPALES DE NEGOCIO -->
            <div class="row mb-4">
                <!-- Cuotas Cobradas del Mes -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="metric-card collection-card">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value">{{ $cobranzaDelMes }}</h3>
                                <p class="metric-label">Cuotas Cobradas del Mes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pre-ventas Pendientes -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="metric-card pending-card">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon">
                                <i class="fa fa-hourglass-half"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value">{{ $reservasPendientes }}</h3>
                                <p class="metric-label">Pre-ventas Pendientes</p>
                                <small class="text-light">Faltan pagos por completar</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pre-ventas Canceladas -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="metric-card cancelled-card">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon">
                                <i class="fa fa-money-check-alt"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value">{{ $reservasCanceladas }}</h3>
                                <p class="metric-label">Pre-ventas Canceladas</p>
                                <small class="text-light">Completamente pagadas</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parcelas Vendidas -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="metric-card sales-card">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value">{{ $totalParcelasVendidas }}/{{ $totalParcelas }}</h3>
                                <p class="metric-label">Parcelas Vendidas</p>
                                <small class="text-light">{{ round(($totalParcelasVendidas / $totalParcelas) * 100, 1) }}%
                                    del total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILA 2: ALERTAS Y ACCESOS RÁPIDOS -->
            <div class="row mb-4">
                <!-- Panel de Alertas -->
                <div class="col-lg-8 mb-4">
                    <div class="alerts-panel">
                        <div class="panel-header">
                            <h5><i class="fa fa-bell text-warning"></i> <strong>Alertas del Sistema</strong></h5>
                        </div>
                        <div class="panel-body">
                            @if ($cuotasVencidasCriticas > 0)
                                <div class="alert-item critical">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <div class="alert-content">
                                        <strong>{{ $cuotasVencidasCriticas }} cuotas vencidas críticas</strong>
                                        <small>Vencidas hace más de 30 días - Requiere acción inmediata</small>
                                    </div>
                                    <a href="{{ route('clientes.index') }}" class="alert-action">Ver Detalles</a>
                                </div>
                            @endif

                            @if ($cuotasProximasVencer > 0)
                                <div class="alert-item warning">
                                    <i class="fa fa-clock"></i>
                                    <div class="alert-content">
                                        <strong>{{ $cuotasProximasVencer }} cuotas próximas a vencer</strong>
                                        <small>Vencen en los próximos 7 días</small>
                                    </div>
                                    <a href="{{ route('clientes.index') }}" class="alert-action">Gestionar</a>
                                </div>
                            @endif

                            @if ($reservasPendientes > 0)
                                <div class="alert-item info">
                                    <i class="fa fa-hourglass-half"></i>
                                    <div class="alert-content">
                                        <strong>{{ $reservasPendientes }} pre-ventas con pagos pendientes</strong>
                                        <small>Faltan pagos por completar</small>
                                    </div>
                                    <a href="{{ route('reservaParcela.index') }}" class="alert-action">Gestionar Pagos</a>
                                </div>
                            @endif

                            @if ($reservasCanceladas >= 5)
                                <div class="alert-item success">
                                    <i class="fa fa-check-circle"></i>
                                    <div class="alert-content">
                                        <strong>¡{{ $reservasCanceladas }} pre-ventas completamente pagadas!</strong>
                                        <small>Excelente gestión de cobranzas</small>
                                    </div>
                                    <a href="{{ route('reservaParcela.index') }}" class="alert-action">Ver Historial</a>
                                </div>
                            @endif

                            @if ($cuotasVencidasCriticas == 0 && $cuotasProximasVencer == 0 && $reservasPendientes == 0 && $reservasCanceladas < 5)
                                <div class="alert-item success">
                                    <i class="fa fa-check-circle"></i>
                                    <div class="alert-content">
                                        <strong>¡Todo al día!</strong>
                                        <small>No hay alertas críticas en este momento</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Accesos Rápidos -->
                <div class="col-lg-4 mb-4">
                    <div class="quick-actions-panel">
                        <div class="panel-header">
                            <h5><i class="fa fa-bolt text-success"></i> <strong>Acciones Rápidas</strong></h5>
                        </div>
                        <div class="panel-body">
                            <div class="quick-actions-grid">
                                <a href="{{ route('ventas.crear') }}" class="quick-btn success">
                                    <i class="fa fa-plus"></i>
                                    <span>Nueva Venta</span>
                                </a>
                                <a href="{{ route('clientes.index') }}" class="quick-btn primary">
                                    <i class="fa fa-search"></i>
                                    <span>Buscar Cliente</span>
                                </a>
                                <a href="{{ route('resumenDiario.index') }}" class="quick-btn info">
                                    <i class="fa fa-chart-bar"></i>
                                    <span>Reporte Diario</span>
                                </a>
                                <a href="{{ route('precios.index') }}" class="quick-btn warning">
                                    <i class="fa fa-tags"></i>
                                    <span>Actualizar Precios</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILA 3: INFORMACIÓN ADICIONAL -->
            <div class="row">
                <!-- Resumen de Clientes -->
                <div class="col-lg-12 mb-4">
                    <div class="summary-panel">
                        <div class="panel-header">
                            <h5><i class="fa fa-users text-primary"></i> <strong>Resumen de Clientes</strong></h5>
                        </div>
                        <div class="panel-body">
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <div class="summary-icon success">
                                        <i class="fa fa-user-check"></i>
                                    </div>
                                    <div class="summary-content">
                                        <h4>{{ $totalClientes }}</h4>
                                        <p>Clientes Activos</p>
                                    </div>
                                </div>

                                <div class="summary-item clickeable-card" data-bs-toggle="modal"
                                    data-bs-target="#clientesVencidosModal" style="cursor: pointer;">
                                    <div class="summary-icon warning">
                                        <i class="fa fa-user-clock"></i>
                                    </div>
                                    <div class="summary-content">
                                        <h4>{{ count($clientesCuotasVencidas) }}</h4>
                                        <p>Con Cuotas Vencidas</p>
                                        <small class="text-muted"><i class="fa fa-mouse-pointer"></i> Click para ver
                                            detalles</small>
                                    </div>
                                </div>

                                <div class="summary-item">
                                    <div class="summary-icon info">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="summary-content">
                                        <h4>{{ $totalParcelasDisponibles }}</h4>
                                        <p>Parcelas Disponibles</p>
                                    </div>
                                </div>

                                <div class="summary-item">
                                    <div class="summary-icon primary">
                                        <i class="fa fa-handshake"></i>
                                    </div>
                                    <div class="summary-content">
                                        <h4>{{ $reservasDelMes }}</h4>
                                        <p>Pre-ventas del Mes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

    <!-- Modal Clientes con Cuotas Vencidas -->
    <div class="modal fade" id="clientesVencidosModal" tabindex="-1" role="dialog"
        aria-labelledby="clientesVencidosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="clientesVencidosModalLabel">
                        <i class="fa fa-exclamation-triangle"></i> Clientes con Cuotas Vencidas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    @if (count($clientesCuotasVencidas) > 0)
                        <div class="alert alert-warning py-2 mb-3">
                            <small>
                                <i class="fa fa-info-circle"></i>
                                <strong>{{ count($clientesCuotasVencidas) }} clientes</strong> con cuotas vencidas
                            </small>
                        </div>

                        <div class="table-responsive">
                            <table id="clientesVencidosTable" class="table table-striped table-hover table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><i class="fa fa-user"></i> Cliente</th>
                                        <th><i class="fa fa-id-card"></i> DNI</th>
                                        <th><i class="fa fa-home"></i> Parcela</th>
                                        <th><i class="fa fa-map"></i> Manzana</th>
                                        <th><i class="fa fa-calendar-times"></i> Cuotas Vencidas</th>
                                        <th><i class="fa fa-cogs"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clientesCuotasVencidas as $clienteGroup)
                                        @foreach ($clienteGroup as $detalle)
                                            @php
                                                $cliente = $detalle->venta->cliente;
                                                $parcela = $detalle->venta->parcela;
                                                $cuotasVencidasCount = \App\Models\DetalleVenta::where(
                                                    'id_venta',
                                                    $detalle->venta->id_venta,
                                                )
                                                    ->where('pagado', '!=', 'si')
                                                    ->where('fecha_maxima_a_pagar', '<', date('Y-m-d'))
                                                    ->count();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $cliente->nombre }} {{ $cliente->apellido }}</strong>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $cliente->dni }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info badge-sm">
                                                        {{ $parcela->descripcion_parcela }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $parcela->manzana }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-danger">
                                                        {{ $cuotasVencidasCount }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('clientes.estado', $cliente->id_persona) }}"
                                                        class="btn btn-sm btn-outline-primary" target="_blank"
                                                        title="Ver estado del cliente">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @break

                                        {{-- Solo una fila por cliente --}}
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-check-circle fa-2x text-success mb-2"></i>
                        <h6>¡Excelente!</h6>
                        <small class="text-muted">No hay clientes con cuotas vencidas.</small>
                    </div>
                @endif
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cerrar
                </button>
                @if (count($clientesCuotasVencidas) > 0)
                    <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-users"></i> Gestionar Clientes
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

<style>
/* Dashboard Mejorado - Estilos Personalizados */

/* Métricas Cards */
.metric-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 25px;
    color: white;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    height: 140px;
    /* Altura fija para consistencia */
    display: flex;
    align-items: center;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.metric-card.collection-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.metric-card.pending-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.metric-card.cancelled-card {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.metric-card.sales-card {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.metric-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 24px;
}

.metric-content {
    flex: 1;
}

.metric-value {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.metric-label {
    font-size: 0.9rem;
    margin: 5px 0;
    opacity: 0.9;
}

.trend {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 15px;
    font-weight: 600;
}

.trend.up {
    background: rgba(87, 182, 87, 0.9);
}

.trend.down {
    background: rgba(255, 71, 71, 0.9);
}

.trend.neutral {
    background: rgba(158, 158, 158, 0.9);
}

/* Panels */
.alerts-panel,
.quick-actions-panel,
.timeline-panel,
.summary-panel {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    height: 100%;
}

.panel-header {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
}

.panel-header h5 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.panel-body {
    padding: 20px;
}

/* Alertas */
.alert-item {
    display: flex;
    align-items: center;
    padding: 15px;
    margin-bottom: 15px;
    border-left: 4px solid;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.alert-item:hover {
    transform: translateX(5px);
}

.alert-item.critical {
    border-left-color: #FF4747;
    background: linear-gradient(135deg, rgba(255, 71, 71, 0.1) 0%, rgba(255, 71, 71, 0.05) 100%);
}

.alert-item.warning {
    border-left-color: #FFC100;
    background: linear-gradient(135deg, rgba(255, 193, 0, 0.1) 0%, rgba(255, 193, 0, 0.05) 100%);
}

.alert-item.info {
    border-left-color: #248AFD;
    background: linear-gradient(135deg, rgba(36, 138, 253, 0.1) 0%, rgba(36, 138, 253, 0.05) 100%);
}

.alert-item.success {
    border-left-color: #57B657;
    background: linear-gradient(135deg, rgba(87, 182, 87, 0.1) 0%, rgba(87, 182, 87, 0.05) 100%);
}

.alert-item i {
    font-size: 24px;
    margin-right: 15px;
    color: inherit;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

.alert-content small {
    color: #666;
}

.alert-action {
    padding: 8px 15px;
    background: rgba(0, 0, 0, 0.1);
    color: #333;
    text-decoration: none;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.alert-action:hover {
    background: rgba(0, 0, 0, 0.2);
    text-decoration: none;
    color: #333;
}

/* Accesos Rápidos */
.quick-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.quick-btn {
    padding: 20px 15px;
    border-radius: 12px;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    font-weight: 600;
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.quick-btn i {
    font-size: 24px;
}

.quick-btn.success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.quick-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.quick-btn.info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.quick-btn.warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.quick-btn:hover {
    transform: translateY(-3px);
    text-decoration: none;
    color: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Timeline */
.timeline-list {
    max-height: 400px;
    overflow-y: auto;
}

.timeline-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.timeline-item:last-child {
    border-bottom: none;
}

.timeline-date {
    min-width: 50px;
    text-align: center;
    font-weight: 600;
    color: #4B49AC;
    background: rgba(75, 73, 172, 0.1);
    padding: 10px;
    border-radius: 8px;
    margin-right: 15px;
}

.timeline-content {
    flex: 1;
}

.timeline-action {
    margin-left: 15px;
}

/* Summary Grid */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 12px;
    transition: all 0.3s ease;
    min-height: 90px;
}

.summary-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Card clickeable especial */
.clickeable-card {
    position: relative;
    overflow: hidden;
}

.clickeable-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.clickeable-card:hover .summary-content {
    color: white;
}

.clickeable-card:hover .summary-content small {
    color: rgba(255, 255, 255, 0.9);
}

.clickeable-card::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    font-family: "Font Awesome 6 Free";
    content: '\f054';
    font-weight: 900;
    color: rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}

.clickeable-card:hover::after {
    color: rgba(255, 255, 255, 0.8);
    right: 8px;
}

/* Modal Clientes Vencidos - DataTable Styles */
#clientesVencidosModal .modal-body {
    padding: 1rem;
}

#clientesVencidosModal .table-responsive {
    border: none;
    max-height: none;
}

#clientesVencidosTable {
    font-size: 0.875rem;
    width: 100% !important;
    margin-bottom: 0;
    table-layout: fixed !important;
}

#clientesVencidosTable th {
    background-color: #343a40 !important;
    color: white !important;
    font-weight: 600;
    border: 1px solid #495057;
    padding: 0.75rem 0.5rem;
    text-align: center;
    white-space: nowrap;
    font-size: 0.8rem;
}

#clientesVencidosTable td {
    padding: 0.75rem 0.5rem;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    text-align: center;
}

/* Anchos específicos para columnas */
#clientesVencidosTable th:nth-child(1),
#clientesVencidosTable td:nth-child(1) {
    width: 25%;
    text-align: left;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

#clientesVencidosTable th:nth-child(2),
#clientesVencidosTable td:nth-child(2) {
    width: 15%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

#clientesVencidosTable th:nth-child(3),
#clientesVencidosTable td:nth-child(3) {
    width: 20%;
}

#clientesVencidosTable th:nth-child(4),
#clientesVencidosTable td:nth-child(4) {
    width: 10%;
}

#clientesVencidosTable th:nth-child(5),
#clientesVencidosTable td:nth-child(5) {
    width: 15%;
}

#clientesVencidosTable th:nth-child(6),
#clientesVencidosTable td:nth-child(6) {
    width: 15%;
}

.badge-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    white-space: nowrap;
}

/* DataTable Controls */
#clientesVencidosModal .dataTables_wrapper {
    font-size: 0.875rem;
}

#clientesVencidosModal .dataTables_wrapper .row {
    margin: 0;
}

#clientesVencidosModal .dataTables_length,
#clientesVencidosModal .dataTables_filter {
    margin-bottom: 1rem;
}

#clientesVencidosModal .dataTables_length select,
#clientesVencidosModal .dataTables_filter input {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
}

#clientesVencidosModal .dataTables_filter input {
    width: 200px;
}

/* Paginación */
#clientesVencidosModal .dataTables_paginate {
    margin-top: 1rem;
}

#clientesVencidosModal .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 0.375rem;
    border: 1px solid #dee2e6;
    background: white;
    color: #495057;
}

#clientesVencidosModal .dataTables_paginate .paginate_button:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

#clientesVencidosModal .dataTables_paginate .paginate_button.current {
    background: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}

#clientesVencidosModal .dataTables_info {
    font-size: 0.875rem;
    margin-top: 1rem;
    color: #6c757d;
}

/* Fix para evitar overflow */
#clientesVencidosModal .modal-dialog {
    max-width: 90%;
    margin: 1.75rem auto;
}

@media (min-width: 992px) {
    #clientesVencidosModal .modal-dialog {
        max-width: 900px;
    }
}

.summary-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.summary-icon.success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.summary-icon.warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.summary-icon.info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.summary-icon.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.summary-content h4 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
}

.summary-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .metric-card {
        margin-bottom: 20px;
        padding: 20px;
        height: auto;
        min-height: 120px;
    }

    .metric-value {
        font-size: 1.8rem;
    }

    .quick-actions-grid {
        grid-template-columns: 1fr;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .timeline-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .timeline-date {
        margin-right: 0;
    }
}

@media (max-width: 1024px) and (min-width: 769px) {
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {

    .alerts-panel,
    .quick-actions-panel {
        margin-bottom: 20px;
    }
}

/* Scrollbar personalizado */
.timeline-list::-webkit-scrollbar {
    width: 6px;
}

.timeline-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.timeline-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.timeline-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.metric-card,
.alerts-panel,
.quick-actions-panel,
.timeline-panel,
.summary-panel {
    animation: fadeInUp 0.6s ease forwards;
}

.metric-card:nth-child(1) {
    animation-delay: 0.1s;
}

.metric-card:nth-child(2) {
    animation-delay: 0.2s;
}

.metric-card:nth-child(3) {
    animation-delay: 0.3s;
}

.metric-card:nth-child(4) {
    animation-delay: 0.4s;
}
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable para clientes con cuotas vencidas
        function initClientesVencidosTable() {
            if ($('#clientesVencidosTable').length && $.fn.DataTable) {
                $('#clientesVencidosTable').DataTable({
                    language: {
                        "lengthMenu": "Mostrar _MENU_ registros por página",
                        "zeroRecords": "No se encontraron registros",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay registros disponibles",
                        "infoFiltered": "(filtrado de _MAX_ registros totales)",
                        "search": "Buscar:",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    pageLength: 10,
                    responsive: false, // Desactivar responsive para evitar deformaciones
                    scrollX: false,
                    autoWidth: false, // Control manual de anchos
                    order: [
                        [0, 'asc']
                    ], // Ordenar por nombre del cliente
                    columnDefs: [{
                            targets: [0], // Columna Cliente
                            width: '25%',
                            className: 'text-left'
                        },
                        {
                            targets: [1], // Columna DNI
                            width: '15%',
                            className: 'text-center'
                        },
                        {
                            targets: [2], // Columna Parcela
                            width: '20%',
                            className: 'text-center'
                        },
                        {
                            targets: [3], // Columna Manzana
                            width: '10%',
                            className: 'text-center'
                        },
                        {
                            targets: [4], // Columna Cuotas Vencidas
                            width: '15%',
                            className: 'text-center',
                            orderable: false
                        },
                        {
                            targets: [5], // Columna Acciones
                            width: '15%',
                            className: 'text-center',
                            orderable: false
                        }
                    ],
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    drawCallback: function(settings) {
                        // Aplicar estilos después de cada redibujado
                        $('#clientesVencidosTable th, #clientesVencidosTable td').css({
                            'white-space': 'nowrap',
                            'text-overflow': 'ellipsis'
                        });
                    },
                    initComplete: function(settings, json) {
                        // Agregar clases personalizadas a los elementos de DataTable
                        $('.dataTables_filter input').addClass('form-control form-control-sm');
                        $('.dataTables_length select').addClass('form-control form-control-sm');
                        $('.dataTables_paginate .paginate_button').addClass('btn btn-sm');
                        $('.dataTables_paginate .paginate_button.current').addClass('btn-primary');
                        $('.dataTables_paginate .paginate_button:not(.current)').addClass(
                            'btn-outline-primary');

                        // Forzar anchuras fijas
                        $('#clientesVencidosTable').css('table-layout', 'fixed');
                    }
                });
            }
        }

        // Intentar inicializar inmediatamente
        initClientesVencidosTable();

        // También inicializar cuando se abra el modal (por si acaso)
        $('#clientesVencidosModal').on('shown.bs.modal', function() {
            if (!$.fn.DataTable.isDataTable('#clientesVencidosTable')) {
                initClientesVencidosTable();
            }
        });
    });
</script>
@endpush
