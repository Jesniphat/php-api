<?php
  namespace Framework\Main;

  use Framework\Services\Router;

  include_once './framework/services/baseController.php';
  include_once './framework/services/routing.php';
  include_once './framework/services/permission.php';
  
  class MainFramework {
    private $req;
    private $request_uri = [];
    private $uri_list = [];
    private $path = [];
    private $request = [];

    private $method_lists = [];

    function __construct() {
      $this->req = explode('/', $_SERVER['REQUEST_URI']);
      array_shift($this->req);

      $this->findUri();
    }

    /**
     * Get string for request url to array and set it to array
     * @access private
     * @return void
     */
    private function findUri(): void {
      foreach ($this->req as $key => $value) {
        if ($key === 0) { continue; }
    
        if (substr($value, 0, strpos($value, '?')) != '') {
          $this->request_uri[] = substr($value, 0, strpos($value, '?'));
        } else {
          $this->request_uri[] = $value;
        }
      }
    }


    /**
     * Check that url request from frontend macth with list of url list in system have or not
     * @param string $uri
     * @param string $method
     * @access private
     * @return bool
     */
    private function checkUriMacth(string $uri, string $method): bool {
      $this->uri_list = explode('/', $uri);
      array_shift($this->uri_list);

      $this->path = [];
      $this->request = [];

      foreach ($this->uri_list as $val) {
        if ($val[0] === ':') {
          $new_key = substr($val, 1);
          $this->request[] = $new_key;
        } else {
          $this->path[] = $val;
        }
      }

      if ($_SERVER['REQUEST_METHOD'] !== $method) {
        return false;
      }

      $isMacth = false;
      foreach ($this->path as $key => $val) {
        if (in_array($val, $this->request_uri)) {
          $isMacth = true;
        } else {
          $isMacth = false;
          break;
        }
      }

      if (!$isMacth) {
        return false;
      }

      if (count($this->request) > 0) {
        Router::request($this->request);
      }

      $request_list_data = [];
      foreach ($this->request_uri as $val) {
        if (!in_array($val, $this->path)) {
          $request_list_data[] = $val;
        }
      }

      if (count($request_list_data) > 0) {
        Router::setUrlList($request_list_data);
      }

      return true;
    }


    /**
     * Call get controller method when url macth
     * @param string $uri
     * @param string $options
     * @access private
     * @return bool
     */
    private function call_get(string $uri, array $options): bool {
      if(!$this->checkUriMacth($uri, 'GET')) {
        return false;
      }

      if (!isset($options['uses'])) {
        Router::usesNotFound();
      } else if (isset($options['permission'])) {
        Router::method($options['uses'], $options['permission']);
      } else {
        Router::method($options['uses']);
      }

      return true;
    }


    /**
     * Call post controller method when url macth 
     * @param string $uri
     * @param string $options
     * @access private
     * @return bool
     */
    private function call_post(string $uri, array $options): bool {
      if (!$this->checkUriMacth($uri, 'POST')){
        return false;
      }

      if (!isset($options['uses'])) {
        Router::usesNotFound();
      } else if (isset($options['permission'])) {
        Router::method($options['uses'], $options['permission']);
      } else {
        Router::method($options['uses']);
      }

      return true;
    }


    /**
     * Call put controller method when url macth 
     * @param string $uri
     * @param string $options
     * @access private
     * @return bool
     */
    private function call_put(string $uri, array $options): bool {
      if (!$this->checkUriMacth($uri, 'PUT')) {
        return false;
      }

      if (!isset($options['uses'])) {
        Router::usesNotFound();
      } else if (isset($options['permission'])) {
        Router::method($options['uses'], $options['permission']);
      } else {
        Router::method($options['uses']);
      }

      return true;
    }


    /**
     * Call delete controller method when url macth 
     * @param string $uri
     * @param string $options
     * @access private
     * @return bool
     */
    private function call_delete(string $uri, array $options):bool {
      if (!$this->checkUriMacth($uri, 'DELETE')) {
        return false;
      }

      if (!isset($options['uses'])) {
        Router::usesNotFound();
      } else if (isset($options['permission'])) {
        Router::method($options['uses'], $options['permission']);
      } else {
        Router::method($options['uses']);
      }

      return true;
    }


    /**
     * Set post method to method list
     * @param string $uri
     * @param string $options
     * @access public
     * @return void
     */
    public function post(string $uri, array $opitions): void {
      $this->method_lists[] = [
        'method' => 'call_post',
        'uri' => $uri,
        'options' => $opitions
      ];
    }


    /**
     * Set put method to method list
     * @param string $uri
     * @param string $options
     * @access public
     * @return void
     */
    public function put(string $uri, array $opitions) {
      $this->method_lists[] = [
        'method' => 'call_put',
        'uri' => $uri,
        'options' => $opitions
      ];
    }


    /**
     * Set get method to method list
     * @param string $uri
     * @param string $options
     * @access public
     * @return void
     */
    public function get(string $uri, array $opitions) {
      $this->method_lists[] = [
        'method' => 'call_get',
        'uri' => $uri,
        'options' => $opitions
      ];
    }


    /**
     * Set delete method to method list
     * @param string $uri
     * @param string $options
     * @access public
     * @return void
     */
    public function delete(string $uri, array $opitions) {
      $this->method_lists[] = [
        'method' => 'call_delete',
        'uri' => $uri,
        'options' => $opitions
      ];
    }


    public function listMethod() {
      var_dump($this->method_lists); exit;
    }


    /**
     * Loop method from list then check is it match with request url from frontend or not.
     * Then call controller method if it match but if not macth all call methodNonFound() and exit.
     * 
     * @access public
     * @return void
     */
    public function start(): void {
      $is_success = false;
      foreach ($this->method_lists as $key => $value) {
        $method = $value['method'];
        $success = $this->$method($value['uri'], $value['options']);
        if ($success) {
          $is_success = true;
          break;
        }
      }

      if (!$is_success) {
        Router::methodNonFound();
      }
    }

  }