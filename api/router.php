<?php
include_once './bootstrap/app.php';

/**
 * Route
 */
$route->post('/check/iCheck/:id/:subid', [
  'uses' => 'checkController@checkTest',
  'permission' => ['admin']
]);

$route->post('/login', [
  'uses' => 'userController@login'
]);

$route->post('/refresh-token', [
  'uses' => 'userController@refreshToken'
]);

$route->get('/clearToken', [
  'uses' => 'userController@logout'
]);

$route->get('/getCheck', [
  'uses' => 'checkController@checkMore'
]);

$route->get('/checkFileLoad', [
  'uses' => 'checkController@checkFileLoad'
]);

$route->start();

