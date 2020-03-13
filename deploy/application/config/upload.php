<?php

//image uploads
$config['image_library'] = 'gd2';
$config['upload_path'] = '../uploads';
$config['allowed_types'] = 'jpg|JPG|png|PNG|jpeg|JPEG';
$config['max_size']	= '10000';
$config['max_width']  = '9000';
$config['max_height']  = '9000';
//other settings
//$config['remove_spaces']  = TRUE;
$config['encrypt_name']  = TRUE;

$config['timber_image_sizes'] = array(
  'thumb_width' => 448,
  'thumb_height' => 224,
  'large_width' => 800,
  'large_height' => 600
);
