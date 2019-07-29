<?php

class BaseController {
  public $requests = [];

  function __construct() {
    $input = file_get_contents('php://input');
    $this->requests = array();
    try {
      if ($input) {
        $this->requests['body'] = json_decode($input, true);
      }
    } catch (Exception $e) {
      print_r($e);
    }
  }

  public function response($data, $code = 200) {
    http_response_code($code);
    return $data;
  }
}
