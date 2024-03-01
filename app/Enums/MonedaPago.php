<?php

namespace App\Enums;

enum MonedaPago:string {
    case DOLARES = 'DOLARES';
    case PESOS = 'PESOS';

    public static function toArray(): array
    {
        return [
            self::DOLARES->name => self::DOLARES->value,
            self::PESOS->name => self::PESOS->value,
        ];
    }

}
