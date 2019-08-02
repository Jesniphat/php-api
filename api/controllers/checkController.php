<?php

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
}