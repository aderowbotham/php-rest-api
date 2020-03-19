<?php
/**
 *
 * @package     REST API
 * @subpackage  API v1
 * @author      Ade Rowbotham
 *
 */

class Home extends Api_controller {

  public function __construct() {
    parent::__construct(USER_PUBLIC);
  }


  public function index_get(){
    $this->success('This is API v1.0. See documentation for usage.');
  }

}
