<?php

namespace Controllers;
use Services\BaseController;
use Services\permission;
use Exception;

class userController extends BaseController {
  public $request;
  public $permission;
  function __construct($request) {
    $header = apache_request_headers();

    $this->request = $request;
    $this->permission = new permission($header);
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
      
      $jwt = $this->permission->writeToken($user, true);
      // $jwt = JWT::encode($token, $permission->lock);
      if (!$jwt['writed']) {
        throw new Exception($jwt['errorMessage']);
      }

      return $this->response([
        'token' => $jwt['token'],
        'refrash' => $jwt['refresh']
      ], 200);
    } catch (Exception $e) {
      return $this->response([
        'error' => true,
        'message' => $e->getMessage()
      ], 403);
    }
  }

  public function refreshToken () {
    try {
      $jwt = $this->permission->refreshToken();

      if (!$jwt['writed']) {
        throw new Exception($jwt['errorMessage']);
      }

      return $this->response([
        'token' => $jwt['token'],
        'refrash' => $jwt['refresh']
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