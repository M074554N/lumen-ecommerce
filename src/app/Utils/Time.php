<?php
declare(strict_types=1);

namespace App\Utils;

use Illuminate\Support\Carbon;

class Time
{
    public static function dateFormat(Carbon $time)
    {
        return $time->format('Y-m-d h:i:s');
    }
}
