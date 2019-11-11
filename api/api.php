<?php
include_once './bootstrap/app.php';
use Services\router;

/**
 * Route to controller
 */
if ($METHOD === 'OPTIONS') {
  exit;
}
if ($METHOD === 'POST') {
  switch ($req) {
    case 'check' :
      router::request(['id']);
      router::method('checkController@checkTest', ['admin']);
      break;
    case 'login' :
      router::method('userController@login');
    case 'refresh-token' :
      router::method('userController@refreshToken');
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
      router::method('userController@logout');
    case 'getCheck' :
      router::method('checkController@checkMore');
    case 'checkFileLoad' :
      router::method('checkController@checkFileLoad');
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
