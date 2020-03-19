<?php
/**
*
* @package     REST API
* @subpackage  API v1
* @author      Ade Rowbotham
*
*/


class Products extends Api_controller {

  function __construct()
  {
    parent::__construct();
  }


  public function index_get(){

    $products = [
      ['id' => 0, 'title' => 'Bucket', 'price' => '1.99'],
      ['id' => 1, 'tite' => 'Spade', 'price' => '0.99'],
    ];

    $id = $this->get('id');

    if($id === null){
      $this->success($products);
    } else {
      if ( array_key_exists( $id, $products )){
        $this->success($products[$id]);
      } else {
        $this->fail([
          'message' => 'User not found'
        ]);
      }
    }

    $output = (object)[
      'status' => 'ok'
    ];
    $this->success($output);
  }


  public function index_post(){
    $input = $this->getInput();
    $this->success([
      'message' => 'You added a product:',
      'product' => $input
    ]);
  }



}
