<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use function OpenApi\scan;

/**
 * @OA\Info(
 *     title="Lumen Sample E-commerce API",
 *     version="1.0.0",
 *     @OA\Contact(
 *          email="hassan.mohamed.sf@gmail.com",
 *     )
 * )
 */
class IndexController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/",
     *     summary="Main API endpoint",
     *     description="Main API endpoint for testing, returns just a welcome message",
     *     @OA\Response(
     *          response="200",
     *          description="Successful Response",
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return $this->success([
            'message' => 'Welcome to Lumen',
        ]);
    }

    public function docs()
    {
        $openapi = scan(__DIR__);
        return $openapi->toJson();
    }

    public function docsUi()
    {
        return view('swagger');
    }
}
