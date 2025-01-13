<?php

namespace App\Enums;

enum PeriodosActualizacion:string {
    case BIMESTRAL = 'BIMESTRAL';
    case TRIMESTRAL = 'TRIMESTRAL';
    case SEMESTRAL = 'SEMESTRAL';

    public static function toArray(): array
    {
        return [
            self::BIMESTRAL->name => self::BIMESTRAL->value,
            self::TRIMESTRAL->name => self::TRIMESTRAL->value,
            self::SEMESTRAL->name => self::SEMESTRAL->value,
        ];
    }

}