<?php

use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */
$router->get('/', 'IndexController@index');
$router->get('/docs-ui', 'IndexController@docsUi');

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/docs', 'IndexController@docs');

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/', 'ProductController@index');
        $router->post('/', 'ProductController@store');
        $router->get('/{productId}', 'ProductController@show');
        $router->put('/{productId}', 'ProductController@update');
        $router->delete('/{productId}', 'ProductController@delete');
    });

    $router->group(['prefix' => 'categories'], function () use ($router) {
        $router->get('/', 'CategoryController@index');
        $router->get('/{categoryId}', 'CategoryController@show');
        $router->get('/{categoryId}/products', 'CategoryController@showProducts');
        $router->post('/', 'CategoryController@store');
        $router->put('/{categoryId}', 'CategoryController@update');
        $router->delete('/{categoryId}', 'CategoryController@delete');
    });
});
