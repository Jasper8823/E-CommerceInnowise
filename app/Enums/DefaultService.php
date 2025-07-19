<?php

declare(strict_types=1);

namespace App\Enums;

enum DefaultService: string
{
    case SERVICE = 'Service';
    case DELIVERY = 'Delivery';
    case INSTALLATION = 'Installation';
    case CONFIGURATION = 'Configuration';

    public static function names(): array
    {
        return array_column(self::cases(), 'value');
    }
}
