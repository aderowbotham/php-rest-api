<?php
/**
 *
 * @package     REST API
 * @subpackage  API v1
 * @author      Ade Rowbotham
 * @copyright   Copyright (c) 2020, https://aderowbotham.com
 *
 */

class Home extends Api_controller {

  public function __construct() {
    parent::__construct(USER_ANON);
  }


  public function index(){
    $this->_outputSuccess('Hello world');
  }

}
