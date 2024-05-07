<?php

namespace App\Enums;

enum DomicilioAlquiler:string {
    case FAMILIAR = 'FAMILIAR';
    case COMERCIAL = 'COMERCIAL';

    public static function toArray(): array
    {
        return [
            self::FAMILIAR->name => self::FAMILIAR->value,
            self::COMERCIAL->name => self::COMERCIAL->value,
        ];
    }
}
