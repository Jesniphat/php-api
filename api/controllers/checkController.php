<?php

namespace Controllers;
use Services\BaseController;

class checkController extends BaseController {
  public $request;

  function __construct($request) {
    $this->request = $request;
  }

  public function checkTest() {
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
}