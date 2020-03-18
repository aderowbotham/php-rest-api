<?php
/**
 *
 * @package     REST API
 * @subpackage  API v1
 * @author      Ade Rowbotham
 *
 */

class Status extends Api_controller {

  public function __construct() {
    parent::__construct(USER_PUBLIC);
  }


  public function index(){
    $this->_outputSuccess('Status OK');
  }

}
