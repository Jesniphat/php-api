<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './vendor/autoload.php';
include_once './controllers/baseController.php';
include_once './services/routing.php';
include_once './services/permission.php';

$req = explode('/', $_SERVER['PATH_INFO']);
// echo "req = " ; print_r($req);
array_shift($req);

$METHOD = $_SERVER['REQUEST_METHOD'];

if ($METHOD === 'POST') {
  switch ($req[0]) {
    case 'check' :
      router('checkController@checkTest',['admin']);
      break;
    case 'login' :
      router('userController@login');
    default :
      methodNonFound();
  }
} else if ($METHOD === 'PUT') {
  switch ($req[0]) {
    default :
      methodNonFound();
  }
} else if ($METHOD === 'GET') {
  switch ($req[0]) {
    case 'clearToken' : 
      router('userController@logout');
    default :
      methodNonFound();
  }
} else if ($METHOD === 'DELETE') {
  switch ($req[0]) {
    default :
      methodNonFound();
  }
} else {
  methodNonFound();
}
