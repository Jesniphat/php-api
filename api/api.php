<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, GET, POST');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, RefreshToken, X-Requested-With");

/**
 * Inclode base file
 */
include_once './vendor/autoload.php';
include_once './services/baseController.php';
include_once './services/routing.php';
include_once './services/permission.php';

use Services\router;

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
if ($METHOD === 'OPTIONS') {
  exit;
}
if ($METHOD === 'POST') {
  switch ($req) {
    case 'check' :
      router::_('checkController@checkTest',['admin']);
      break;
    case 'login' :
      router::_('userController@login');
    case 'refresh-token' :
      router::_('userController@refreshToken');
    default :
    router::methodNonFound();
  }
} else if ($METHOD === 'PUT') {
  switch ($req) {
    default :
    router::methodNonFound();
  }
} else if ($METHOD === 'GET') {
  switch ($req) {
    case 'clearToken' : 
      router::_('userController@logout');
    case 'getCheck' :
      router::_('checkController@checkMore');
    case 'checkFileLoad' :
      router::_('checkController@checkFileLoad');
    default :
      router::methodNonFound();
  }
} else if ($METHOD === 'DELETE') {
  switch ($req) {
    default :
      router::methodNonFound();
  }
} else {
  router::methodNonFound();
}
