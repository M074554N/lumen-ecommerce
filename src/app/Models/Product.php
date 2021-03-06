<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const LIMIT = 10;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
