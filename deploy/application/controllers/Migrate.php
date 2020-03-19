<?php defined("BASEPATH") or exit("No direct script access allowed");

class Migrate extends CI_Controller {

  public function __construct() {

    parent::__construct();

    // @NOTE super critical we don't let this run outside of dev except via the CLI
    if(!is_cli() && ENVIRONMENT !== ENV_DEVELOPMENT){
      show_404();
    }
    $this->load->library('migration');
  }



  public function index() {

    if(!is_cli() && ENVIRONMENT !== ENV_DEVELOPMENT){
      return FALSE;
    }

    if(!is_cli()){
      echo '<pre>';
    }

    echo "\n- - - - - - - - - - - - - - - - - - - - - - -\n";
    echo "Checking whether a migration is required...\n";


    if ( ! $this->migration->current()) {
      echo "Migration is current. Config version is " . $this->config->item('migration_version') . " \n";
      show_error($this->migration->error_string());
    } else {

      echo "Migration successful, or already up to date \n";
      echo "Attempting to clear cache... \n";

      $this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
      $this->cacheAdapter =($this->cache->memcached->is_supported())? $this->cache->memcached  : $this->cache->file;

      if ($this->cache->memcached->is_supported()){
        $this->cache->memcached->clean();
      } else {
        $this->cache->file->clean();
      }

    }

    echo "- - - - - - - - - - - - - - - - - - - - - - -\n\n";
  }

  function _remap($method){
    if (method_exists($this, $method)){
      $this->$method();
    } else {
      $this->index($method);
    }
  }
}
