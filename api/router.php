<?php
include_once './bootstrap/app.php';

/**
 * Route
 */
$router->post('/check/iCheck/:id/:subid', [
  'uses' => 'checkController@checkTest',
  'permission' => ['admin']
]);

$router->post('/login', [
  'uses' => 'userController@login'
]);

$router->post('/refresh-token', [
  'uses' => 'userController@refreshToken'
]);

$router->get('/clearToken', [
  'uses' => 'userController@logout'
]);

$router->get('/getCheck', [
  'uses' => 'checkController@checkMore'
]);

$router->get('/checkFileLoad', [
  'uses' => 'checkController@checkFileLoad'
]);

$router->start();

