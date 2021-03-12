<?php
declare(strict_types=1);

namespace App\Repository;

use App\Exceptions\NotFoundException;
use App\Models\Category;
use App\Transformers\CategoryTransformer;

class CategoryRepository
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function all(): array
    {
        $categories = Category::orderBy('name')->get();

        $categories->transform(function ($category) {
            return CategoryTransformer::transform($category);
        });

        return $categories->toArray();
    }

    /**
     * @throws NotFoundException
     */
    public function get(int $categoryId): array
    {
        $category = Category::find($categoryId);

        if (!$category) {
            throw new NotFoundException();
        }

        return CategoryTransformer::transform($category);
    }

    /**
     * @throws NotFoundException
     */
    public function getCategoryWithProducts(int $categoryId): array
    {
        $category = Category::find($categoryId);

        if (!$category) {
            throw new NotFoundException();
        }

        $response = CategoryTransformer::transform($category);
        $response['products'] = $this->productRepository->getProductsByCategory($categoryId);

        return $response;
    }

    public function store(array $inputData): array
    {
        return CategoryTransformer::transform(Category::create($inputData));
    }

    public function update(Category $category, array $inputData): array
    {
        $category->update($inputData);
        return CategoryTransformer::transform($category);
    }

    public function delete(Category $category): ?bool
    {
        return $category->delete();
    }
}
