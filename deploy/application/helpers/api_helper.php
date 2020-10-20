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


if ( ! function_exists('validateObject')){
  function validateObject($input_obj, $required_properties = [], $permitted_properties = [], $numeric_properties = []){

    if(!is_object($input_obj)){
      return ['Object expected'];
    }

    $errors = [];

    // check required properties
    foreach($required_properties as $property){
      if(!property_exists($input_obj, $property)){
        $errors[] = 'Missing property ' . $property;
      }
    }

    // check permitted properties (or skip if no array or empty array passed)
    if(!empty($permitted_properties)){
      foreach($input_obj as $key => $value){
        if(!in_array($key, $permitted_properties)){
          $errors[] = 'Unexpected property ' . $key;
        }
      }
    }

    foreach($numeric_properties as $property){
      if(property_exists($input_obj, $property)){
        if((int)$input_obj->$property === 0){
          continue;
        }

        if(!empty($input_obj->$property) && !is_numeric($input_obj->$property)){
          $errors[] = 'Property must be numeric ' . $property;
        }
      }
    }

    if(!empty($errors)){
      return $errors;
    }

    return TRUE;
  }
}
