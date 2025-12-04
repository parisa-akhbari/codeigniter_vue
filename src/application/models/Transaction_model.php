<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    protected $table = 'transactions';

    public function __construct() {
        parent::__construct();
    }

    /** دریافت همه تراکنش‌ها با دسته‌بندی */
    public function get_all() {
        return $this->db
            ->select('transactions.*, transaction_categories.title as category_title')
            ->from($this->table)
            ->join('transaction_categories', 'transactions.category_id = transaction_categories.id', 'left')->get()->result();
    }

    /** دریافت یک تراکنش با شناسه */
    public function get_by_id($id) {
        return $this->db
            ->select('transactions.*, transaction_categories.title as category_title')
            ->from($this->table)
            ->join('transaction_categories', 'transactions.category_id = transaction_categories.id', 'left')
            ->where('transactions.id', $id)->get()->row();
    }

    /** درج تراکنش جدید */
    public function insert(array $data) {
        return $this->db->insert($this->table, $data);
    }

    /** بروزرسانی تراکنش */
    public function update($id, array $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    /** حذف تراکنش */
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /** دریافت تراکنش‌ها با فیلتر و Pagination */
    public function get_filtered_paginated(array $filters = [], $limit = 10, $offset = 0) {
    $this->db
        ->select('transactions.*, transaction_categories.title as category_title')
        ->from($this->table)
        ->join('transaction_categories', 'transactions.category_id = transaction_categories.id', 'left');

    $this->apply_filters($filters);

    $this->db->limit($limit, $offset);
    return $this->db->get()->result();
}


    /** شمارش تراکنش‌ها با فیلتر */
	public function count_filtered(array $filters = []) {
		$this->db->from($this->table);
		$this->apply_filters($filters);
		return $this->db->count_all_results();
}


    /** تابع کمکی برای اعمال فیلتر */
    protected function apply_filters(array $filters) {
    if (!empty($filters['user_id'])) {
        $this->db->where('transactions.user_id', $filters['user_id']);
    }
    if (!empty($filters['title'])) {
        $this->db->like('transactions.title', $filters['title']);
    }
    if (!empty($filters['type'])) {
        $this->db->where('transactions.type', $filters['type']);
    }
    if (!empty($filters['start_date'])) {
        $this->db->where('transactions.transaction_date >=', $filters['start_date']);
    }
    if (!empty($filters['end_date'])) {
        $this->db->where('transactions.transaction_date <=', $filters['end_date']);
    }
}

	
	public function get_user_transactions($user_id)
{
		$this->db->select('transactions.*, transaction_categories.title AS category_title');
		$this->db->from('transactions');
		$this->db->join('transaction_categories', 'transaction_categories.id = transactions.category_id', 'left');
		$this->db->where('transactions.user_id', $user_id);

		return $this->db->get()->result();
}


    /** جمع کل درآمدهای یک کاربر */
    public function get_total_income($user_id)
    {
        return $this->db->select_sum('amount')->from('transactions')->where('user_id', $user_id)->where('type', 'income')->get()->row()->amount ?? 0;
    }

    /** جمع کل هزینه‌های یک کاربر */
    public function get_total_expense($user_id)
    {
        return $this->db->select_sum('amount')->from('transactions')->where('user_id', $user_id)->where('type', 'expense')->get()->row()->amount ?? 0;
    }

    /** موجودی فعلی = درآمد - هزینه */
    public function get_balance($user_id)
    {
        $income  = $this->get_total_income($user_id);
        $expense = $this->get_total_expense($user_id);
        return $income - $expense;
    }


    /** نمودار درآمد و هزینه ماهانه */
    public function get_monthly_summary($user_id)
    {
        return $this->db->select("
                DATE_FORMAT(transaction_date, '%Y-%m') AS month,
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense
            ")
            ->from("transactions")
            ->where("user_id", $user_id)
            ->group_by("DATE_FORMAT(transaction_date, '%Y-%m')")
            ->order_by("month", "ASC")
            ->get()
            ->result();
    }


}