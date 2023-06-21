<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Volante Pago</title>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> --}}


    <style>
        ​ body {
            margin: 0;
        }

        main {
            font-family: 'Arial', sans-serif;
            margin-top: -20px;
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
            width: 100px;
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
            text-align: left;
            padding-left: 22px;
            padding-right: 195px;
            padding-bottom: 5px;
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
            <div class="content-fecha" width="150">
                <small>N°: <b>1717</b></small>
                <br>
                <small>Fecha: <b>12-05-2023</b></small>
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
                    <small>CUIT: 30-71226835-9</small>
                </div>
            </div>
            <br>

            <div class="content-venta">
                <div class="nombre">
                    <small>Sr/Sra: </small><b>{{ $cliente->nombre }} {{ $cliente->apellido }}.</b>
                </div>
                <br>
                <div class="telefono">
                    <small>Telefono: </small> <b>{{ $cliente->celular }}.</b>
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
                    <small>Recibí(mos) la suma de: </small><b>{{ convertDigitsToWord($cuota->total_pago) }} pesos.</b>
                </div>
                <br>
                <div class="info-loteo">
                    <small>Loteo: </small><b>{{ $parcela->lote->nombre_lote }}.</b>
                </div>
                <div class="info-parcela">
                    <small>Parcela: </small><b> {{ $parcela->descripcion_parcela }}.</b>
                </div>
                <br>
                <div class="info-parcela">
                    <small>Dimensión: </small><b>{{ $parcela->ancho }} x {{ $parcela->largo }}.</b>
                </div>
                <div class="info-parcela">
                    <small>Manzana:</small><b>{{ $parcela->manzana }}.</b>
                </div>
                <div class="info-parcela">
                    <small>Ubicación: </small><b>{{ $parcela->lote->ubicacion }}.</b>
                </div>
                <br>
                <div class="info-parcela">
                    <small>Cuota N°: </small><b>{{ $cuota->numero_cuota }}</b>
                </div>
                <div class="info-parcela">
                    <small>Plan: </small><b>{{ $venta->cuotas }} Cuota/s.</b>
                </div>
            </div>
            <div class="importe-total">
                <small>Importe Total: </small><b>$ {{ number_format($cuota->total_pago, 2, ',', '.') }}</b>
            </div>
            <br>
            <div class="firma">
                <small>Firma: ..............................</b>
            </div>
        </div>
    </main>
    {{-- 
    <main>
        <div class="fecha">
            <div class="content-fecha" width="150">
                <small>N°: <b>{{ $cuota->numero_recibo }}</b></small>
                <br>
                <small>Fecha: <b>{{ date('d-m-Y', strtotime($cuota->fecha_pago)) }}</b></small>
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
                    <small>CUIT: 30-71226835-9</small>
                </div>
            </div>
            <br>

            <div class="content-venta">
                <div class="nombre">
                    <small>Sr/Sra: </small><b>{{ $cliente->nombre }} {{ $cliente->apellido }}.</b>
                </div>
                <div class="telefono">
                    <small>Telefono: </small> <b>{{ $cliente->celular }}.</b>
                </div>
                <br>
                <div class="dni">
                    <small>Dni: </small> <b>{{ $cliente->dni }}.</b>
                </div>
                <div class="domicilio">
                    <small>Domicilio: </small><b>{{ $cliente->domicilio }}.</b>
                </div>
                <br>
                <div class="pago">
                    <small>Recibí(mos) la suma de: </small><b>{{ convertDigitsToWord($cuota->total_pago) }} pesos.</b>
                </div>
                <br>
                <div class="info-loteo">
                    <small>Loteo: </small><b>{{ $parcela->lote->nombre_lote }}.</b>
                </div>
                <div class="info-parcela">
                    <small>Parcela: </small><b> {{ $parcela->descripcion_parcela }}.</b>
                </div>
                <br>
                <div class="info-parcela">
                    <small>Dimensión: </small><b>{{ $parcela->ancho }} x {{ $parcela->largo }}.</b>
                </div>
                <div class="info-parcela">
                    <small>Manzana:</small><b>{{ $parcela->manzana }}.</b>
                </div>
                <div class="info-parcela">
                    <small>Ubicación: </small><b>{{ $parcela->lote->ubicacion }}.</b>
                </div>
                <br>
                <div class="info-parcela">
                    <small>Cuota N°: </small><b>{{ $cuota->numero_cuota }}.</b>
                </div>
                <div class="info-parcela">
                    <small>Plan: </small><b>{{ $venta->cuotas }} Cuota/s.</b>
                </div>
            </div>
            <div class="importe-total">
                <small>Importe Total: </small><b>$ 
                    {{ number_format($cuota->total_pago, 2, ',', '.') }}</b>
            </div>
            <br>
            <div class="firma">
                <small>Firma: ..............................</b>
            </div>

        </div>
    </main> --}}

</body>

</html>
