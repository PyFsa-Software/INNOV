<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

function convertDigitsToWord(int $number): string
{
    $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return $f->format($number);

}

function getMesEnLetraConAnio($anio = '')
{
    $fecha = Carbon::parse($anio ?? date('d-m-Y'));
    return Str::upper($fecha->monthName) . "/" . $fecha->year;

}
function fechaIgualMesActual($fecha)
{
    return Carbon::create($fecha)->isSameMonth();
}