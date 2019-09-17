<?php

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

$router->get('/', function () {
    return response()->json(["success" => "This Is Home Page"], 200);
});

$router->get('/books', 'BookController@index');
$router->post('/books', 'BookController@store');
$router->get('/books/{bookId}', 'BookController@show');
$router->put('/books/{bookId}', 'BookController@update');
$router->patch('/books/{bookId}', 'BookController@update');
$router->delete('/books/{bookId}', 'BookController@destory');
