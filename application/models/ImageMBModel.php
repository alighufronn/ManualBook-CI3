<?php

class ImageMBModel extends CI_Model
{
    protected $table = 'image_mb';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_allowed_fields()
    {
        return ['id_kategori', 'id_halaman', 'gambar'];
    }

    // Menemukan berdasarkan id
    public function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    // Mendapatkan berdasarkan id halaman
    public function get_images_by_halaman($id_halaman)
    {
        $this->db->where('id_halaman', $id_halaman);
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    // Hapus berdasarkan id halaman
    public function delete_images_by_halaman($id_halaman)
    {
        return $this->db->delete($this->table, array('id_halaman' => $id_halaman));
    }

    // Menginput gambar
    public function insert($data)
    {
        return $this->db->insert('image_mb', $data);
    }

    // Ambil berdasarkan id
    public function get_image($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row_array();
    }

    // Hapus berdasarkan id
    public function delete_image($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    // Update gambar
    public function updateImage($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }


}