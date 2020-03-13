<?php

class Api_controller extends MY_Controller {

  protected static $minPermissions;
  protected static $user_permissions = -1;
  private static $output_rendered;


  function __construct($minPermissions = USER_OFFICE) {
    parent::__construct();

    if($this->config->item('maintenance_mode') === TRUE && !defined('MIGRATION_IN_PROGRESS')){
      return $this->_outputError('Maintenance mode. Sorry for the inconvenience while we are upgrading. Try again soon', 503);
    }

    // prevent direct access to the api controllers (bypassing the routes)
    if(substr(uri_string(), 0 , 6) === 'apiv1/'){
      return $this->_output404();
    }



    // if the profiler is enabled in MY_Controller this breaks the JSON output of the API, so turn if off here
    // $this->output->enable_profiler(FALSE);
    //
    // self::$output_rendered = FALSE;
    // self::$minPermissions = $minPermissions;
    //
    // $permissions_ok = $this->_checkPermissions();
    // $this->load->helper(array('api', 'generaltools'));
    // if(!$permissions_ok){
    //   return FALSE;
    // }


  }



  protected function checkPermissions($newMinPermissions=-1){
    if($newMinPermissions !== -1){
      self::$minPermissions = $newMinPermissions;
    }
    return $this->_checkPermissions();
  }



  private function _checkPermissions(){

    $minPermissions = self::$minPermissions;

    // exit("\$minPermissions = $minPermissions");

    $login_status = $this->getLoginStatus();
    self::$user_permissions = $login_status['permissions'];

    if($login_status['logged_in']){

      if($login_status['permissions'] < $minPermissions){
        self::$user_is_authorised = FALSE;
        self::$user_permissions = 0;
        $this->_outputError("You do not have permission to access this resource", 403);
        return FALSE;

      } else {
        self::$user_is_authorised = TRUE;
        self::$user_permissions = $login_status['permissions'];
        return TRUE;
      }

    } else {

      // cli mode: grant USER_ADMIN permissions if called from command line
      if(is_cli()){

        self::$user_is_authorised = TRUE;
        self::$user_permissions = USER_ADMIN;
        return TRUE;

      } else {

        // not logged in: public users
        // *IF* constructor is called with $minPermissions set to USER_CUSTOMER
        // then we allow the API controller to run

        if($minPermissions == USER_CUSTOMER){
          self::$user_is_authorised = TRUE;
          self::$user_permissions = USER_CUSTOMER;
          return TRUE;
        } else {
          self::$user_is_authorised = FALSE;
          self::$user_permissions = 0;
          $this->_outputError("You do not have permission to access this resource", 403);
          return FALSE;
        }
      }

    }
  }


  protected function _filterProperties($object, $allowedProperties){
    $output = new stdClass();
    foreach($object as $objectKey => $objectValue){
      if(in_array($objectKey, $allowedProperties, TRUE)){
        $output->$objectKey = $objectValue;
      }
    }

    return $output;
  }



  protected function _outputSuccess($messageOrProperties=FALSE, $doNumericCheck=TRUE){

    $response = new stdClass();
    $response->success = TRUE;

    if($messageOrProperties){
      if(is_string($messageOrProperties)){
        $response->message = $messageOrProperties;
      } else {
        foreach (get_object_vars($messageOrProperties) as $key => $value) {
          $response->$key = $value;
        }
      }
    }

    if($doNumericCheck){
      return $this->_outputJSON($response, 200, JSON_NUMERIC_CHECK);
    } else {
      return $this->_outputJSON($response, 200);
    }

  }



  protected function _outputError($messageOrProperties, $code=500){
    $response = new stdClass();
    $response->success = FALSE;

    if(empty($messageOrProperties)){
      $response->message = 'An error occurred';

    } else if(is_string($messageOrProperties)){
      $response->message = $messageOrProperties;

    } else {
      foreach($messageOrProperties as $key => $value){
        $response->$key = $value;
      }
      if(isset($messageOrProperties->response_code)){
        $code = $messageOrProperties->response_code;
      }
    }

    $response->code = $code;
    return $this->_outputJSON($response, $code, JSON_NUMERIC_CHECK);
  }



  protected function _output404($message=NULL){

    if(empty($message)){
      $message = 'Resource not found';
    }

    return $this->_outputError($message, 404);
  }



  protected function _outputJSONPrecompiled($prerendered_json, $header_code=200){

    if(self::$output_rendered){
      return FALSE;
    }
    self::$output_rendered = TRUE;

    if($header_code != 200){
      $this->output->set_status_header($header_code);
    }

    $this->output->set_content_type('application/json');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
    $this->output->set_output($prerendered_json);

  }



  private function _outputJSON($response, $header_code=200, $options=NULL) {

    if(self::$output_rendered){
      return FALSE;
    }
    self::$output_rendered = TRUE;

    if(empty($options)){
      $json = json_encode($response);
    } else {
      $json = json_encode($response, $options);
    }

    $json = str_replace('##phone_encode_prefix##', '', $json);

    if($header_code != 200){
      $this->output->set_status_header($header_code);
    }

		$this->output->set_content_type('application/json');
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->load->view('json-view', array('jsonObject' => $json));
	}


}
