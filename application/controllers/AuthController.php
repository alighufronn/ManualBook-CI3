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
            $this->session->set_userdata('user_id', $user->id); // Menyimpan ID pengguna di session
            redirect('/');
        } else {
            redirect('login');
        }
    }

    public function logout() 
    {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('user_id');
        redirect('/login');
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

    public function add()
    {
        $userModel = new UserModel();

        // Ambil input username dan password
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Validasi input username dan password
        if (empty($username) || empty($password)) {
            echo "Username dan Password tidak boleh kosong.";
            return;
        }

        // Jika input valid, lanjutkan proses
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'username' => $username,
            'password' => $hashedPassword,
        ];

        // Masukkan data ke model
        $userModel->insert($data);

        redirect('/users');
    }

    public function edit() {
        $userModel = new UserModel();
    
        $userId = $this->input->post('id');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
    
        if ($userId === null || !is_numeric($userId)) {
            $this->session->set_flashdata('error', 'ID pengguna tidak valid.');
            redirect('/users');
            return;
        }
    
        $data = [];
    
        if (!empty($username)) {
            $data['username'] = $username;
        }
    
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $data['password'] = $hashedPassword;
        }
    
        if (!empty($data)) {
            $updateStatus = $userModel->update($userId, $data);
    
            if ($updateStatus) {
                if ($userId == $this->session->userdata('user_id')) {
                    $this->session->unset_userdata('logged_in');
                    $this->session->unset_userdata('username');
                    $this->session->unset_userdata('user_id');
                    $this->session->set_flashdata('success', 'User berhasil diperbarui. Silakan login kembali.');
                    redirect('/login');
                } else {
                    $this->session->set_flashdata('success', 'User berhasil diperbarui.');
                }
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui user.');
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada data yang diperbarui.');
        }
    
        redirect('/users');
    }
    

    public function delete($id)
    {
        $currentUserId = $this->session->userdata('user_id');
        $this->UserModel->delete($id);

        if ($id == $currentUserId) {
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('username');
            $this->session->unset_userdata('user_id');
            redirect('/login');
        } else {
            redirect('/users');
        }
    }

    public function getUserData()
    {
        $userId = $this->input->get('id');
        
        log_message('debug', 'Received user ID: ' . $userId);

        if ($userId === null) {
            log_message('error', 'Invalid user ID');
            echo json_encode(['error' => 'Invalid user ID']);
            return;
        }

        $user = $this->UserModel->find($userId);

        if ($user) {
            $response = ['username' => $user->username];
            log_message('debug', 'User data: ' . print_r($response, true));
            echo json_encode($response);
        } else {
            log_message('error', 'User not found for ID: ' . $userId);
            echo json_encode(['error' => 'User not found']);
        }
    }
}
?>
