<?php


class Users_model extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function getUserByAccessKey($access_key){
    $this->db->where('access_key', $access_key);
    $query = $this->db->get('api_users');
    if ($query->num_rows() !== 1){
      return NULL;
    }
    $result = $query->result();
    return $result[0];
  }


  public function recordFailedAuth($user_id){
    $this->db->where('id', $user_id);
    $this->db->select('failed_attempts');
    $query = $this->db->get('api_users');
    $result = $query->result();

    if(count($result) !== 1){
      return false;
    }

    $this->db->where('id', $user_id);
    $this->db->set('failed_attempts', $result[0]->failed_attempts + 1);
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update('api_users');
  }


  public function resetFailedAuth($user_id){
    $this->db->where('id', $user_id);
    $this->db->set('failed_attempts', 0);
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update('api_users');
  }


}
