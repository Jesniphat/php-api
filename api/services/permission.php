<?php
use \Firebase\JWT\JWT;

class permission {
  public $lock = "p@ssw0rd";
  public $requestHeader = [];
  public $jwtRequest = [];

  function __construct($requestHeader = []) {
    $this->requestHeader = $requestHeader;
  }

  /**
   * Clear token and return to frontend
   * 
   * @access public
   * @return array token key
   */
  public function clearToken(){
    return $this->writeToken([]);
  }


  /**
   * Write token
   * 
   * @param array $user
   * @access public
   * @return array token
   */
  public function writeToken(array $user) {
    try {
      $token = $this->initData($user);
      $jwt = JWT::encode($token, $this->lock);
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


  /**
   * Init data for gen token
   * 
   * @param array $user
   * @return array $token
   */
  private function initData(array $user) {
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

    return $token;
  }


  /**
   * Read token key
   * 
   * @param array $permission
   * @access public
   * @return array can access and user data ['access', 'user]
   */
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
      } else if ($decoded && is_array($decoded->data) && count($decoded->data) == 0) {
        throw new Exception('No user login');
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