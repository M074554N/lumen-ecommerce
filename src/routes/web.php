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
        $router->get('/{productId:[0-9]+}', 'ProductController@show');
        $router->put('/{productId:[0-9]+}', 'ProductController@update');
        $router->delete('/{productId:[0-9]+}', 'ProductController@delete');
    });

    $router->group(['prefix' => 'categories'], function () use ($router) {
        $router->get('/', 'CategoryController@index');
        $router->get('/{categoryId:[0-9]+}', 'CategoryController@show');
        $router->get('/{categoryId:[0-9]+}/products', 'CategoryController@showProducts');
        $router->post('/', 'CategoryController@store');
        $router->put('/{categoryId:[0-9]+}', 'CategoryController@update');
        $router->delete('/{categoryId:[0-9]+}', 'CategoryController@delete');
    });
});
