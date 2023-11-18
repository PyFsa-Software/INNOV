<?php
namespace App\Enums;
enum ConceptoDeVenta:string {
    case RESERVA = 'RESERVA';
    case SENIA = 'SEÑA';

    public static function toArray(): array
    {
        return [
            self::RESERVA->name => self::RESERVA->value,
            self::SENIA->name => self::SENIA->value,
        ];
    }

}