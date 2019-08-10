<?php

namespace Services;
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\SignatureInvalidException;
use \Exception\Exception;

class permission {
  public $lock = "p@ssw0rd";
  public $requestHeader = [];
  public $jwtRequest = [];

  function __construct($requestHeader = []) {
    $this->requestHeader = $requestHeader;
  }


  /**
   * Init data for gen token
   * 
   * @param array $user
   * @param int $timeOut
   * @return array $token
   */
  private function initData(array $user, int $timeOut) {
    $issuer_claim = 'JESSE_API'; // this can be the servername
    $audience_claim = 'THE_AUDIENCE';
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim + 10; //not before in seconds
    $expire_claim = $issuedat_claim + $timeOut; // expire time in seconds
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
  public function writeToken(array $user, bool $refresh = false) {
    try {
      $timeOut = 70;
      $token = $this->initData($user, $timeOut);
      $jwt = JWT::encode($token, $this->lock);

      $refreshJwt = '';
      if ($refresh) {
        $refreshTimeOut = 6000000;
        $refreshToken = $this->initData($user, $refreshTimeOut);
        $refreshJwt = JWT::encode($refreshToken, $this->lock);

        return [
          'writed' => true,
          'token' => $jwt,
          'refresh' => $refreshJwt
        ];
      }

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
   * Read token key
   * 
   * @param array $permission
   * @access private
   * @return array can access and user data ['access', 'user]
   */
  private function readToken($permission) {
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
    } catch (ExpiredException $e) {
      return [
        'access' => false,
        'message' => $e->getMessage()
      ];
    } catch (SignatureInvalidException $e) {
      return [
        'access' => false,
        'message' => $e->getMessage()
      ];
    }
  }


  /**
   * refresh token
   * 
   * @access private
   * @return array token
   */
  private function refreshToken($permission) {
    try {
      $token = '';
      if (!$this->requestHeader || count($this->requestHeader) == 0) {
        throw new Exception('No token.');
      }

      $token = str_replace('Bearer ', '', $this->requestHeader['RefreshToken']);
      $decoded = JWT::decode($token, $this->lock, array('HS256'));

      if (!$decoded) {
        throw new Exception('No token.');
      } else if (count($permission) > 0 && in_array($decoded->data->role, $permission)) {
        $user = [
          'id' => $decoded->data->id,
          'role' => $decoded->data->role,
          'name' => $decoded->data->name,
          'email' => $decoded->data->email
        ];

        return [
          'refresh' => true,
          'user' => $user,
          'newToken' => $this->writeToken($user, true)
        ];
      } else if ($decoded && is_array($decoded->data) && count($decoded->data) == 0) {
        throw new Exception('No user login');
      } else {
        throw new Exception('Can not access.');
      }
    } catch (Exception $e) {
      return [
        'refresh' => false,
        'message' => $e->getMessage()
      ];
    } catch (ExpiredException $e) {
      return [
        'refresh' => false,
        'message' => $e->getMessage()
      ];
    } catch (SignatureInvalidException $e) {
      return [
        'refresh' => false,
        'message' => $e->getMessage()
      ];
    }
  }


  /**
   * Get new token when refresh token
   * 
   * @param array new token from gen it
   * @access private
   * @return array token
   */
  private function getTokenByRefresh(array $newToken) {
    try {
      if ($newToken['refresh'] && $newToken['newToken']['writed']) {
        return [
          'access' => $newToken['refresh'],
          'user' => $newToken['user'],
          'token' => [
            'token' => $newToken['newToken']['token'],
            'refrashToken' => $newToken['newToken']['refresh']
          ]
        ];
      } else if ($newToken['refresh'] && !$newToken['newToken']['writed']) {
        throw new Exception($newToken['newToken']['errorMessage']);
      } else {
        throw new Exception($newToken['message']);
      }
    } catch (Exception $e) {
      return [
        'access' => false,
        'message' => $e->getMessage()
      ];
    }
  }


  /**
   * Get token
   * 
   * @param array $permission
   * @access public
   * @return array $token
   */
  public function getToken(array $permission) {
    try {
      if (count($permission) == 0) {
        return [
          'access' => true,
          'user' => []
        ];
      }

      $token = $this->readToken($permission);

      if ($token['access']) {
        return $token;
      }

      if (!$token['access'] && $token['message'] == 'Expired token' && $this->requestHeader['RefreshToken']) {
        $newToken = $this->refreshToken($permission);
        return $this->getTokenByRefresh($newToken);
      } else {
        throw new Exception($token['message']);
      }
    } catch (Exception $e) {
      return [
        'access' => false,
        'message' => $e->getMessage()
      ];
    }
  }

}