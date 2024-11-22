<?php

ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function login()
    {
        $this->load->view('login');
    }
    
    public function login_process() 
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->UserModel->get_user($username, $password);
        if ($user) {
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('username', $user->username);
            redirect('/');
        } else {
            redirect('login');
        }
    }

    public function logout() 
    {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('username');
        redirect('/');
    }



    public function user()
    {
        $data['users'] = $this->UserModel->getUser();

        $data['title'] = 'User Control';
        $data['pageTitle'] = 'User Control';
        $data['breadcrumb'] = 'User Control';
        $data['logged_in'] = $this->session->userdata('logged_in');
        $data['username'] = $this->session->userdata('username');

        $data['content'] = $this->load->view('user', $data, true);

        $this->load->view('layout/page_layout', $data);
    }
}
?>
