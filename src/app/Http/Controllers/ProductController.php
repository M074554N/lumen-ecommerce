<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ErrorCodes;
use App\Exceptions\NotFoundException;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends BaseController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * @OA\Get(
     *     path="/v1/products",
     *     summary="Returns a list of products",
     *     description="Returns a list of products",
     *     @OA\Parameter(
     *          in="query",
     *          name="page",
     *          description="The page number",
     *          example="1",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Founde",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'page' => 'numeric|min:1',
            ]);

            $products = $this->productRepository->all();

            return $this->success([
                'total' => Product::count(),
                'products' => $products,
            ]);
        } catch (ValidationException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::PRODUCT_VALIDATION_ERROR), [
                'errorCode' => ErrorCodes::PRODUCT_VALIDATION_ERROR,
                'exceptionMessage' => $exception->getMessage(),
                'exceptionData' => $exception->getResponse()->getContent(),
                'requestData' => $request->all(),
            ]);

            return $this->error(
                ErrorCodes::PRODUCT_VALIDATION_ERROR,
                ErrorCodes::translate(ErrorCodes::PRODUCT_VALIDATION_ERROR),
                details: json_decode($exception->getResponse()->getContent(), true)
            );
        } catch (\Exception $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::GENERAL_ERROR), [
                'errorCode' => ErrorCodes::GENERAL_ERROR,
                'exceptionMessage' => $exception->getMessage(),
            ]);

            return $this->error(
                ErrorCodes::GENERAL_ERROR,
                ErrorCodes::translate(ErrorCodes::GENERAL_ERROR),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/products/{productId}",
     *     summary="Get product information",
     *     description="Get product information",
     *     @OA\Parameter(
     *          in="path",
     *          name="productId",
     *          description="The product ID",
     *          example="150",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function show(int $productId): JsonResponse
    {
        try {
            $product = $this->productRepository->getProduct($productId);

            return $this->success($product);
        } catch (NotFoundException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::PRODUCT_NOT_FOUND), [
                'errorCode' => ErrorCodes::PRODUCT_NOT_FOUND,
                'exceptionMessage' => $exception->getMessage(),
                'productId' => $productId,
            ]);

            return $this->error(
                ErrorCodes::PRODUCT_NOT_FOUND,
                ErrorCodes::translate(ErrorCodes::PRODUCT_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::GENERAL_ERROR), [
                'errorCode' => ErrorCodes::GENERAL_ERROR,
                'exceptionMessage' => $exception->getMessage(),
            ]);

            return $this->error(
                ErrorCodes::GENERAL_ERROR,
                ErrorCodes::translate(ErrorCodes::GENERAL_ERROR),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/products",
     *     summary="Create a new product",
     *     description="Create a new product",
     *     @OA\RequestBody(
     *          required=true,
     *          description="New product object",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property (
     *                      property="name",
     *                      description="Product Name",
     *                      example="New Product",
     *                      @OA\Schema(
     *                          type="string",
     *                      ),
     *                  ),
     *                  @OA\Property (
     *                      property="price",
     *                      description="Product Price",
     *                      example="3531",
     *                      @OA\Schema(
     *                          type="integer",
     *                      ),
     *                  ),
     *                  @OA\Property (
     *                      property="categoryId",
     *                      description="Category ID",
     *                      example="5",
     *                      @OA\Schema(
     *                          type="integer",
     *                      ),
     *                  ),
     *              ),
     *          ),
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
    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, $this->validationRules());

            $product = $this->productRepository->store($request->all());

            return $this->success([
                'product' => $product,
            ], Response::HTTP_CREATED);
        } catch (ValidationException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::PRODUCT_VALIDATION_ERROR), [
                'errorCode' => ErrorCodes::PRODUCT_VALIDATION_ERROR,
                'exceptionMessage' => $exception->getMessage(),
                'exceptionData' => $exception->getResponse()->getContent(),
                'requestData' => $request->all(),
            ]);

            return $this->error(
                ErrorCodes::PRODUCT_VALIDATION_ERROR,
                ErrorCodes::translate(ErrorCodes::PRODUCT_VALIDATION_ERROR),
                details: json_decode($exception->getResponse()->getContent(), true)
            );
        } catch (\Exception $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::GENERAL_ERROR), [
                'errorCode' => ErrorCodes::GENERAL_ERROR,
                'exceptionMessage' => $exception->getMessage(),
            ]);

            return $this->error(
                ErrorCodes::GENERAL_ERROR,
                ErrorCodes::translate(ErrorCodes::GENERAL_ERROR),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/v1/products/{productId}",
     *     summary="Update a product",
     *     description="Update a product",
     *     @OA\Parameter(
     *          in="path",
     *          name="productId",
     *          description="The product ID",
     *          example="1",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="New product data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property (
     *                      property="name",
     *                      description="New Product Name",
     *                      example="Iphone 12 Max",
     *                      @OA\Schema(
     *                          type="string",
     *                      ),
     *                  ),
     *                  @OA\Property (
     *                      property="price",
     *                      description="New Product Price",
     *                      example="2525",
     *                      @OA\Schema(
     *                          type="integer",
     *                      ),
     *                  ),
     *                  @OA\Property (
     *                      property="categoryId",
     *                      description="New Category ID",
     *                      example="5",
     *                      @OA\Schema(
     *                          type="integer",
     *                      ),
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response="202",
     *          description="Updated Successfully",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Bad Request",
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function update(int $productId, Request $request): JsonResponse
    {
        try {
            $product = Product::find($productId);

            if (!$product) {
                throw new NotFoundException();
            }

            $this->validate($request, $this->validationRules());

            $product = $this->productRepository->update($product, $request->all());

            return $this->success([
                'product' => $product,
            ], Response::HTTP_ACCEPTED);
        } catch (NotFoundException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::PRODUCT_NOT_FOUND), [
                'errorCode' => ErrorCodes::PRODUCT_NOT_FOUND,
                'exceptionMessage' => $exception->getMessage(),
                'productId' => $productId,
            ]);

            return $this->error(
                ErrorCodes::PRODUCT_NOT_FOUND,
                ErrorCodes::translate(ErrorCodes::PRODUCT_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        } catch (ValidationException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::PRODUCT_VALIDATION_ERROR), [
                'errorCode' => ErrorCodes::PRODUCT_VALIDATION_ERROR,
                'exceptionMessage' => $exception->getMessage(),
                'exceptionData' => $exception->getResponse()->getContent(),
                'requestData' => $request->all(),
            ]);

            return $this->error(
                ErrorCodes::PRODUCT_VALIDATION_ERROR,
                ErrorCodes::translate(ErrorCodes::PRODUCT_VALIDATION_ERROR),
                details: json_decode($exception->getResponse()->getContent(), true)
            );
        } catch (\Exception $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::GENERAL_ERROR), [
                'errorCode' => ErrorCodes::GENERAL_ERROR,
                'exceptionMessage' => $exception->getMessage(),
            ]);

            return $this->error(
                ErrorCodes::GENERAL_ERROR,
                ErrorCodes::translate(ErrorCodes::GENERAL_ERROR),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/products/{productId}",
     *     summary="Delete a product",
     *     description="Delete a product",
     *     @OA\Response(
     *          response="204",
     *          description="Deleted Successfully",
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function delete(int $productId)
    {
        try {
            $product = Product::find($productId);

            if (!$product) {
                throw new NotFoundException();
            }

            $this->productRepository->delete($product);

            return $this->success(responseCode: Response::HTTP_NO_CONTENT);
        } catch (NotFoundException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::PRODUCT_NOT_FOUND), [
                'errorCode' => ErrorCodes::PRODUCT_NOT_FOUND,
                'exceptionMessage' => $exception->getMessage(),
                'productId' => $productId,
            ]);

            return $this->error(
                ErrorCodes::PRODUCT_NOT_FOUND,
                ErrorCodes::translate(ErrorCodes::PRODUCT_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::GENERAL_ERROR), [
                'errorCode' => ErrorCodes::GENERAL_ERROR,
                'exceptionMessage' => $exception->getMessage(),
                'productId' => $productId,
            ]);

            return $this->error(
                ErrorCodes::GENERAL_ERROR,
                ErrorCodes::translate(ErrorCodes::GENERAL_ERROR),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function validationRules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'price' => 'required|min:0|integer',
            'category_id' => 'required|numeric|exists:categories,id',
        ];
    }
}
