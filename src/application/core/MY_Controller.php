<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

        public function __construct()
        {
                parent::__construct();

                // بارگذاری کتابخانه‌ها و مدل‌ها
                $this->load->library('session');
                // $this->load->model('User_model');
                // $this->load->model('Transaction_model');
                // $this->load->model('Transaction_category_model');

                // بررسی ورود کاربر
                if (!$this->session->userdata('logged_in')) {
                        redirect('auth/signup');
                }


                // if (!$this->session->userdata('logged_in')) {

                //         if ($this->input->is_ajax_request()) {
                //                 //show_404();
                //                 echo json_encode(['error' => 'Unauthorized']);
                //                 exit;
                //         } else {

                //                 redirect('auth/signup');
                //         }
                // }
        }
}
?>