<?php
use \Firebase\JWT\JWT;

class permission {
  public $lock = "p@ssw0rd";
  public $requestHeader = [];
  public $jwtRequest = [];

  function __construct($requestHeader = []) {
    $this->requestHeader = $requestHeader;
  }

  public function clearToken(){
    return $this->writeToken([]);
  }

  public function writeToken(array $user) {
    try {
      $jwt = JWT::encode($user, $this->lock);
      return [
        'writed' => true,
        'token' => $jwt
      ];
    } catch (Exception $e) {
      return [
        'writed' => false,
        'errorMessage' => $e->getMessage()
      ];
    }
  }

  public function readToken($permission) {
    try {
      $token = '';

      if (!$this->requestHeader || count($this->requestHeader) == 0) {
        throw new Exception('No token.');
      }

      $token = str_replace('Bearer ', '', $this->requestHeader['Authorization']);
      $decoded = JWT::decode($token, $this->lock, array('HS256'));

      if (!$decoded) {
        throw new Exception('No token.');
      } else if (count($permission) > 0 && in_array($decoded->data->role, $permission)) {
        return [
          'access' => true,
          'user' => [
            'id' => $decoded->data->id,
            'role' => $decoded->data->role,
            'name' => $decoded->data->name,
            'email' => $decoded->data->email
          ]
        ];
      } else if (count($permission) == 0) {
        return [
          'access' => true,
          'user' => []
        ];
      } else {
        throw new Exception('Can not access.');
      }
    } catch (Exception $e) {
      return [
        'access' => false,
        'message' => $e->getMessage()
      ];
      exit;
    }
  }

}