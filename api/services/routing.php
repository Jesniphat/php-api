<?php

namespace Services;
use \Controllers;

class router {
  /**
   * Route to controller and return data to frondend
   * @param string $controller
   * @param array $permission
   * @return json
   */
  public static function _($controller, $permission = []) {
    $base = new BaseController();

    $controller = explode('@', $controller);

    include_once './controllers/' . $controller[0] . '.php';
    $continue = ['access' => true];

    if (count($permission) > 0) {
      $continue = self::checkPermission($permission);
    }

    if (!$continue['access']) {
      $responseError = $base->response([
        'message' => $continue['message']
      ], 403);

      self::responseJson($responseError);
      return;
    }

    
    $class = '\Controllers\\' . $controller[0];
    $medthod = $controller[1];
    
    $request = [
      'auth' => $continue['user']
    ];
    $request = array_merge($request, $base->requests);
    $controller = new $class($request);
    
    $res = $controller->$medthod();
    if ($continue['token']) {
      $res['newToken'] = $continue['token'];
    }

    if ($res && $res['isFile']) {
      self::responseDownload($res['data']['path']);
    }

    if ($res) {
      self::responseJson($res);
    }
  }

  /**
   * Check permission if seted it
   * @param array $permissionList
   * @return jwt $token
   */
  private function checkPermission($permissionList) {
    $header = apache_request_headers();
    $permission = new permission($header);

    // $checked = $permission->getToken($permissionList);
    do {
      $checked = $permission->getToken($permissionList);
    } while (!$checked['access'] && stripos($checked['message'], 'not handle token prior to'));
    // return $permission->getToken($permissionList);
    return $checked;
  }

  /**
   * Response json to frontend
   * 
   * @param array $data
   * @return json
   */
  public static function responseJson($data) {
    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
  }

  /**
   * Response download file
   * 
   * @param string $path
   * @return file 
   */
  public static function responseDownload(string $path) {
    $file = $path;

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
      $responseError = $base->response([
        'message' => 'Not file exits'
      ], 400);

      self::responseJson($responseError);
    }
  }

  /**
   * Response error method not found
   * 
   * @return json
   */
  public static function methodNonFound() {
    http_response_code(400);
    $error = [
      'message' => 'METHOD NOT FOUND'
    ];
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
    exit;
  }
}
