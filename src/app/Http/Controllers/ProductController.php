<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/products",
     *     summary="Returns a list of products",
     *     description="Returns a list of products",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $products = Product::limit(10)->get();

        $products = ProductTransformer::transformList($products);

        return $this->success([
            'total' => Product::count(),
            'products' => $products,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/v1/products/{productId}",
     *     summary="Create a new product",
     *     description="Returns a list of products",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     )
     * )
     */
    public function show()
    {

    }

    /**
     * @OA\Post(
     *     path="/v1/products",
     *     summary="Create a new product",
     *     description="Returns a list of products",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     ),
     *     @OA\Response(
     *          response="201",
     *          description="Created Successfully",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Bad Request",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function store(Request $request)
    {

    }

    /**
     * @OA\Put(
     *     path="/v1/products/{productId}",
     *     summary="Update a product",
     *     description="Returns a list of products",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     )
     * )
     */
    public function update()
    {

    }

    /**
     * @OA\Delete(
     *     path="/v1/products/{productId}",
     *     summary="Delete a product",
     *     description="Returns a list of products",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     )
     * )
     */
    public function delete()
    {

    }
}
