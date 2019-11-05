<?php
namespace Database;

use config;
use PDO;
use Exception;

class DB {
  public static $pdo;
  /**
   * @access public
   * @return mixed $conn
   */
  public static function connect() {
    $opt = [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false
    ];

    try {
      $config = new config();
      self::$pdo = new PDO("mysql:host=$config->HOST;dbname=$config->DB;charset=utf8",$config->USERNAME,$config->PASSWORD,$opt);
    } catch (Exception $e) {
      http_response_code(500);
      $error = [
        'message' => 'Can not connect to db ' . $e->getMessage()
      ];

      echo json_encode($error, JSON_UNESCAPED_UNICODE);
      exit;
    }
  }

  public static function disconnect() {
    self::$pdo = null;
  }
}