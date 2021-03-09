<?php

use App\Models\Category;
use Database\Factories\CategoryFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryFactory::new()->createOne();
    }

    public function testCategoryIndexEndpoint()
    {
        $response = $this->get('/v1/categories');

        $structure = [
            'data' => [
                'total',
                'categories',
            ]
        ];

        $response->assertResponseOk();
        $response->seeHeader('content-type', 'application/json');
        $response->seeJsonStructure($structure);
    }

    public function testCategoryShowEndpoint()
    {
        $response = $this->get('/v1/categories/1');

        $structure = [
            'data' => [
                'categoryId',
                'categoryName',
                'ProductsCount',
                'createdAt',
                'updatedAt',
            ]
        ];

        $response->assertResponseOk();
        $response->seeHeader('content-type', 'application/json');
        $response->seeJsonStructure($structure);
    }

    public function testCategoryShowWithProductsEndpoint()
    {
        $response = $this->get('/v1/categories/1/products');

        $structure = [
            'data' => [
                'categoryId',
                'categoryName',
                'ProductsCount',
                'createdAt',
                'updatedAt',
                'products',
            ]
        ];

        $response->assertResponseOk();
        $response->seeHeader('content-type', 'application/json');
        $response->seeJsonStructure($structure);
    }

    public function testBadCategoryId()
    {
        $response = $this->get('/v1/categories/1dsdsdsd');
        $response->assertResponseStatus(404);
    }

    public function testNotfoundCategoryId()
    {
        $response = $this->get('/v1/categories/12312311231');
        $response->assertResponseStatus(404);
    }

    public function testStoreSuccessEndpoint()
    {
        $response = $this->post('/v1/categories', [
            'name' => 'Good Books',
        ]);

        $response->assertResponseStatus(201);
        $response->seeInDatabase('categories', [
            'name' => 'Good Books'
        ]);
        $this->assertCount(2, Category::all());
    }

    public function testStoreFailedShortNameEndpoint()
    {
        $response = $this->post('/v1/categories', [
            'name' => 'v',
        ]);

        $responseStructure = [

            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'category_validation_error'
        ]);
    }

    public function testStoreFailedLongNameEndpoint()
    {
        $response = $this->post('/v1/categories', [
            'name' => 'This is a really long name This is a really long name This is a really long name' .
                      ' This is a really long name This is a really long name This is a really long name' .
                      ' This is a really long name This is a really long name This is a really long name',
        ]);

        $responseStructure = [
            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'category_validation_error'
        ]);
    }

    public function testUpdateSuccessEndpoint()
    {
        $response = $this->put('/v1/categories/1', [
            'name' => 'Fiction Books',
        ]);

        $response->assertResponseStatus(202);
        $response->seeInDatabase('categories', [
            'name' => 'Fiction Books'
        ]);
    }

    public function testUpdateFailedEndpoint()
    {
        $response = $this->put('/v1/categories/1', [
            'name' => 'v',
        ]);

        $responseStructure = [
            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'category_validation_error'
        ]);
    }

    public function testDeleteSuccessEndpoint()
    {
        $response = $this->delete('/v1/categories/1');

        $response->assertResponseStatus(204);
        $response->notSeeInDatabase('categories', [
            'id' => 1,
        ]);
        $this->assertCount(0, Category::all());
    }
}
