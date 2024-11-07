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


function getFechaActualizacion()
{

    //fecha actual de prueba
     $fechaActualizacionPrueba = Carbon::create('2023-10-12')->format('Y-m');

    //fecha actual del sistema
     $fechaActual = Carbon::now()->format('Y-m-d');
        
    
        return $fechaActual;

}
function getFechaActual()
{

    //fecha actual de prueba
     $fechaActualPrueba = Carbon::create('2023-09-12')->format('Y-m');

    //fecha actual del sistema
     $fechaActual = Carbon::now()->format('Y-m');
        
    
     return $fechaActual;

}
function getFechaActualEditarCuota()
{

    //fecha actual de prueba
     $fechaActualPrueba = Carbon::create('2023-10-12')->format('Y-m');

    //fecha actual del sistema
    $fechaActual = Carbon::now()->format('Y-m');
        
    
        return $fechaActual;

}