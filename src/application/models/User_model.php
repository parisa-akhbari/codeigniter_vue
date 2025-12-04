<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {


    public function __construct() {
        parent::__construct();
        $this->load->database(); 
    }


    public function get_user($username) {
        return $this->db->get_where('users', ['username' => $username])->row();
    }

    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function update_user($user_id, $data) {
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
}

    public function update_image($id, $path)
    {
        return $this->db->where('id', $id)->update('users', ['profile_image' => $path]);
    }

}
