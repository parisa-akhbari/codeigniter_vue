<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->database();
        $this->load->helper(['url', 'security']);
    }

public function signup() {
    $this->form_validation->set_rules('username', 'نام کاربری', 'required|is_unique[users.username]|min_length[4]', [
        'required' => 'فیلد {field} الزامی است.',
        'is_unique' => 'این {field} قبلاً ثبت شده است.',
        'min_length' => '{field} باید حداقل 4 کاراکتر باشد.'
    ]);

    $this->form_validation->set_rules('password', 'رمز عبور', 'required|min_length[6]', [
        'required' => 'فیلد {field} الزامی است.',
        'min_length' => '{field} باید حداقل 6 کاراکتر باشد.'
    ]);

    $this->form_validation->set_rules('confirmpassword', 'تأیید رمز عبور', 'required|matches[password]', [
        'required' => 'فیلد {field} الزامی است.',
        'matches' => '{field} با رمز عبور مطابقت ندارد.'
    ]);

    if ($this->form_validation->run() === FALSE) {
        $data['active_form'] = 'signup'; // فرم ثبت‌نام فعال بماند
        $this->load->view('auth/signup', $data);
    } else {
        $data = [
            'username' => $this->input->post('username', true),
            'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
        ];

        $this->db->insert('users', $data);
        $this->session->set_flashdata('signup_success', 'ثبت نام با موفقیت انجام شد. لطفا وارد شوید.');
        redirect('auth/signup');
    }
}

public function login() {
    if ($this->session->userdata('logged_in')) {
        redirect('dashboard');
    }

    $this->form_validation->set_rules('username', 'نام کاربری', 'required', [
        'required' => 'فیلد {field} الزامی است.'
    ]);
    $this->form_validation->set_rules('password', 'رمز عبور', 'required', [
        'required' => 'فیلد {field} الزامی است.'
    ]);

    if ($this->form_validation->run() === FALSE) {
        $data['active_form'] = 'login'; // فرم ورود فعال بماند
        $this->load->view('auth/signup', $data);
    } else {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->User_model->get_user($username);

        if ($user && password_verify($password, $user->password)) {
            $userdata = [
                'user_id' => $user->id,
                'username' => $user->username,
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($userdata);
            redirect('Dashboard');
        } else {
            $data['login_error'] = "نام کاربری یا رمز عبور اشتباه است.";
            $data['active_form'] = 'login'; // فرم ورود فعال بماند
            $this->load->view('auth/signup', $data);
        }
    }
}


    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/signup');
    }
}

