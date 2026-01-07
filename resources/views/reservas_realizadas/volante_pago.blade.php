<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Volante Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .nota-pedido {
            width: 100%;
            border: 2px solid black;
            padding: 20px;
        }

        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .info {
            font-size: 13px;
            margin-bottom: 3px;
            width: 100%;
            border-collapse: collapse;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        .fecha {
            font-size: 15px;
            float: right;
            padding-right: 10px;
        }

        .firma {
            font-size: 13px;
            text-align: right;
            padding-top: 15px;
        }
    </style>

</head>

<body>

    <div class="nota-pedido">
        <table style="margin-bottom: 2px;">
            <tr>
                <td rowspan="2" style="width: 35%; text-align: center;">{!! $html !!}
                    <div class="info-empresa">
                        <small class="nombre-empresa"><b> INNOV S.R.L </b></small>
                        <br>
                        <small>CUIT: 30-71804836-9</small>
                    </div>
                </td>
                <td style="text-align: right; font-size: 13px;">
                    <div
                        style="width: 35%; margin-left: auto; text-align: left; word-wrap: break-word; white-space: normal;">
                        {{-- <small>N°: <b>{{ $cuota->numero_recibo }}</b></small><br> --}}
                        <small>Fecha: <b>{{ date('d-m-Y', strtotime($fecha_pago)) }}</b></small><br>
                        <small>CEL: 3704-998173</small><br>
                        <small style="display: block;">Rivadavia 210</small>
                    </div>
                </td>



            </tr>
            <tr>
                <td class="header">COMPROBANTE DE PAGO
                    <div>
                        @if ($detalleReservaParcela->cancelado == 1)
                            {!! $htmlCancel !!}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table class="info">
            <tr>
                <td style="width: 50%;"><strong>Sr/Sra:</strong>
                    {{ getFormatNombreApellido($cliente->nombre, $cliente->apellido) }}</td>
                <td><strong>DNI:</strong> {{ $cliente->dni }}</td>
            </tr>
        </table>

        <table class="info">

            <tr>
                <td style="width: 40%;">
                    <table>
                        <tr>
                            <td><strong>Loteo:</strong>{{ $parcela?->lote->nombre_lote }}.</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 60%;">

                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Ubicación:</strong> {{ $cliente?->domicilio }}
                            </td>
                            <td style="width: 50%;"><strong>Dimensión:</strong>{{ $parcela?->ancho }} x
                                {{ $parcela?->largo }}.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="width: 40%;">
                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Manzana:</strong> {{ $parcela?->manzana }}.</td>

                        </tr>
                    </table>
                </td>
                <td style="width: 60%;">
                    <table>
                        <tr>
                            <td colspan="2" style="width: 50%;"><strong>Parcela:</strong>
                                {{ $parcela?->descripcion_parcela }}.
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 40%;">
                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Forma de Pago:</strong> <b>
                                    @if ($detalleReservaParcela->forma_pago != null)
                                        {{ $detalleReservaParcela->forma_pago }}
                                    @else
                                        ...
                                    @endif
                                </b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 60%;">
                    <table>
                        <tr>
                            <td colspan="2" style="width: 50%;"><small>Importe Total: </small><b>
                                    @if ($reservaParcela->monto_total != null)
                                        $ {{ number_format($reservaParcela?->monto_total, 2, ',', '.') }}
                                    @else
                                        $ 0.00
                                    @endif
                                </b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td><strong>Importe Entrega: </strong>
                    @if ($detalleReservaParcela->importe_pago != null)
                        $ {{ number_format($detalleReservaParcela?->importe_pago, 2, ',', '.') }}
                    @else
                        $ 0.00
                    @endif
                </td>
                <td> <strong>
                        Concepto de:</strong>
                    {{ $detalleReservaParcela?->concepto_de ?? '..............................' }}
                    @if ($detalleReservaParcela?->leyenda)
                        ({{ $detalleReservaParcela->leyenda }})
                    @endif
                </td>
            </tr>

        </table>

        <table class="info">
            <tr>
                <td class="firma"><small>Firma:</small> ..............................</td>
            </tr>
        </table>
    </div>

</body>

</html>
