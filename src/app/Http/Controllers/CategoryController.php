<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ErrorCodes;
use App\Exceptions\NotFoundException;
use App\Models\Category;
use App\Repository\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CategoryController extends BaseController
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    /**
     * @OA\Get(
     *     path="/v1/categories",
     *     summary="Get a list of categories",
     *     description="Get all the categories in the system",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryRepository->all();

            return $this->success([
                'total' => Category::count(),
                'categories' => $categories,
            ]);
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
     *     path="/v1/categories/{categoryId}",
     *     summary="Show category data",
     *     description="Show category information",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function show(int $categoryId): JsonResponse
    {
        try {
            $category = $this->categoryRepository->get($categoryId);

            return $this->success($category);
        } catch (NotFoundException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND), [
                'errorCode' => ErrorCodes::CATEGORY_NOT_FOUND,
                'exceptionMessage' => $exception->getMessage(),
                'categoryId' => $categoryId,
            ]);

            return $this->error(
                ErrorCodes::CATEGORY_NOT_FOUND,
                ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND),
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
     * @OA\Get(
     *     path="/v1/categories/{categoryId}/products",
     *     summary="Show category data",
     *     description="Show category information + related products",
     *     @OA\Parameter(
     *          in="path",
     *          name="categoryId",
     *          description="The category ID",
     *          example="1",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
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
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function showProducts(int $categoryId): JsonResponse
    {
        try {
            $category = $this->categoryRepository->getCategoryWithProducts($categoryId);

            return $this->success($category);
        } catch (NotFoundException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND), [
                'errorCode' => ErrorCodes::CATEGORY_NOT_FOUND,
                'exceptionMessage' => $exception->getMessage(),
                'categoryId' => $categoryId,
            ]);

            return $this->error(
                ErrorCodes::CATEGORY_NOT_FOUND,
                ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND),
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
     *     path="/v1/categories",
     *     summary="Create a new category",
     *     description="Create a new category",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     ),
     *     @OA\Response(
     *          response="201",
     *          description="Created Successfully",
     *     ),
     *     @OA\Response(
     *          response="422",
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
            $this->validate($request, [
                'name' => 'required|min:3|max:120|unique:categories,name',
            ]);

            $category = $this->categoryRepository->store($request->all());

            return $this->success([
                'category' => $category,
            ], Response::HTTP_CREATED);
        } catch (ValidationException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::CATEGORY_VALIDATION_ERROR), [
                'errorCode' => ErrorCodes::CATEGORY_VALIDATION_ERROR,
                'exceptionMessage' => $exception->getMessage(),
                'exceptionData' => $exception->getResponse()->getContent(),
                'requestData' => $request->all(),
            ]);

            return $this->error(
                ErrorCodes::CATEGORY_VALIDATION_ERROR,
                ErrorCodes::translate(ErrorCodes::CATEGORY_VALIDATION_ERROR),
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
     *     path="/v1/categories/{categoryId}",
     *     summary="Update a category",
     *     description="Send a request to update a category",
     *     @OA\Response(
     *          response="204",
     *          description="Updated Successfully",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Bad Request",
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Foundt",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function update(int $categoryId, Request $request): JsonResponse
    {
        try {
            $category = Category::find($categoryId);

            if (!$category) {
                throw new NotFoundException();
            }

            $this->validate($request, [
                'name' => 'required|min:3|max:120|unique:categories,name',
            ]);

            $category = $this->categoryRepository->update($category, $request->all());

            return $this->success([
                'category' => $category,
            ], Response::HTTP_NO_CONTENT);
        } catch (NotFoundException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND), [
                'errorCode' => ErrorCodes::CATEGORY_NOT_FOUND,
                'exceptionMessage' => $exception->getMessage(),
                'categoryId' => $categoryId,
            ]);

            return $this->error(
                ErrorCodes::CATEGORY_NOT_FOUND,
                ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        } catch (ValidationException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::CATEGORY_VALIDATION_ERROR), [
                'errorCode' => ErrorCodes::CATEGORY_VALIDATION_ERROR,
                'exceptionMessage' => $exception->getMessage(),
                'exceptionData' => $exception->getResponse()->getContent(),
                'requestData' => $request->all(),
            ]);

            return $this->error(
                ErrorCodes::CATEGORY_VALIDATION_ERROR,
                ErrorCodes::translate(ErrorCodes::CATEGORY_VALIDATION_ERROR),
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
     *     path="/v1/categories/{categoryId}",
     *     summary="Delete a category",
     *     description="Delete a category",
     *     @OA\Response(
     *          response="204",
     *          description="Deleted Successfully",
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Foundt",
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *     ),
     * )
     */
    public function delete(int $categoryId): JsonResponse
    {
        try {
            $category = Category::find($categoryId);

            if (!$category) {
                throw new NotFoundException();
            }

            $this->categoryRepository->delete($category);

            return $this->success(responseCode: Response::HTTP_NO_CONTENT);
        } catch (NotFoundException $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND), [
                'errorCode' => ErrorCodes::CATEGORY_NOT_FOUND,
                'exceptionMessage' => $exception->getMessage(),
                'categoryId' => $categoryId,
            ]);

            return $this->error(
                ErrorCodes::CATEGORY_NOT_FOUND,
                ErrorCodes::translate(ErrorCodes::CATEGORY_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $exception) {
            Log::error(ErrorCodes::translate(ErrorCodes::GENERAL_ERROR), [
                'errorCode' => ErrorCodes::GENERAL_ERROR,
                'exceptionMessage' => $exception->getMessage(),
                'categoryId' => $categoryId,
            ]);

            return $this->error(
                ErrorCodes::GENERAL_ERROR,
                ErrorCodes::translate(ErrorCodes::GENERAL_ERROR),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
