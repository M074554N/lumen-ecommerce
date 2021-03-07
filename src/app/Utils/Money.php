<?php
declare(strict_types=1);

namespace App\Utils;

class Money
{
    public static function centsToCurrency(int $amount): float
    {
        return (float) $amount / 100;
    }
}
