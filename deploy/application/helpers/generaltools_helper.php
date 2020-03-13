<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Code Igniter
*
*
* @package		CodeIgniter / General Processing tools
* @author		Ade Rowbotham
* @copyright	Copyright (c) Ade Rowbotham
* @link		http://ade.rowbotham.co.uk
*/


if ( ! function_exists('toBoolean')){
  function toBoolean($var) {
    return($var === TRUE || $var === 1 || $var ==='1' || strtolower($var) === 'true');
  }
}




if ( ! function_exists('removeSpaces')){
  function removeSpaces($inputString)
  {
    return str_replace(" ","_",$inputString);
  }
}


if(!function_exists('precisionCeil')){
  function precisionCeil($value, $precision = 0) {
    return ceil($value * pow(10, $precision)) / pow(10, $precision);
  }
}




// clone array - required to clone an array of objects
// otherwise the object pointers remain intact as references
// via https://stackoverflow.com/a/17729234/539883
if(!function_exists('array_clone')){
  function array_clone($array)
  {
    return array_map(function($element) {
      return ((is_array($element))
      ? call_user_func(__FUNCTION__, $element)
      : ((is_object($element))
      ? clone $element
      : $element
    ));
  }, $array);
}
}





if(!function_exists('url_get_contents')){
  function url_get_contents ($Url) {
    if (!function_exists('curl_init')){
      die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }
}
