<?php

class userController extends BaseController {
  public $request;
  public $permission;
  function __construct($request) {
    $this->request = $request;
    $this->permission = new permission();
  }

  public function login() {
    try {
      // $permission = new permission();
      $user = [
        'id' => 1,
        'name' => 'Jesse',
        'last_name' => 'Checker',
        'email' => 'jesniphat@hotmail.com',
        'role' => 'admin'
      ];
      
      $jwt = $this->permission->writeToken($user);
      // $jwt = JWT::encode($token, $permission->lock);
      if (!$jwt['writed']) {
        throw new Exception($jwt['errorMessage']);
      }

      return $this->response([
        'token' => $jwt['token']
      ], 200);
    } catch (Exception $e) {
      return $this->response([
        'error' => true,
        'message' => $e->getMessage()
      ], 403);
    }
  }

  public function logout() {
    try {
      $jwt = $this->permission->clearToken();
      // $jwt = JWT::encode($token, $permission->lock);
      if (!$jwt['writed']) {
        throw new Exception($jwt['errorMessage']);
      }

      return $this->response([
        'token' => $jwt['token']
      ], 200);
    } catch (Exception $e) {
      return $this->response([
        'error' => true,
        'message' => $e->getMessage()
      ], 403);
    }
  }
}