<?php
/**
*
* @package     REST API
* @subpackage  API v1
* @author      Ade Rowbotham
*
*/


class Users extends Api_controller {

  function __construct()
  {
    parent::__construct();
  }



  public function index_get(){

    $users = [
      ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
      ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
    ];

    $id = $this->get('id');

    if($id === null){
      $this->response($users, 200 );
    } else {
      if ( array_key_exists( $id, $users )){
        $this->response( $users[$id], 200 );
      } else {
        // $this->response([
        //   'status' => false,
        //   'message' => 'User not found'
        // ], 404 );
        $this->_outputError([
          'message' => 'User not found'
        ]);
      }
    }

    $output = (object)[
      'status' => 'ok'
    ];
    $this->response($output, 200 );
  }


  public function index_post(){
    $input = $this->getInput();
    $this->_outputSuccess((object)[
      'message' => 'You added a user:',
      'user' => $input
    ]);
  }



}
