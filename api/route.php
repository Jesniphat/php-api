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
  'uses' => 'checkController@checkTest',
  'permission' => ['admin']
]);

$route->post('/login', [
  'uses' => 'userController@login'
]);

$route->post('/refresh-token', [
  'uses' => 'userController@refreshToken'
]);



/**
 * PUT enpoint
 */



/**
 * GET enpoint
 */
$route->get('/clearToken', [
  'uses' => 'userController@logout'
]);

$route->get('/getCheck', [
  'uses' => 'checkController@checkMore'
]);

$route->get('/checkFileLoad', [
  'uses' => 'checkController@checkFileLoad'
]);



/**
 * DELETE enpoint
 */



$route->start();
