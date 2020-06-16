<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: OPTIONS, GET, POST');
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, RefreshToken, X-Requested-With");
  
  /**
   * Inclode libs from vendor forder
   */
  include_once './vendor/autoload.php';

  /**
   * Inclode base file
   */
  include_once './config/config.php';
  include_once './database/db.php';
  include_once './framework/main/main.php';

  Use Framework\Main\main;
  
  class apps extends main {
    function __construct() {
      parent::__construct();
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
  }

  $route = new apps();