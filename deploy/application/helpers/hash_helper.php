<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('makePWHash')){
  function makePWHash($username,$password,$salt)
  {
    return hash("sha256",$salt.hash("sha256",$username.$salt.$password));
  }
}



if ( ! function_exists('makeVerifyKey')){
  function makeVerifyKey($username,$salt)
  {
    return hash("sha1",$salt.hash("sha1",$username.'verify'.$salt));
  }
}



if ( ! function_exists('make_salt')){
  function make_salt() {
    mt_srand(microtime(true) * 100000 + memory_get_usage(true));
    return md5(uniqid(mt_rand(), true));
  }
}



if ( ! function_exists('makeResetKey')){
  function makeResetKey($username)
  {
    $randomSalt = make_salt();
    return substr(hash('sha1' ,$randomSalt),0,19).'-'.hash('sha1', $randomSalt . hash('sha1', $username . 'reset' . $randomSalt));
  }
}
