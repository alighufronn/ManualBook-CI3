<?php

class ManualBookModel extends CI_Model
{
    protected $table = 'manualbooks';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_allowed_fields()
    {
        return ['nama_halaman', 'id_kategori', 'nama_kategori'];
    }

    public function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert('manualbooks', $data);
    }

    public function get_manualbooks()
    {
        // Mengurutkan berdasarkan 'nama_halaman'
        $this->db->order_by('nama_halaman', 'ASC');
        $query = $this->db->get($this->table);
        $result = $query->result_array();

        // Memproses 'nama_kategori' menjadi array 'kategori_list'
        foreach ($result as &$row) {
            $row['kategori_list'] = explode(',', $row['nama_kategori']);
        }

        return $result;
    }

    public function delete_manual_book($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
}
