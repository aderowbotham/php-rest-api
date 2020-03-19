<?php

use chriskacerguis\RestServer\RestController;

class Api_controller extends RestController {

  protected static $user_is_authorised;
  protected static $minPermissions;
  protected static $user_permissions = -1;
  private static $input_api_key = NULL;



  function __construct($minPermissions = USER_PUBLIC) {
    parent::__construct();

    $this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
    $this->cacheAdapter =($this->cache->memcached->is_supported())? $this->cache->memcached  : $this->cache->file;
    $this->load->helper('url');

    $this->load->helper('api_helper');

    if($this->config->item('maintenance_mode') === true){
      return $this->fail('Maintenance mode. Sorry for the inconvenience while we are upgrading. Try again soon', 503);
    }

    // prevent direct access to the api controllers by their folder path (bypassing the routes)
    // this could allow people to use http methods the endpoints are not designed for
    if(substr(uri_string(), 0 , 6) === 'apiv1/'){
      return $this->fail404();
    }

    self::$minPermissions = $minPermissions;

    $this->_processHeaders();

    // $permissions_ok = $this->_checkPermissions();
    // $this->load->helper(array('api', 'generaltools'));
    // if(!$permissions_ok){
    //   return false;
    // }


  }



  protected function getInput(){
    if(!$input = json_decode(file_get_contents('php://input'))){
      return $this->fail('Missing or invalid JSON payload', 400);
    }
    return $input;
  }


  protected function checkPermissions($newMinPermissions=-1){
    if($newMinPermissions !== -1){
      self::$minPermissions = $newMinPermissions;
    }
    return $this->_checkPermissions();
  }


  protected function success($messageOrProperties=NULL){
    $response = ['status' => true];
    if(!empty($messageOrProperties)){
      if(is_string($messageOrProperties)){
        $response['message'] = $messageOrProperties;
      } else {
        foreach($messageOrProperties as $key => $value) {
          $response[$key] = $value;
        }
      }
    }
    $this->response($response, 200);
  }


  protected function fail($messageOrProperties, $code=500){
    $response = ['status' => false];
    if(empty($messageOrProperties)){
      $response['message'] = 'An error occurred';
    } else if(is_string($messageOrProperties)){
      $response['message'] = $messageOrProperties;
    } else {
      foreach($messageOrProperties as $key => $value){
        $response[$key] = $value;
      }
    }

    $response['code'] = $code;
    return $this->response($response, $code );
  }


  protected function fail404($message=NULL){
    if(empty($message)){
      $message = 'Resource not found';
    }
    return $this->fail($message, 404);
  }



  // - - - - private internal methods - - - -

  private function _checkPermissions(){

    $minPermissions = self::$minPermissions;

    // exit("\$minPermissions = $minPermissions");

    $login_status = $this->getLoginStatus();
    self::$user_permissions = $login_status['permissions'];

    if($login_status['logged_in']){

      if($login_status['permissions'] < $minPermissions){
        self::$user_is_authorised = false;
        self::$user_permissions = 0;
        $this->fail("You do not have permission to access this resource", 403);
        return false;

      } else {
        self::$user_is_authorised = true;
        self::$user_permissions = $login_status['permissions'];
        return true;
      }

    } else {

      // cli mode: grant USER_ADMIN permissions if called from command line
      if(is_cli()){

        self::$user_is_authorised = true;
        self::$user_permissions = USER_ADMIN;
        return true;

      } else {

        // not logged in: public users
        // *IF* constructor is called with $minPermissions set to USER_CUSTOMER
        // then we allow the API controller to run

        if($minPermissions == USER_CUSTOMER){
          self::$user_is_authorised = true;
          self::$user_permissions = USER_CUSTOMER;
          return true;
        } else {
          self::$user_is_authorised = false;
          self::$user_permissions = 0;
          $this->fail("You do not have permission to access this resource", 403);
          return false;
        }
      }

    }
  }



  private function _processHeaders(){

    // $authh = $this->input->get_request_header('Authorization');
    // exit("\$authh = ".$authh);

    $username = $this->input->server('PHP_AUTH_USER');
    $http_auth = $this->input->server('HTTP_AUTHENTICATION') ?: $this->input->server('HTTP_AUTHORIZATION');

    // exit("\$http_auth = $http_auth");

    // $headers = getallheaders();
    //$headers = apache_request_headers();
    //exit(print_r($headers, true));

    //$session_key = $this->input->get_request_header('Authorization', true);
    // $clientVersion = $this->input->get_request_header('X-requested-with', true);

    // exit("\$session_key = $session_key");

  }


}
