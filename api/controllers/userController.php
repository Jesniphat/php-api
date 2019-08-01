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

      $issuer_claim = 'JESSE_API'; // this can be the servername
      $audience_claim = 'THE_AUDIENCE';
      $issuedat_claim = time(); // issued at
      $notbefore_claim = $issuedat_claim + 10; //not before in seconds
      $expire_claim = $issuedat_claim + 600000; // expire time in seconds
      $token = [
        'iss' => $issuer_claim,
        'aud' => $audience_claim,
        'iat' => $issuedat_claim,
        'nbf' => $notbefore_claim,
        'exp' => $expire_claim,
        'data' => $user
      ];
      
      $jwt = $this->permission->writeToken($token);
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