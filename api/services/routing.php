<?php

function router($controller, $permission = []) {
  $base = new BaseController();

  $controller = explode('@', $controller);

  include_once './controllers/' . $controller[0] . '.php';
  $continue = ['access' => true];

  if (count($permission) > 0) {
    $continue = checkPermission($permission);
  }

  if (!$continue['access']) {
    $responseError = $base->response([
      'message' => $continue['message']
    ], 403);

    responseJson($responseError);
    return;
  }

  
  $class = $controller[0];
  $medthod = $controller[1];
  
  $request = [
    'auth' => $continue['user']
  ];
  $request = array_merge($request, $base->requests);
  $controller = new $class($request);
  
  $res = $controller->$medthod();
  if ($res) {
    responseJson($res);
  }
}

function checkPermission($param) {
  $header = apache_request_headers();
  
  $permission = new permission($header);
  return $permission->readToken($param);
}

function responseJson($data) {
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit;
}

function methodNonFound() {
  http_response_code(400);
  $error = [
    'message' => 'METHOD NOT FOUND'
  ];
  echo json_encode($error, JSON_UNESCAPED_UNICODE);
  exit;
}
