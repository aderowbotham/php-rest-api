<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// debugging utility
if ( ! function_exists('prx')){
  function prx($object, $doExit=true){
    if($doExit){
      exit('<pre>' . print_r($object, true));
    } else {
      echo('<pre>' . print_r($object, true));
    }

  }
}


if ( ! function_exists('authSleep')){
  function authSleep() {
    usleep(1000000 + (int)(mt_rand(0,1500000)));
  }
}


if ( ! function_exists('make_access_key')){
  function make_access_key($username, $salt){
    return hash('sha256', $username . $salt);
  }
}


if ( ! function_exists('make_secret_key_hash')){
  function make_secret_key_hash($secret_key){
    return password_hash($secret_key, PASSWORD_BCRYPT);
  }
}
