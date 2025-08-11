<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionStatus: string
{
    case PROCESSED = 'PROCESSED';
    case FAILED = 'FAILED';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
