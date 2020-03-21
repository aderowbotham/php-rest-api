<?php defined("BASEPATH") or exit("No direct script access allowed");

class Mkadminuser extends CI_Controller {

  public function __construct() {

    parent::__construct();

    // @NOTE critical we don't let this run outside of development environment except via the CLI
    if(!is_cli() && ENVIRONMENT !== ENV_DEVELOPMENT){
      show_404();
    }
  }



  public function index() {

    if(!is_cli()){
      return FALSE;
    }
    echo "\n- - - - - - - - - - - - - - - - - - - - - - -\n";
    if(empty(NEW_USERNAME) || empty(NEW_PASSWORD)){
      exit("pass username and password as arguments 3 and 4");
    }

    $this->load->helper('api_helper');

    // insert demo user
    $demo_user = [
      'username' => NEW_USERNAME,
      'access_key' => make_access_key(NEW_USERNAME, $this->config->item('public_key_salt')),
      'secret_key_hash' => make_secret_key_hash(NEW_PASSWORD),
      'permissions' => USER_ADMIN,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];

    $this->db->insert('api_users', $demo_user);
    echo "User " . NEW_USERNAME . " created\n";

  }

  function _remap($method){
    if (method_exists($this, $method)){
      $this->$method();
    } else {
      $this->index($method);
    }
  }
}
