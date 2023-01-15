@foreach ($lotes as $lote)

<h3>{{$lote->nombre_lote}}</h3>

@php
$total = 0;
@endphp
<table>
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Parcela</th>
            <th>Numero Cuota</th>
            <th>Pago Mensual</th>
            <th>Porcentaje</th>
            <th>Dueño</th>
            <th>Diego</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cuotasAgrupadas[$lote->id_lote] as $cuotas)

        @php
        $descuento = $cuotas->total_pago * 0.20;
        @endphp

        <tr>
            <td>{{$cuotas->nombre}} {{$cuotas->apellido}}</td>
            <td>{{$cuotas->descripcion_parcela}}</td>
            <td>{{$cuotas->numero_cuota}}</td>
            <td>{{$cuotas->total_pago}}</td>
            <td>20%</td>
            <td>{{$cuotas->total_pago - $descuento}}</td>
            <td>{{$descuento}}</td>
        </tr>

        @php
        $total += $cuotas->total_pago;
        @endphp
        @endforeach
    </tbody>
</table>
<h2>Total: {{$total}}</h2>

@endforeach