<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Transaction_model', 'transactions_model');
        $this->load->model('Transaction_category_model', 'categories_model');
        $this->load->helper('url');
        $this->load->library(['form_validation', 'pagination']);
    }

     private function persian_to_gregorian($persianDate) {
        // تبدیل اعداد فارسی به انگلیسی
        $persianDate = strtr($persianDate, [
            '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9'
        ]);

        // جایگزینی / با -
        $persianDate = str_replace('/', '-', $persianDate);

        // گرفتن سال، ماه، روز
        list($jy, $jm, $jd) = explode('-', $persianDate);

        // تبدیل شمسی به میلادی
        list($gy, $gm, $gd) = jalali_to_gregorian($jy, $jm, $jd);

        return sprintf("%04d-%02d-%02d", $gy, $gm, $gd);
    }

     private function prepare_transaction_data($is_create = true) {
        $this->load->helper("jdf");

        $gregorianDate = $this->persian_to_gregorian($this->input->post('transaction_date'));

        $data = [
            'title' => $this->input->post('title'),
            'amount' => $this->input->post('amount'),
            'type' => $this->input->post('type'),
            'category_id' => $this->input->post('category_id'),
            'transaction_date' => $gregorianDate
        ];

        if ($is_create) {
            // فقط در حالت ایجاد، user_id اضافه می‌شود
            $data['user_id'] = $this->session->userdata('user_id');
        }

        return $data;
    }

    private function set_transaction_validation_rules() {
        $rules = [
            ['field' => 'title', 'label' => 'عنوان', 'rules' => 'required'],
            ['field' => 'amount', 'label' => 'مبلغ', 'rules' => 'required|numeric'],
            ['field' => 'type', 'label' => 'نوع', 'rules' => 'required'],
            ['field' => 'category_id', 'label' => 'دسته بندی', 'rules' => 'required'],
            ['field' => 'transaction_date', 'label' => 'تاریخ', 'rules' => 'required']
        ];

        $this->form_validation->set_rules($rules);
}


    /** صفحه لیست تراکنش‌ها */
    public function index() {
		$user_id = $this->session->userdata('user_id');
		$this->load->helper("jdf");

		// نگهداری مقدار شمسی برای فرم
		$shamsi_start = $this->input->get('start_date');
		$shamsi_end   = $this->input->get('end_date');

		$filters = [
			'title' => $this->input->get('title'),
			'type' => $this->input->get('type'),

			// این دو مقدار شمسی هستند و فقط برای فرم استفاده می‌شوند
			'start_date_shamsi' => $shamsi_start,
			'end_date_shamsi' => $shamsi_end,

			'user_id' => $user_id
		];

		// --- تبدیل تاریخ شمسی به میلادی برای جستجو ---
		if (!empty($shamsi_start)) {
			$clean = strtr($shamsi_start, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']);
			list($jy, $jm, $jd) = explode('/', $clean);
			list($gy, $gm, $gd) = jalali_to_gregorian($jy, $jm, $jd);
			$filters['start_date'] = "$gy-$gm-$gd";
		}

		if (!empty($shamsi_end)) {
			$clean = strtr($shamsi_end, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']);
			list($jy, $jm, $jd) = explode('/', $clean);
			list($gy, $gm, $gd) = jalali_to_gregorian($jy, $jm, $jd);
			$filters['end_date'] = "$gy-$gm-$gd";
		}

		// Pagination Config 
		$config['base_url'] = site_url('transactions/index');
		$config['total_rows'] = $this->transactions_model->count_filtered($filters);
		$config['per_page'] = 10;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		$config['full_tag_open']  = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open']   = '<li class="page-item">';
		$config['num_tag_close']  = '</li>';
		$config['cur_tag_open']   = '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close']  = '</a></li>';
		$config['attributes']     = ['class' => 'page-link'];

		$this->pagination->initialize($config);

		$page = $this->input->get('page') ?: 1;
		$offset = ($page - 1) * $config['per_page'];

		$data['transactions'] = $this->transactions_model->get_filtered_paginated(
			$filters,
			$config['per_page'],
			$offset
		);

		// ارسال مقدارهای شمسی برای ویو
		$data['filters'] = $filters;

		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('transactions/index', $data);
	}

    /** صفحه ایجاد تراکنش جدید */
    public function create() {
        $this->load->helper("jdf");
        $user_id = $this->session->userdata('user_id');
        $data['categories'] = $this->categories_model->get_by_user($user_id);

        //$data['categories'] = $this->categories_model->get_all();

         $this->set_transaction_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('transactions/create', $data);
        }

        $insert_data = $this->prepare_transaction_data(true);

        //log_message("debug", "Insert data: " . print_r($insert_data, true));

        $this->transactions_model->insert($insert_data);
        redirect('transactions');
}

    /** صفحه ویرایش تراکنش */
    public function edit($id) {
        $this->load->helper("jdf");

        $data['transaction'] = $this->transactions_model->get_by_id($id);
        if (!$data['transaction']) show_404();

        $data['categories'] = $this->categories_model->get_all();

        $this->set_transaction_validation_rules();


        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('transactions/edit', $data);
        }

        $update_data = $this->prepare_transaction_data(false);

        // log_message("debug", "Update data: " . print_r($update_data, true));

        $this->transactions_model->update($id, $update_data);
        redirect('transactions');
}


    /** حذف تراکنش */
    public function delete($id) {
        $this->transactions_model->delete($id);
        redirect('transactions');
    }
}