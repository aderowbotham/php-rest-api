<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api_controller extends RestController {

  protected static $user_is_authorised;
  protected static $min_permissions;
  protected static $user_permissions = -1;
  private static $input_api_key = NULL;


  function __construct($min_permissions = USER_PUBLIC) {
    parent::__construct();

    $this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
    $this->load->helper(['url','api_helper']);
    $this->cacheAdapter =($this->cache->memcached->is_supported())? $this->cache->memcached  : $this->cache->file;

    if($this->config->item('maintenance_mode') === true){
      return $this->fail('Maintenance mode. Sorry for the inconvenience while we are upgrading. Try again soon', 503);
    }

    // prevent direct access to the api controllers by their folder path (bypassing the routes)
    // this could allow people to use http methods the endpoints are not designed for
    if(substr(uri_string(), 0 , 6) === 'apiv1/'){
      return $this->fail404();
    }

    self::$min_permissions = $min_permissions;
    $permissions_ok = $this->_checkPermissions();
    if(!$permissions_ok){
      return false;
    }
  }



  protected function getInput(){
    $input = json_decode(trim($this->input->raw_input_stream));
    if($input === NULL){
      return $this->fail('Missing or invalid JSON payload', 400);
    }
    return $input;
  }


  protected function checkPermissions($newMinPermissions=-1){
    if($newMinPermissions !== -1){
      self::$min_permissions = $newMinPermissions;
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


  protected function redirect(string $new_location, int $method = NULL)
  {
    $method = empty($method) ? 301 : $method;
    return redirect($new_location, $method);
  }


  private function _checkPermissions(){
    self::$user_is_authorised = false;
    $failed_attempts = 0;

    // assumed trust of CLI users
    if(is_cli() && $this->config->item('trust_cli_users')){
      self::$user_is_authorised = true;
      return true;
    }

    $access_key = trim($this->input->server('HTTP_ACCESS_KEY'));
    $secret_key = trim($this->input->server('HTTP_SECRET_KEY'));

    if(empty($access_key)){
      self::$user_permissions = USER_PUBLIC;
    } else {

      $this->load->model('users_model');
      $user = $this->users_model->getUserByAccessKey($access_key);

      // user not found
      if(empty($user)){
        authSleep();
        self::$user_permissions = USER_PUBLIC;
        $this->fail('Authorization rejected - bad credentials', 401);
        return false;
      }

      if($user->failed_attempts >= $this->config->item('max_failed_auth_attempts')){
        authSleep();
        self::$user_permissions = USER_PUBLIC;
        $this->fail('Authorization rejected - too many attempts', 401);
        return false;
      }

      // missing key
      if(empty($secret_key)){
        $this->users_model->recordFailedAuth($user->id);
        authSleep();
        self::$user_permissions = USER_PUBLIC;
        $this->fail('Authorization rejected - bad credentials', 401);
        return false;
      }

      // bad password
      if(!password_verify($secret_key, $user->secret_key_hash)){
        $this->users_model->recordFailedAuth($user->id);
        authSleep();
        self::$user_permissions = USER_PUBLIC;
        $this->fail('Authorization rejected - bad credentials', 401);
        return false;
      }

      // NOTE if here the then the provided secret key was correct
      // set the permission from the user in the database
      self::$user_permissions = $user->permissions;
      if((int)$user->failed_attempts !== 0){
        $this->users_model->resetFailedAuth($user->id);
      }
    }

    if(self::$user_permissions < self::$min_permissions){
      $this->fail('You do not have permission to access this resource', 403);
      return false;
    } else {
      self::$user_is_authorised = true;
      return true;
    }
  }

}
