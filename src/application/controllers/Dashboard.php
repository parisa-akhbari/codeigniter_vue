<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

        public function __construct()
        {
                parent::__construct();

                //$this->load->library('session');
                $this->load->model('User_model');
                $this->load->model('Transaction_model');
                $this->load->model('Transaction_category_model');

                // if (!$this->session->userdata('logged_in')) {
                //         redirect('auth/signup');
                // }
        }

        private function load_view($active, $view, $data = [])
        {
                $data['active_menu'] = $active;

                // لود محتوای داخلی
                $data['content'] = $this->load->view($view, $data, true);

                // لود قالب اصلی
                $this->load->view("dashboard/layout", $data);
        }

        public function index()
        {
                $user_id = $this->session->userdata('user_id');

                $data['total_income'] = $this->Transaction_model->get_total_income($user_id);
                $data['total_expense'] = $this->Transaction_model->get_total_expense($user_id);
                $data['balance'] = $this->Transaction_model->get_balance($user_id);

                $this->load_view('home', 'dashboard/home', $data);
        }

        public function profile()
        {
                $user_id = $this->session->userdata('user_id');
                $data['user'] = $this->User_model->get_user_by_id($user_id);

                $this->load_view('profile', 'dashboard/profile', $data);
        }

        public function transactions()
        {
                $this->load->helper('jdf');
                $user_id = $this->session->userdata('user_id');

                $data['transactions'] = $this->Transaction_model->get_user_transactions($user_id);

                // لود صفحه داخل داشبورد
                $this->load_view('transactions', 'dashboard/transactions', $data);
        }

        public function categories()
        {
                $user_id = $this->session->userdata('user_id');

                $data['categories'] = $this->Transaction_category_model->get_by_user($user_id);

                // نمایش داخل داشبورد
                $this->load_view('categories', 'dashboard/categories', $data);
        }



        // public function chart_data()
        // {
        //         $this->load->helper('jdf');
        //         $user_id = $this->session->userdata("user_id");

        //         $this->load->model("Transaction_model");
        //         $rows = $this->Transaction_model->get_monthly_summary($user_id);

        //         $labels = [];
        //         $income = [];
        //         $expense = [];

        //         foreach ($rows as $row) {
        //                 $labels[] = $row->month;
        //                 $income[] = (int) $row->total_income;
        //                 $expense[] = (int) $row->total_expense;
        //         }

        //         echo json_encode([
        //                 "labels" => $labels,
        //                 "income" => $income,
        //                 "expense" => $expense
        //         ]);
        // }

        public function chart_data()
        {
                $this->load->helper('jdf');
                $user_id = $this->session->userdata('user_id');

                $monthly = $this->Transaction_model->get_monthly_summary($user_id);

                $labels = [];
                $income = [];
                $expense = [];

                foreach ($monthly as $m) {
                        // $m->month assumed format: YYYY-MM
                        list($gy, $gm, $gd) = explode('-', $m->month . '-01'); // اضافه کردن روز فرضی 01
                        list($jy, $jm, $jd) = gregorian_to_jalali($gy, $gm, $gd);

                        // label شمسی
                        $labels[] = sprintf('%04d/%02d', $jy, $jm);

                        $income[] = (float) $m->total_income;
                        $expense[] = (float) $m->total_expense;
                }

                echo json_encode([
                        'labels' => $labels,
                        'income' => $income,
                        'expense' => $expense
                ]);
        }



        public function api_summary()
        {
                $user_id = $this->session->userdata("user_id");

                $this->load->model("Transaction_model");

                $data = [
                        "total_income" => (int) $this->Transaction_model->get_total_income($user_id),
                        "total_expense" => (int) $this->Transaction_model->get_total_expense($user_id),
                        "balance" => (int) $this->Transaction_model->get_balance($user_id),
                ];

                echo json_encode($data);
        }

        

}