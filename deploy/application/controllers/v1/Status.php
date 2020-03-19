<?php
/**
*
* @package     REST API
* @subpackage  API v1
* @author      Ade Rowbotham
*
*/


class Status extends Api_controller {

  function __construct()
  {
    parent::__construct();
  }

  public function index_get(){
    $output = [
      'message' => 'Status is OK',
      'version' => '1.0'
    ];
    $this->success($output);
  }

}
