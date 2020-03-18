<?php
/**
 *
 * @package     REST API
 * @subpackage  API v1
 * @author      Ade Rowbotham
 *
 */

class Post extends Api_controller {

  public function __construct() {

    // NOTE nothing passed to __construct sets lowest level of permissions - i.e. public - for this controller
    // this can be overridden on a per-method basis by calling $this->checkPermissions(NEW_PERMISSION)
    parent::__construct(USER_ADMIN);
  }

  // route: POST:/1.0/post-test
  public function index(){

    $input = $this->getInput();
    $this->_outputSuccess((object)[
      'message' => 'This method simply echoes back the JSON payload you post to it',
      'payload' => $input
    ]);
  }

}
