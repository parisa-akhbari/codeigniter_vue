<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
        $this->load->model('User_model');

        // اگر کاربر وارد نشده باشد، به صفحه ورود هدایت شود
        if (!$this->session->userdata('user_id')) {
            redirect('auth/signup');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $this->load->view('profile', $data);
    }

    public function update_name() {

        // قانون اعتبارسنجی
        $this->form_validation->set_rules('username', 'نام کاربری', 'required|alpha_numeric|is_unique[users.username]|min_length[3]', [
        'required' => 'فیلد {field} الزامی است.',
        'is_unique' => 'این {field} قبلاً ثبت شده است.',
        'alpha_numeric' => '{field} می‌تواند شامل حروف و اعداد باشد.',
        'min_length' => '{field} باید حداقل 4 کاراکتر باشد.']);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile');
        } else {
            $user_id = $this->session->userdata('user_id');
            $this->User_model->update_user($user_id, ['username' => $this->input->post('username', true)]);
            $this->session->set_flashdata('message', 'نام با موفقیت تغییر کرد.');
            redirect('profile');
        }
}

    

    public function update_password() {
        $this->form_validation->set_rules('current_password', 'پسورد قبلی', 'required|min_length[6]', [
        'required' => 'فیلد {field} الزامی است.',
        'min_length' => '{field} باید حداقل 6 کاراکتر باشد.'
    ]);
        $this->form_validation->set_rules('new_password', 'پسورد جدید', 'required|min_length[6]', [
        'required' => 'فیلد {field} الزامی است.',
        'min_length' => '{field} باید حداقل 6 کاراکتر باشد.'
    ]);
        $this->form_validation->set_rules('confirm_password', 'تایید پسورد', 'required|matches[new_password]', [
        'required' => 'فیلد {field} الزامی است.',
        'matches' => '{field} با رمز عبورجدید مطابقت ندارد.'
    ]);

        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } elseif (!password_verify($this->input->post('current_password'), $user->password)) {
            $this->session->set_flashdata('error', 'رمز فعلی اشتباه است.');
            redirect('profile');
        } else {
            $this->User_model->update_user($user_id, ['password' => password_hash($this->input->post('new_password'), PASSWORD_DEFAULT)]);
            $this->session->set_flashdata('message', 'رمز عبور با موفقیت تغییر کرد.');
            redirect('profile');
        }
    }

    public function upload_image() {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = 'profile_'.$this->session->userdata('user_id');

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('profile_image')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $file_data = $this->upload->data();
			//$image_path = 'uploads/'.$file_data['file_name'];
            $this->User_model->update_user($this->session->userdata('user_id'), ['profile_image' => 'uploads/'.$file_data['file_name']]);
			//$this->session->set_userdata('profile_image', $image_path);
            $this->session->set_flashdata('message', 'تصویر پروفایل با موفقیت آپلود شد.');
        }
        redirect('profile');
    }
}
