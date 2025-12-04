<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	private function getUserData()
{
    return (object)[
        'id'       => $this->session->userdata('user_id'),
        'username' => $this->session->userdata('username'),
		'profile_image' => $this->session->userdata('profile_image'),
    ];
}

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
		$this->load->model('User_model');
		$this->load->model('Transaction_model');
		$this->load->model('Transaction_category_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/signup');
        }
    }

//     public function home_ajax()
// {
// 		$this->load->view("dashboard");
// }

	public function home_ajax()
{
		$user_id = $this->session->userdata('user_id');

		$data['total_income']  = $this->Transaction_model->get_total_income($user_id);
		$data['total_expense'] = $this->Transaction_model->get_total_expense($user_id);
		$data['balance']       = $this->Transaction_model->get_balance($user_id);

		$this->load->view("dashboard_home", $data);
}


	public function profile_ajax()
	{
		$user_id = $this->session->userdata('user_id');
		$data['user'] = $this->User_model->get_user_by_id($user_id);

		$this->load->view("profile", $data);
	}

	// public function index()
	// {
	// 	$data['user'] = $this->getUserData();
	// 	$this->load->view('dashboard', $data);
	// }


	public function index()
	 {
		
		$user_id = $this->session->userdata('user_id');

		$data['total_income']  = $this->Transaction_model->get_total_income($user_id);
		$data['total_expense'] = $this->Transaction_model->get_total_expense($user_id);
		$data['balance']       = $this->Transaction_model->get_balance($user_id);

		$this->load->view("dashboard", $data);
	 }

	public function chart_data()
{
		$this->load->helper('jdf');

		$user_id = $this->session->userdata('user_id');
		$monthly = $this->Transaction_model->get_monthly_summary($user_id);

		$labels = [];
		$income = [];
		$expense = [];

		foreach ($monthly as $m) {

			// فرض: مقدار month مثل "2024-01" یا "2024-1" است
			$gregorian_date = $m->month . "-01"; // تبدیل به یک تاریخ کامل

			// تبدیل به شمسی
			$jalali_label = jdate("Y/m", strtotime($gregorian_date));

			$labels[]  = $jalali_label;
			$income[]  = (int)$m->total_income;
			$expense[] = (int)$m->total_expense;
		}

		echo json_encode([
			"labels" => $labels,
			"income" => $income,
			"expense" => $expense
		]);
	}


	
}
