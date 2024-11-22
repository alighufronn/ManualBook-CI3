<?php

class KategoriMBModel extends CI_Model
{
    protected $table = 'kategori_mb';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_allowed_fields()
    {
        return ['nama'];
    }

    public function get_kategoris() 
    { 
        $query = $this->db->get($this->table); 
        return $query->result_array();
    }

    public function delete_kategori($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }
    
}