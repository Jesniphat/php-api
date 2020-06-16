<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: OPTIONS, GET, POST');
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, RefreshToken, X-Requested-With");
  
  Use Framework\Main\MainFramework;
  /**
   * Inclode libs from vendor forder
   */
  include_once './vendor/autoload.php';

  /**
   * Inclode base file
   */
  include_once './config/config.php';
  include_once './database/db.php';
  include_once './framework/main/mainFramework.php';
  
  class Apps extends MainFramework {
    function __construct() {
      parent::__construct();
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
  }

  $route = new Apps();