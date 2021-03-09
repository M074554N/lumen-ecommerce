<?php

use App\Models\Product;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryFactory::new()->createOne();
        ProductFactory::new()->createOne();
    }

    public function testProductIndexEndpoint()
    {
        $response = $this->get('/v1/products');

        $structure = [
            'data' => [
                'total',
                'products',
            ]
        ];

        $response->assertResponseOk();
        $response->seeHeader('content-type', 'application/json');
        $response->seeJsonStructure($structure);
        $this->assertCount(1, Product::all());
    }

    public function testProductShowEndpoint()
    {
        $response = $this->get('/v1/products/1');

        $structure = [
            'data' => [
                'productId',
                'name',
                'price',
                'categoryName',
                'createdAt',
                'updatedAt',
            ]
        ];

        $response->assertResponseOk();
        $response->seeHeader('content-type', 'application/json');
        $response->seeJsonStructure($structure);
    }



    public function testBadProductId()
    {
        $response = $this->get('/v1/products/1dsdsdsd');
        $response->assertResponseStatus(404);
    }

    public function testNotfoundProductId()
    {
        $response = $this->get('/v1/products/12312311231');
        $response->assertResponseStatus(404);
    }

    public function testStoreSuccessEndpoint()
    {
        $response = $this->post('/v1/products', [
            'name' => 'Iphone 12 Max Pro',
            'price' => 123123,
            'category_id' => 1,
        ]);

        $response->assertResponseStatus(201);
        $response->seeInDatabase('products', [
            'name' => 'Iphone 12 Max Pro'
        ]);
        $this->assertCount(2, Product::all());
    }

    public function testStoreFailedShortName()
    {
        $response = $this->post('/v1/products', [
            'name' => 'I',
            'price' => 123123,
            'category_id' => 1,
        ]);

        $responseStructure = [
            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'product_validation_error'
        ]);
    }

    public function testStoreFailedBadPrice()
    {
        $response = $this->post('/v1/products', [
            'name' => 'Iphone 12 Max',
            'price' => 'asd',
            'category_id' => 1,
        ]);

        $responseStructure = [
            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'product_validation_error'
        ]);
    }

    public function testStoreFailedBadProduct()
    {
        $response = $this->post('/v1/products', [
            'name' => 'Iphone 12 Max',
            'price' => 123213,
            'category_id' => 113123,
        ]);

        $responseStructure = [
            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'product_validation_error'
        ]);
    }

    public function testStoreFailedLongName()
    {
        $response = $this->post('/v1/products', [
            'name' => 'This is a really long name This is a really long name This is a really long name' .
                      ' This is a really long name This is a really long name This is a really long name' .
                      ' This is a really long name This is a really long name This is a really long name' .
                      ' This is a really long name This is a really long name This is a really long name' .
                      ' This is a really long name This is a really long name This is a really long name' .
                      ' This is a really long name This is a really long name This is a really long name',
            'price' => 1231232,
            'category_id' => 1,
        ]);

        $responseStructure = [
            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'product_validation_error'
        ]);
    }

    public function testUpdateSuccessEndpoint()
    {
        $response = $this->put('/v1/products/1', [
            'name' => 'Macbook Pro',
            'price' => 123442,
            'category_id' => 1,
        ]);

        $response->assertResponseStatus(202);
        $response->seeInDatabase('products', [
            'name' => 'Macbook Pro',
            'price' => 123442,
            'category_id' => 1,
        ]);
    }

    public function testUpdateFailedEndpoint()
    {
        $response = $this->put('/v1/products/1', [
            'name' => 'v',
            'price' => 12312312,
            'category_id' => 1,
        ]);

        $responseStructure = [
            'errorCode',
            'errorMessage',
            'details',
        ];

        $response->assertResponseStatus(400);
        $response->seeJsonStructure($responseStructure);
        $response->seeJsonContains([
            'errorCode' => 'product_validation_error'
        ]);
    }

    public function testDeleteSuccessEndpoint()
    {
        $response = $this->delete('/v1/products/1');

        $response->assertResponseStatus(204);
        $response->notSeeInDatabase('products', [
            'id' => 1,
        ]);
        $this->assertCount(0, Product::all());
    }
}
