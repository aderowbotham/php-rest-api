<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// debugging utility
if ( ! function_exists('prx')){
  function prx($object, $doExit=TRUE){
    if($doExit){
      exit('<pre>' . print_r($object, true));
    } else {
      echo('<pre>' . print_r($object, true));
    }

  }
}
