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

$router->group(['prefix' => 'api/'], function() use($router){

$router->post('/register','UserController@register');
$router->post('/login','UserController@login');


$router->post('/product','ProductController@create');
$router->get('/product','ProductController@index');
$router->get('/product/{id}','ProductController@show');
$router->put('/product/{id}','ProductController@update');
$router->delete('/product/{id}','ProductController@destroy');

$router->post('/cart/add','CartController@add');
$router->post('/cart/remove','CartController@remove');
$router->get('/cart','CartController@index');

});