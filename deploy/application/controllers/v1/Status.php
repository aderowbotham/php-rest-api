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

    $output = (object)[
      'status' => 'ok'
    ];
    $this->response($output, 200 );
  }



}
