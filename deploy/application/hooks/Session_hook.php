<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function close_ci_sessions()
{
  session_write_close();
  $CI =& get_instance();
  if(isset($CI->db)){
    $CI->db->close();
  }

}
