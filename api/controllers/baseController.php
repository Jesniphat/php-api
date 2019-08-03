<?php

class BaseController {
  public $requests = [];

  function __construct() {
    $this->getParam();
  }


  /**
   * Get body data and query string
   * it will set all data to property $requests
   * 
   * @access private
   * @return json error or void
   */
  private function getParam() {
    $input = file_get_contents('php://input');

    try {
      if ($input) {
        $this->requests['body'] = json_decode($input, true);
      }

      if (count($_REQUEST) > 0) {
        $this->requests['query'] = $_REQUEST;
      }
    } catch (Exception $e) {
      $error = $this->response([
        'error' => $e->getMessage(),
        'more' => 'can not map body or query string'
      ], 400);
      return responseJson($error);
    }
  }


  /**
   * Handel response code
   * 
   * @param array $data
   * @param int $code
   * @access public
   * @return array $data
   */
  public function response($data, $code = 200) {
    http_response_code($code);
    return $data;
  }
}
