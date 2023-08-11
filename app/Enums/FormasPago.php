<?php

namespace App\Enums;

enum FormasPago:string {
    case EFECTIVO = 'EFECTIVO';
    case TRANSFERENCIA = 'TRANSFERENCIA';
    case DEBITO = 'DEBITO';

    public static function toArray(): array
    {
        return [
            self::EFECTIVO->name => self::EFECTIVO->value,
            self::TRANSFERENCIA->name => self::TRANSFERENCIA->value,
            self::DEBITO->name => self::DEBITO->value,
        ];
    }

}
