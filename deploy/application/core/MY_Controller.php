<?php

class MY_Controller extends CI_Controller {

  private static $thisGlobals;
  protected static $user_is_authorised;
  protected static $user_permissions;
  protected static $data;



  function __construct($forceAllow = FALSE) {
    parent::__construct();

    $this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
    $this->cacheAdapter =($this->cache->memcached->is_supported())? $this->cache->memcached  : $this->cache->file;
    $this->load->helper('url');
  }


  protected function _deletecache(){
    if ($this->cache->memcached->is_supported()){
      $this->cache->memcached->clean();
    } else {
      $this->cache->file->clean();
    }
  }


}
