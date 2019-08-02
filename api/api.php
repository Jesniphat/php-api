<?php
/**
 * Handel header data
 */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Inclode base file
 */
include_once './vendor/autoload.php';
include_once './controllers/baseController.php';
include_once './services/routing.php';
include_once './services/permission.php';

/**
 * Handel request url
 */
$req = explode('/', $_SERVER['REQUEST_URI']);
array_shift($req);
if (substr($req[1], 0, strpos($req[1], '?')) != '') {
  $req = substr($req[1], 0, strpos($req[1], '?'));
} else {
  $req = $req[1];
}

/**
 * Check request method
 */
$METHOD = $_SERVER['REQUEST_METHOD'];

/**
 * Route to controller
 */
if ($METHOD === 'POST') {
  switch ($req) {
    case 'check' :
      router('checkController@checkTest',['admin']);
      break;
    case 'login' :
      router('userController@login');
    default :
      methodNonFound();
  }
} else if ($METHOD === 'PUT') {
  switch ($req) {
    default :
      methodNonFound();
  }
} else if ($METHOD === 'GET') {
  switch ($req) {
    case 'clearToken' : 
      router('userController@logout');
    default :
      methodNonFound();
  }
} else if ($METHOD === 'DELETE') {
  switch ($req) {
    default :
      methodNonFound();
  }
} else {
  methodNonFound();
}
