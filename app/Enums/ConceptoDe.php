<?php

namespace App\Enums;

enum ConceptoDe:string {
    case CUOTA = 'CUOTA';
    case SENIA = 'SEÑA';
    case RESERVA = 'RESERVA';

    public static function toArray(): array
    {
        return [
            self::CUOTA->name => self::CUOTA->value,
            self::SENIA->name => self::SENIA->value,
            self::RESERVA->name => self::RESERVA->value,
        ];
    }

}
enum ConceptoDeVenta:string {
    case CUOTA = 'CUOTA';
    case SENIA = 'SEÑA';

    public static function toArray(): array
    {
        return [
            self::CUOTA->name => self::CUOTA->value,
            self::SENIA->name => self::SENIA->value,
        ];
    }

}