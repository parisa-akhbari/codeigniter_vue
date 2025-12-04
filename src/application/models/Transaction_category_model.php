<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_category_model extends CI_Model {

    protected $table = 'transaction_categories';

    public function __construct() {
        parent::__construct();
    }
	
	public function get_all() {
        return $this->db->select('transaction_categories.*')->from($this->table)->get()->result();
    }

    public function get_by_id($id) {
        return $this->db->select('transaction_categories.*')->from($this->table)->where('transaction_categories.id', $id)->get()->row();
    }

    
    public function get_by_user($user_id) {
        return $this->db->select('*')->from($this->table)->where('user_id', $user_id)->get()->result();
    }


    public function insert(array $data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, array $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    // public function get_filtered_paginated($filters, $limit, $offset)
    // {
    //     if (!empty($filters['title'])) {
    //         $this->db->like('title', $filters['title']);
    //     }

    //     $this->db->where('user_id', $filters['user_id']);

    //     return $this->db->limit($limit, $offset)->get('transaction_categories')->result();
    // }


    public function get_filtered_paginated($filters, $limit, $offset)
    {
        if (!empty($filters['title'])) {
            $this->db->like('title', $filters['title']);
        }

        $this->db->where('user_id', $filters['user_id']);
        $this->db->order_by('id', 'DESC');

        return $this->db->get('transaction_categories', $limit, $offset)->result();
    }

    public function count_filtered($filters)
    {
        if (!empty($filters['title'])) {
            $this->db->like('title', $filters['title']);
        }

        $this->db->where('user_id', $filters['user_id']);

        return $this->db->count_all_results('transaction_categories');
    }
}
