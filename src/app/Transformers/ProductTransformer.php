<?php
declare(strict_types=1);

namespace App\Transformers;

use App\Utils\Money;
use App\Utils\Time;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductTransformer extends Transformer
{
    public static function transform(Model $product, Collection|null $products = null): array
    {
        return [
            'productId' => $product->id,
            'name' => $product->name,
            'price' => Money::centsToCurrency((int) $product->price),
            'categoryName' => $product->category->name,
            'createdAt' => Time::dateFormat($product->created_at),
            'updatedAt' => Time::dateFormat($product->updated_at),
        ];
    }
}
