<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactionscategories extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Transaction_category_model', 'transaction_category_model');
        $this->load->helper('url');
        $this->load->library(['form_validation', 'pagination', 'session']);
    }

    /**
     * create view 
     * @return void
     */
    public function index()
    {
        $this->load->view('categories/index');
    }

    /**
     * return data in json format
     * @return void
     */
    public function search()
    {
        $user_id = $this->session->userdata('user_id');

        $filters = [
            'title' => $this->input->get('title'),
            'user_id' => $user_id
        ];

        $page_size = 10;
        $page = $this->input->get('page') ?? 1;
        $offset = ($page - 1) * $page_size;

        $categories = $this->transaction_category_model->get_filtered_paginated(
            $filters,
            $page_size,
            $offset
        );

        log_message("debug", "Categories: " . print_r($categories, true));

        echo json_encode([
            'cats' => $categories
        ]);
    }

    

    /** صفحه ایجاد */
    public function create() {

        $this->form_validation->set_rules('title', 'عنوان', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('categories/create');
        }

        $insert_data = [
            'title'   => $this->input->post('title'),
            'user_id' => $this->session->userdata('user_id')
        ];

        $this->transaction_category_model->insert($insert_data);

        redirect('transactionscategories');
    }

    /** صفحه ویرایش */
    public function edit($id) {

        $data['category'] = $this->transaction_category_model->get_by_id($id);

        if (!$data['category']) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'عنوان', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('categories/edit', $data);
        }

        $update_data = [
            'title' => $this->input->post('title'),
        ];

        $this->transaction_category_model->update($id, $update_data);

        redirect('transactionscategories');
    }

    /** حذف */
    public function delete($id) {
        $this->transaction_category_model->delete($id);
        redirect('transactionscategories');
    }
}

