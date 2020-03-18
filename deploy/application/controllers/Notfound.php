<?php
/**
 *
 * @package     REST API
 * @subpackage  API v1
 * @author      Ade Rowbotham
 *
 */

class Notfound extends Api_controller {

  public function __construct() {
    parent::__construct();
  }


  public function index(){
    $this->_output404();
  }

}
