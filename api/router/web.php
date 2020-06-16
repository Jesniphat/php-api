<?php
include_once './bootstrap/app.php';

/**
 * Route
 * Permission array is list of user type who can access that controller
 * Permission [] is user have to login to access that controller
 * Not set permission is every one can access that controller
 */



/**
 * POST enpoint
 */
$route->post('/check/iCheck/:id/:subid', [
  'uses' => 'CheckController@checkTest',
  'permission' => ['admin']
]);

$route->post('/myapiInsert', [
  'uses' => 'CheckController@myapiInsert',
  'permission' => ['admin']
]);

$route->post('/login', [
  'uses' => 'UserController@login'
]);

$route->post('/refresh-token', [
  'uses' => 'UserController@refreshToken'
]);



/**
 * PUT enpoint
 */



/**
 * GET enpoint
 */
$route->get('/clearToken', [
  'uses' => 'UserController@logout'
]);

$route->get('/getCheck', [
  'uses' => 'CheckController@checkMore'
]);

$route->get('/checkFileLoad', [
  'uses' => 'CheckController@checkFileLoad'
]);



/**
 * DELETE enpoint
 */



$route->start();
