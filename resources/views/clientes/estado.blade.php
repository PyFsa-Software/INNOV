@extends('layouts.app')

@section('titulo', 'INNOV')

@section('contenido')


    <div class="main-panel">
        <div class="content-wrapper d-flex justify-content-center">
            <div class="col-md-12 grid-margin transparent">
                <div class="app-title mb-5">
                    <h3 class="text-center"><i class="fa fa-desktop fa-lg"></i> Estado Cliente:
                        {{ $persona->nombre }}-{{ $persona->apellido }}
                    </h3>
                    <a href="{{ route('clientes.index') }}" class="btn btn-warning">Volver Atrás</a>
                </div>

                <x-alertas />

                @forelse ($parcelas as $parcela)
                    <div class="card text-center mt-3">
                        <div class="card-header">
                            <div class="card-body">
                                <h4>{{ $parcela->descripcion_parcela }}</h4>

                                <h5 class="card-title {{ $parcela->cantidadDeudas > 0 ? 'text-danger' : 'text-success' }}">
                                    Estado:
                                    {{ $parcela->cantidadDeudas > 0 ? 'Hay cuotas vencidas' : 'Cliente al día' }}
                                </h5>

                                <h5 class="card-title {{ $parcela->verificarCuotasEditar ? 'text-danger' : '' }}">
                                    {{ $parcela->verificarCuotasEditar ? 'Hay Precios Desactualizados' : '' }}
                                </h5>

                                <a href="{{ route('clientes.estadoCuotas', $parcela->id_parcela) }}"
                                    class="btn btn-primary">Ver Detalle</a>

                                @if ($parcela?->verificarCancelacionPlan)
                                    <a class="btn btn-success" disabled>Cancelado</a>
                                @endif

                                @if ($parcela->actualizarPrecioCuotaFechaLimite)
                                    <a href="{{ route('clientes.actualizarPrecios', $parcela->id_parcela) }}"
                                        class="btn btn-danger">Actualizar Precios</a>
                                @endif

                                @if ($parcela->generarNuevasCuotas)
                                    <a href="{{ route('clientes.generarCuotas', $parcela->id_parcela) }}"
                                        class="btn btn-warning">Generar Nuevas Cuotas</a>
                                @endif

                                {{-- Botón "Calcular Deuda" solo si hay cuotas vencidas --}}
                                @if ($parcela->cantidadDeudas > 0)
                                    <a href="{{ route('clientes.calcularDeuda', $parcela->id_parcela) }}"
                                        class="btn btn-secondary">Calcular Deuda</a>
                                @endif
                            </div>

                            {{-- Botón para mostrar el modal --}}
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#confirmModal-{{ $parcela->id_parcela }}">
                                Eliminar Venta
                            </button>
                        </div>
                    </div>

                    {{-- Modal de confirmación --}}
                    <div class="modal fade" id="confirmModal-{{ $parcela->id_parcela }}" tabindex="-1"
                        aria-labelledby="modalLabel-{{ $parcela->id_parcela }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel-{{ $parcela->id_parcela }}">Confirmar
                                        Eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar esta venta?
                                    <br>
                                    <br>
                                    <p style="color:red;"> <b> Esta acción eliminara todo registro de
                                            la venta (pre-ventas, ventas y cuotas) y no se podra deshacer. Tambien liberara
                                            la
                                            parcela.</b></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <a href="{{ route('ventas.eliminarVenta', $parcela->id_venta) }}"
                                        class="btn btn-success">Confirmar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-danger alert-dismissible fade show mt-2">
                        <strong>El cliente seleccionado no tiene asignado ninguna venta.</strong>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


@endsection
