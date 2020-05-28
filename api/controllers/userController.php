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
      if (empty($this->request['body'])) {
        throw new Exception('No body, username and password');
      }

      if ($this->request['body']['username'] !== 'Jesse' || $this->request['body']['password'] !== 'password') {
        throw new Exception('Username Or Password Wrong');
      }
      // Select User from db befor here may have to crater model for query db.
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