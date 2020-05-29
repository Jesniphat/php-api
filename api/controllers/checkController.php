<?php

namespace Controllers;
use Services\BaseController;
use Database\DB;

class checkController extends BaseController {
  public $request;

  function __construct($request) {
    $this->request = $request;
  }

  public function checkTest() {
    // $pdo = DB::connect();
    return $this->response(['check' => 'ok', 'param' => $this->request], 200);
  }

  public function checkMore() {
    return $this->response(['sss' => 5555]);
  }

  public function checkFileLoad() {
    $file = './files/F-1563006945.png';

    return $this->download([
      'path' => $file,
    ], 200);
  }

  public function myapiInsert() {
    DB::connect();
    DB::$pdo->beginTransaction();
    try {
      DB::$pdo->exec('insert into users (id, name) values (\'10\', \'Jesse\')');
      // commit the transaction
      // print_r(DB::$pdo);
      DB::$pdo->commit();
      DB::disconnect();
      return $this->response([
        'ok' => true
      ], 200);
    } catch (Exception $err) {
      // roll back the transaction if something failed
      DB::$pdo->rollback();
      DB::disconnect();
      return $this->response([
        'error' => true,
        'message' => $e->getMessage()
      ], 400);
    }
  }
}