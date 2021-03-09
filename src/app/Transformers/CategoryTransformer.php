<?php
declare(strict_types=1);

namespace App\Transformers;

use App\Utils\Time;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryTransformer extends Transformer
{
    public static function transform(Model $category): array
    {
        return [
            'categoryId' => $category->id,
            'categoryName' => $category->name,
            'ProductsCount' => $category->products()->count(),
            'createdAt' => Time::dateFormat($category->created_at),
            'updatedAt' => Time::dateFormat($category->updated_at),
        ];
    }
}
