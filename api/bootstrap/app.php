<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: OPTIONS, GET, POST');
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, RefreshToken, X-Requested-With");
  
  /**
   * Inclode base file
   */
  include_once './vendor/autoload.php';
  include_once './config/config.php';
  include_once './database/db.php';
  include_once './services/baseController.php';
  include_once './services/routing.php';
  include_once './services/permission.php';
  
  use Services\router;
  
  /**
   * Handel request url
   */
  $req = explode('/', $_SERVER['REQUEST_URI']);
  array_shift($req);
  
  if (count($req) > 2) {
    $paramiter = [];
    foreach ($req as $key => $value) {
      if ($key > 1) {
        $paramiter[] = substr($value, 0, strpos($value, '?')) != '' ? substr($value, 0, strpos($value, '?')) : $value;
      }
    }
    router::setUrlList($paramiter);
  }
  
  if (substr($req[1], 0, strpos($req[1], '?')) != '') {
    $req = substr($req[1], 0, strpos($req[1], '?'));
  } else {
    $req = $req[1];
  }
  
  /**
   * Check request method
   */
  $METHOD = $_SERVER['REQUEST_METHOD'];
  
  