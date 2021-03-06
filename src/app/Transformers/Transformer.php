<?php
declare(strict_types=1);

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;

abstract class Transformer {
    abstract public static function transform(Model $entity): array;
}
