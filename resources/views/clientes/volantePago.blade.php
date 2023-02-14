<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Volante Pago</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>



    <div class="container m-5">
        {{-- <div class="row">
            <div class="mb-4 col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%;">
                <h3 class="text-center m-2">ORIGINAL</h3>
            </div>
        </div> --}}

        <div class="row mb-2">
            <div class="float-right">
                <small>N°: <b>{{ $cuota->id_detalle_venta }}</b></small>
                <br>
                <small>Fecha: <b>{{ date('d-m-Y', strtotime($cuota->fecha_pago)) }}</b></small>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%;">
                <img height="100" width="100" alt=""
                    src="https://www.tecnopolo.it/grversion/wp-content/uploads/2019/05/INNOV-logo-Tecnopolo-Tiburtino.jpg" />
                <br>
                <small>INNOV S.R.L</small>
                <br>
                <small>CUIT: 30-71226835-9</small>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%">
                <br>
                <h6><small>Sre/Sra:</small> {{ $cliente->nombre }} {{ $cliente->apellido }}</h6>
                <h6><small>Dni:</small> {{ $cliente->dni }}</h6>
                <h6><small>Domicilio:</small> {{ $cliente->domicilio }}</h6>
                <h6><small>Telefono:</small> {{ $cliente->celular }}</h6>
                <br>
            </div>
        </div>

        <div class="row ">
            <div class="col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%">
                <br>
                <h6><small>Recibí(mos) la suma de:</small> {{ convertDigitsToWord($cuota->total_pago) }} pesos.</h6>
                <h6><small>Cuota N°:</small> {{ $cuota->numero_cuota }}</h6>
                <h6><small>Loteo:</small> {{ $parcela->lote->nombre_lote }}</h6>
                <h6><small>En concepto de:</small>.................</h6>
                <h6><small>Parcela:</small> {{ $parcela->descripcion_parcela }}</h6>
                <h6><small>Ubicación:</small> {{ $parcela->lote->ubicacion }}</h6>
                <h6><small>Dimensión:</small> {{ $parcela->ancho }} x {{ $parcela->largo }}</h6>
                <h6><small>Plan:</small> {{ $venta->cuotas }} Cuota/s</h6>
                <h6>Formas de pago: </h6>
                <h6><small>1-Efectivo</small></h6>
                <h6><small>2-Transferencias Bancaria</small></h6>
                <h6><small>3-Tarjeta de debito</small></h6>
                <h3>IMPORTE: </h3>
                <br>
                <h6 class="d-inline" style="padding-right: 25px">Total: {{ $cuota->total_pago }}
                </h6>
                <h6 class="d-inline" style="padding-right: 25px">Firma:............</h6>
                <h6 class="d-inline" style="padding-right: 25px">Datos de:...........</h6>
                <h6 class="d-inline" style="padding-right: 25px">Aclaración:..............</h6>
                <br>
                <br>
            </div>
        </div>

        {{-- <div class="row">
                <div class="float-right" style="padding-left: 30px">
                    <small>N° <b>{{$cuota->id_detalle_venta}}</b> </small>
                    <br>
                    <small>Fecha: <b>{{date('d-m-Y', strtotime($cuota->fecha_pago))}}</b></small>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12"
                    style="border-color: black;border-style: solid; border-radius: 2%">
                    <small class="float: left; width: 65%;"><b>IMPRENTA ANTONIO NIÑO - Saavedra 234 - Tel. 4433003</b> <br>
                        CUIT
                        20-08231406-8 H.MINIC. N° 046/96 <br>
                        IMPRESO {{getMesEnLetraConAnio()}} <br>
                        DESDE 0001-00001101 AL 0001-00001150</small>
                </div>
            </div> --}}
            <small><i>(Documento no valido como factura)</i></small>
    </div>
  

    </div>



    {{-- DUPLICADO --}}


    <br><br><br><br>
    <div class="container m-5">
        {{-- <div class="row">
            <div class="mb-4 col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%;">
                <h3 class="text-center m-2">DUPLICADO</h3>
            </div>
        </div> --}}

        <div class="row mb-2">
            <div class="float-right">
                <small>N°: <b>{{ $cuota->id_detalle_venta }}</b></small>
                <br>
                <small>Fecha: <b>{{ date('d-m-Y', strtotime($cuota->fecha_pago)) }}</b></small>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%;">
                <img height="100" width="100" alt=""
                    src="https://www.tecnopolo.it/grversion/wp-content/uploads/2019/05/INNOV-logo-Tecnopolo-Tiburtino.jpg" />
                <br>
                <small>INNOV S.R.L</small>
                <br>
                <small>CUIT: 30-71226835-9</small>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%">
                <br>
                <h6><small>Sre/Sra:</small> {{ $cliente->nombre }} {{ $cliente->apellido }}</h6>
                <h6><small>Dni:</small> {{ $cliente->dni }}</h6>
                <h6><small>Domicilio:</small> {{ $cliente->domicilio }}</h6>
                <h6><small>Telefono:</small> {{ $cliente->celular }}</h6>
                <br>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12"
                style="border-color: black;border-style: solid; border-radius: 2%">
                <br>
                <h6><small>Recibí(mos) la suma de:</small> {{ convertDigitsToWord($cuota->total_pago) }} pesos.</h6>
                <h6><small>Cuota N°:</small> {{ $cuota->numero_cuota }}</h6>
                <h6><small>Loteo:</small> {{ $parcela->lote->nombre_lote }}</h6>
                <h6><small>En concepto de:</small>.................</h6>
                <h6><small>Parcela:</small> {{ $parcela->descripcion_parcela }}</h6>
                <h6><small>Ubicación:</small> {{ $parcela->lote->ubicacion }}</h6>
                <h6><small>Dimensión:</small> {{ $parcela->ancho }} x {{ $parcela->largo }}</h6>
                <h6><small>Plan:</small> {{ $venta->cuotas }} Cuota/s</h6>
                <h6>Formas de pago: </h6>
                <h6><small>1-Efectivo</small></h6>
                <h6><small>2-Transferencias Bancaria</small></h6>
                <h6><small>3-Tarjeta de debito</small></h6>
                <h3>IMPORTE: </h3>
                <br>
                <h6 class="d-inline" style="padding-right: 25px">Total: {{ $cuota->total_pago }}
                </h6>
                <h6 class="d-inline" style="padding-right: 25px">Firma:............</h6>
                <h6 class="d-inline" style="padding-right: 25px">Datos de:...........</h6>
                <h6 class="d-inline" style="padding-right: 25px">Aclaración:..............</h6>
                <br>
                <br>
            </div>
        </div>

        {{-- <div class="row">
                <div class="float-right" style="padding-left: 30px">
                    <small>N° <b>{{$cuota->id_detalle_venta}}</b> </small>
                    <br>
                    <small>Fecha: <b>{{date('d-m-Y', strtotime($cuota->fecha_pago))}}</b></small>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12"
                    style="border-color: black;border-style: solid; border-radius: 2%">
                    <small class="float: left; width: 65%;"><b>IMPRENTA ANTONIO NIÑO - Saavedra 234 - Tel. 4433003</b> <br>
                        CUIT
                        20-08231406-8 H.MINIC. N° 046/96 <br>
                        IMPRESO {{getMesEnLetraConAnio()}} <br>
                        DESDE 0001-00001101 AL 0001-00001150</small>
                </div>
            </div> --}}
            <small><i>(Documento no valido como factura)</i></small>
    </div>
   

    </div>
</body>

</html>
