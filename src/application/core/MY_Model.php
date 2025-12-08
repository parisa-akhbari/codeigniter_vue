<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
        protected $table;
        protected $primaryKey = 'id';

        public function __construct()
        {
                parent::__construct();
        }

        /** دریافت همه رکوردها */
        public function get_all()
        {
                return $this->db->get($this->table)->result();
        }

        /** دریافت رکورد با شناسه */
        public function get_by_id($id)
        {
                return $this->db->where($this->primaryKey, $id)
                        ->get($this->table)
                        ->row();
        }

        /** درج رکورد جدید */
        public function insert(array $data)
        {
                return $this->db->insert($this->table, $data);
        }

        /** بروزرسانی رکورد */
        public function update($id, array $data)
        {
                return $this->db->update($this->table, $data, [$this->primaryKey => $id]);
        }

        /** حذف رکورد */
        public function delete($id)
        {
                return $this->db->delete($this->table, [$this->primaryKey => $id]);
        }

        /** دریافت رکوردها همراه فیلتر و Pagination */
        public function get_filtered_paginated(array $filters = [], $limit = 10, $offset = 0)
        {
                $this->db->from($this->table);
                $this->apply_filters($filters);

                $this->db->limit($limit, $offset);
                return $this->db->get()->result();
        }

        /** شمارش رکوردها با فیلتر */
        public function count_filtered(array $filters = [])
        {
                $this->db->from($this->table);
                $this->apply_filters($filters);
                return $this->db->count_all_results();
        }

        /** تابع عمومی اعمال فیلترها (قابل override) */
        protected function apply_filters(array $filters)
        {
                foreach ($filters as $field => $value) {
                        if ($value === '' || $value === null)
                                continue;

                        if (is_string($value))
                                $this->db->like($field, $value);
                        else
                                $this->db->where($field, $value);
                }
        }

}
