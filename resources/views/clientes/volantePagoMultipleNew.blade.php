<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .info-parcela {
            font-size: 13px;
            margin-top: 10px;
            margin-bottom: 10px;
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
                    <div style="width: 35%; margin-left: auto; text-align: left;">
                        <small>N°: <b>{{ $detalleVentas[0]->numero_recibo }}</b></small><br>
                        <small>Fecha: <b>{{ date('d-m-Y', strtotime($detalleVentas[0]->fecha_pago)) }}</b></small><br>
                        <small>CEL: 3704-504731</small><br>
                        <small>España 101, Galería Orquin, local 10 P.B</small>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="header">COMPROBANTE DE PAGO</td>
            </tr>
        </table>

        <table class="info">
            <tr>
                <td><strong>Sr/Sra:</strong> {{ getFormatNombreApellido($cliente->nombre, $cliente->apellido) }}</td>
                <td><strong>DNI:</strong> {{ $cliente->dni }}</td>
            </tr>
        </table>
        <table class="info">
            <tr>
                <td style="width: 40%;">
                    <table>
                        <tr>
                            <td><strong>Loteo:</strong> {{ $venta->parcela->lote->nombre_lote }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 60%;">

                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Ubicación:</strong> {{ $venta->parcela->lote->ubicacion }}
                            </td>
                            <td style="width: 50%;"><strong>Dimensión:</strong> {{ $venta->parcela->ancho }} x
                                {{ $venta->parcela->largo }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="width: 40%;">
                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Manzana:</strong> {{ $venta->parcela->manzana }}</td>

                        </tr>
                    </table>
                </td>
                <td style="width: 60%;">
                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Parcela:</strong> {{ $venta->parcela->descripcion_parcela }}
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 40%;">
                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Plan:</strong> {{ $venta->cuotas }} Cuota/s.</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 60%;">
                    <table>
                        <tr>
                            <td style="width: 50%;"><strong>Cuota N°: </strong><b>{{ $numeroPrimeraCuota }}</b> al
                                <b>{{ $numeroUltimaCuota }}</b>
                            </td>
                            <td colspan="2"><strong>Actualizacion:</strong>
                                {{ $venta->update_period }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Recibí(mos) la suma de:</strong>
                    {{ $detalleVentas[0]?->moneda_pago ?? 'PESOS' }}
                    {{ convertDigitsToWord($detalleVentas[0]?->total_pago) }}
                </td>
            </tr>
            <tr>
                <td><strong>Importe Total:</strong> $
                    {{ number_format($detalleVentas[0]?->total_pago, 2, ',', '.') }}
                </td>
                <td><strong>Concepto de:</strong>
                    {{ $concepto_de ?? '..............................' }}</td>
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
