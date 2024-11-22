<?php

class UserModel extends CI_Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_allowed_fields()
    {
        return ['username', 'password'];
    }

    public function get_user($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));

        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getUser()
    {
        $query = $this->db->get($this->table); 
        return $query->result_array();
    }
}