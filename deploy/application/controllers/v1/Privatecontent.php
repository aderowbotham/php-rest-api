<?php
/**
 *
 * @package     REST API
 * @subpackage  API v1
 * @author      Ade Rowbotham
 *
 */

class Privatecontent extends Api_controller {

  public function __construct() {

    // NOTE nothing passed to __construct sets lowest level of permissions - i.e. public - for this controller
    // this can be overridden on a per-method basis by calling $this->checkPermissions(NEW_PERMISSION)
    parent::__construct(USER_ADMIN);
  }

  // route: GET:/1.0/private-content (auth required)
  public function index_get(){

    if(!$this->checkPermissions()){
      return false;
    }

    $this->success([
      'message' => 'Access to private content granted'
    ]);
  }

}
