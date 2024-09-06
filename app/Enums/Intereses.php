<?php

namespace App\Enums;

enum Intereses: string {
    case INTERES = '0';
    case INTERES_BASE = '0.50';
    case INTERES_MEDIO = '1.00';
    case INTERES_ALTO = '1.50';

    public static function toArray(): array
    {
        return [
            self::INTERES->name => (float) self::INTERES->value,
            self::INTERES_BASE->name => (float) self::INTERES_BASE->value,
            self::INTERES_MEDIO->name => (float) self::INTERES_MEDIO->value,
            self::INTERES_ALTO->name => (float) self::INTERES_ALTO->value,
        ];
    }
}