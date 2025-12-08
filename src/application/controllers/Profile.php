<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
        $this->load->model('User_model');
    }

    /** Vue صفحه اصلی */
    public function index()
    {
        $this->load->view("profile");
    }

    /** API دریافت اطلاعات کاربر */
    public function api_get()
    {
        $id = $this->session->userdata("user_id");
        $user = $this->User_model->get_user_by_id($id);
        echo json_encode($user);
    }

    /** API تغییر نام */
    public function api_update_name()
    {

        $username = $this->input->post("username");

        if (!$username) {
            echo json_encode(["status" => "error", "message" => "نام وارد نشده"]);
            return;
        }

        $id = $this->session->userdata("user_id");

        $this->User_model->update_user($id, [
            "username" => $username
        ]);

        echo json_encode(["status" => "success"]);
    }

    /** API تغییر رمز */
    public function api_update_password()
    {

        $id = $this->session->userdata("user_id");
        $user = $this->User_model->get_user_by_id($id);

        $curr = $this->input->post("current_password");
        $new = $this->input->post("new_password");
        $conf = $this->input->post("confirm_password");

        if (!$curr || !$new || !$conf) {
            echo json_encode(["status" => "error", "message" => "تمام فیلدها لازم هستند"]);
            return;
        }

        if (!password_verify($curr, $user->password)) {
            echo json_encode(["status" => "error", "message" => "رمز فعلی اشتباه است"]);
            return;
        }

        if ($new !== $conf) {
            echo json_encode(["status" => "error", "message" => "تکرار رمز اشتباه است"]);
            return;
        }

        $this->User_model->update_user($id, [
            "password" => password_hash($new, PASSWORD_DEFAULT)
        ]);

        echo json_encode(["status" => "success"]);
    }

    /** API آپلود عکس */
    public function api_upload_image()
    {

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = 'profile_' . $this->session->userdata('user_id');

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('profile_image')) {
            echo json_encode(["status" => "error", "message" => $this->upload->display_errors()]);
            return;
        }

        $file = $this->upload->data();
        //$path =  "uploads/" . $file['file_name'];
        $path = site_url("uploads/" . $file['file_name']);

        $this->User_model->update_user(
            $this->session->userdata("user_id"),
            ["profile_image" => $path]
        );

        echo json_encode(["status" => "success"]);
    }
}
