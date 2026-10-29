<?php

namespace App\Enum;

enum TransactionType: string
{
    case In = 'IN';
    case Out = 'OUT';

    public function label(): string
    {
        return match ($this) {
            self::In => 'Stock in',
            self::Out => 'Stock out',
        };
    }
}