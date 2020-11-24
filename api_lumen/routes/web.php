<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
    });

    $router->group(['middleware' => ['token', 'auth'], 'prefix' => 'user'], function () use ($router) {
        $router->get('/profile', 'UserController@profile');
        $router->patch('/update', 'UserController@update');
        $router->delete('/delete', 'UserController@delete');
    });

    $router->group(['middleware' => ['token', 'auth'], 'prefix' => 'message'], function () use ($router) {
        $router->post('/', 'MessageController@create');
        $router->get('/{id}', 'MessageController@read');
        $router->patch('/update/{id}', 'MessageController@update');
        $router->delete('/delete/{id}', 'MessageController@delete');
    });
});
