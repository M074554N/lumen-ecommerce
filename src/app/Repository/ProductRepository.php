<?php
declare(strict_types=1);

namespace App\Repository;

use App\Exceptions\NotFoundException;
use App\Models\Product;
use App\Transformers\ProductTransformer;

class ProductRepository
{
    public function all(): array
    {
        $products = Product::simplePaginate(Product::LIMIT);

        $products->transform(
            function ($product) {
                return ProductTransformer::transform($product);
            }
        );

        return $products->toArray();
    }

    /**
     * @throws NotFoundException
     */
    public function getProduct(int $productId): array
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new NotFoundException();
        }

        return ProductTransformer::transform($product);
    }

    public function getProductsByCategory(int $categoryId): array
    {
        $products = Product::where('category_id', $categoryId)->simplePaginate(Product::LIMIT);

        $products->transform(
            function ($product) {
                return ProductTransformer::transform($product);
            }
        );

        return $products->toArray();
    }

    public function store(array $inputData): array
    {
        return ProductTransformer::transform(Product::create($inputData));
    }

    public function update(Product $product, array $inputData): array
    {
        $product->update($inputData);
        return ProductTransformer::transform($product);
    }

    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }
}
