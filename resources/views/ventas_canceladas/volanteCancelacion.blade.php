<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Volante Cancelación</title>
    <style>
        body {
            margin: 0;
        }

        main {
            font-family: 'Arial', sans-serif;
            margin-top: 0%;
            border-color: black;
            border-style: solid;
            border-radius: 2%;
            margin: 2%;
        }

        .content-fecha {
            text-align: left;
            padding-top: 15px;
        }

        .fecha {
            font-size: 13px;
            float: right;
            padding-right: 10px;
        }

        .logo {
            padding: 15px;
            text-align: left;

        }


        .content-empresa {
            text-align: left;
            padding-left: 17px;
            width: 120px;
        }

        .nombre-empresa {
            font-size: 13px;
            text-align: center;
        }

        .info-empresa {
            text-align: center;
            font-size: 13px;
        }

        .nombre {
            font-size: 13px;
            display: inline-block;
            text-align: left;
            padding-right: 200px;
            padding-left: 22px;
            padding-bottom: 5px;
        }

        .telefono {
            font-size: 13px;
            display: inline-block;
            padding-left: 22px;
            text-align: left;
            padding-bottom: 5px;
        }

        .dni {
            font-size: 13px;
            display: inline-block;
            text-align: left;
            padding-right: 175px;
            padding-left: 22px;
            padding-bottom: 5px;
        }

        .domicilio {
            font-size: 13px;
            display: inline-block;
            text-align: left;
            padding-right: 175px;
            padding-left: 22px;
            padding-bottom: 5px;
        }

        .pago {
            font-size: 13px;
            display: inline-block;
            text-align: left;
            padding-left: 22px;
            padding-bottom: 10px;
        }

        .importe-total {
            font-size: 13px;
            display: inline-block;
            text-align: left;
            padding-left: 22px;
            padding-bottom: 10px;
        }

        .info-parcela {
            font-size: 13px;
            display: inline-block;
            text-align: left;
            padding-left: 20px;
            padding-bottom: 5px;
        }

        .info-loteo {
            font-size: 13px;
            display: inline-block;
            /* text-align: left; */
            /* padding-left: 22px;
            padding-right: 195px;
            padding-bottom: 5px; */
            margin-left: 20px;
        }

        .info-loteo>b {
            padding-right: 10px;
        }

        .firma {
            font-size: 13px;
            text-align: right;
            padding-right: 20px;
            padding-bottom: 10px;
        }
    </style>

</head>

<body>
    <main>
        <div class="fecha">
            <div class="content-fecha" width="180">
                <small>N°: <b>{{ $detalleVentas[0]->numero_recibo }}</b></small>
                <br>
                <small>Fecha: <b>{{ date('d-m-Y', strtotime($detalleVentas[0]->fecha_pago)) }}</b></small>
                <br>
                <small>CEL: 3704-504731</small>
                <br>
                <small>España 101,Galería Orquin, local 10 P.B</small>
            </div>
        </div>
        <div class="contenido">
            <div class="logo">
                {!! $html !!}
            </div>
            <div class="content-empresa">
                <div class="info-empresa">
                    <small class="nombre-empresa"><b> INNOV S.R.L </b></small>
                    <br>
                    <small>CUIT: 30-71804836-9</small>
                </div>
            </div>
            <br>

            <div class="content-venta">
                <div class="nombre">
                    <small>Sr/Sra: </small><b>{{ getFormatNombreApellido($cliente->nombre, $cliente->apellido) }}.</b>
                </div>
                <br>
                <div class="dni">
                    <small>Dni: </small> <b>{{ $cliente->dni }}.</b>
                </div>
                <br>
                <div class="domicilio">
                    <small>Domicilio: </small><b>{{ $cliente->domicilio }}.</b>
                </div>
                <br>
                <div class="pago">
                    <small>Recibí(mos) la suma de: </small><b>{{ $detalleVentas[0]?->moneda_pago ?? 'PESOS' }}
                        {{ convertDigitsToWord($totalPago) }}.</b>
                </div>
                <br>
                <div class="info-loteo">
                    <small>Loteo: </small><b>{{ $venta->parcela->lote->nombre_lote }}.</b>
                    <small>Parcela: </small><b> {{ $venta->parcela->descripcion_parcela }}.</b>
                    <small>Manzana:</small><b>{{ $venta->parcela->manzana }}.</b>
                    <small>Ubicación: </small><b>{{ $venta->parcela->lote->ubicacion }}.</b>
                </div>
                {{-- <div class="info-parcela">
                    <small>Parcela: </small><b> {{ $venta->parcela->descripcion_parcela }}.</b>
                </div> --}}
                <br>
                <div class="info-parcela">
                    <small>Dimensión: </small><b>{{ $venta->parcela->ancho }} x {{ $venta->parcela->largo }}.</b>
                </div>
                {{-- <div class="info-parcela">
                    <small>Manzana:</small><b>{{ $venta->parcela->manzana }}.</b>
                </div> --}}
                {{-- <div class="info-parcela">
                    <small>Ubicación: </small><b>{{ $venta->parcela->lote->ubicacion }}.</b>
                </div> --}}
                <br>
                <div class="info-parcela">
                    <small>Plan: </small><b>{{ $venta->cuotas }} Cuota/s. CANCELACIÓN.</b>
                </div>
            </div>
            <div class="importe-total">
                <small>Importe Total: </small><b>$ {{ number_format($totalPago, 2, ',', '.') }}.</b>
            </div>
            <br>
            <div class="importe-total">
                <small>Concepto de: <b>{{ $concepto_de ?? '..............................' }}.</b>
            </div>
            <br>
            <div class="firma">
                <small>Firma: ..............................
            </div>
        </div>
    </main>
</body>

</html>
