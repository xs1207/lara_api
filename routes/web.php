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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


//登录
$router->get('/user/login','Test\IndexController@login');
//用户中心
$router->get('/user/center','Test\IndexController@uCenter');

//防刷测试
$router->get('/test/order','Test\IndexController@order');


$router->get('/test/encryption','Test\IndexController@encryption');

//api  接口登录测试
$router->post('/user/login','Test\IndexController@lgn');

