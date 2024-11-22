<?php

class ManualBookController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ManualBookModel');
        $this->load->model('ImageMBModel');
        $this->load->model('KategoriMBModel');
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library('upload', 'session');

    }

    public function index()
    {
        $data['manualbooks'] = $this->ManualBookModel->get_manualbooks();
        $data['kategoris'] = $this->KategoriMBModel->get_kategoris();

        $data['title'] = 'PMC Manual Book';
        $data['pageTitle'] = 'Manual Book';
        $data['logged_in'] = $this->session->userdata('logged_in');
        $data['username'] = $this->session->userdata('username');

        $data['content'] = $this->load->view('ManualBook/PageBook', $data, true);

        $this->load->view('layout/page_layout', $data);
    }

    public function detail($id)
    {
        $this->load->helper('text');
        $this->load->helper('url');

        $data['mb'] = $this->ManualBookModel->find($id);
        if (empty($data['mb'])) {
            show_404('Halaman dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data['gambar'] = $this->ImageMBModel->get_images_by_halaman($id);
        $data['kategoris'] = $this->KategoriMBModel->get_kategoris();
        $data['id_halaman'] = $id;

        $data['title'] = "Detail Manual Book";
        $data['pageTitle'] = "Detail Manual Book";
        $data['breadcrumb'] = "Detail Manual Book";
        $data['logged_in'] = $this->session->userdata('logged_in');
        $data['username'] = $this->session->userdata('username');
        $data['breadcrumb'] = true;
        $data['content'] = true;
        
        $data['content'] = $this->load->view('ManualBook/ImageBook', $data, true);

        $this->load->view('layout/page_layout', $data);
    }

    public function save_kategorimb()
    {
        $katModel = new KategoriMBModel();

        $data = [
            'nama' => $this->input->post('nama'),
        ];

        $katModel->insert($data);

        redirect('/');
    }

    public function save_mb()
    {
        $manualBookModel = new ManualBookModel();
        $imageModel = new ImageMBModel();

        $dataMB = array(
            'nama_halaman' => $this->input->post('nama_halaman'),
            'id_kategori' => $this->input->post('id_kategori'),
            'nama_kategori' => $this->input->post('nama_kategori'),
        );

        $this->db->trans_start();

        $manualBookModel->insert($dataMB);
        $id_halaman = $this->db->insert_id();

        // Gambar
        if (!empty($_FILES['gambar']['name'][0])) {
            $files = $_FILES;
            $count = count($_FILES['gambar']['name']);
            for ($i = 0; $i < $count; $i++) {
                $_FILES['gambar']['name'] = $files['gambar']['name'][$i];
                $_FILES['gambar']['type'] = $files['gambar']['type'][$i];
                $_FILES['gambar']['tmp_name'] = $files['gambar']['tmp_name'][$i];
                $_FILES['gambar']['error'] = $files['gambar']['error'][$i];
                $_FILES['gambar']['size'] = $files['gambar']['size'][$i];

                $config['upload_path'] = './assets/uploads/img/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = time() . '_' . $_FILES['gambar']['name'];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('gambar')) {
                    $uploadData = $this->upload->data();
                    $dataImage = array(
                        'id_kategori' => $this->input->post('id_kategori'),
                        'id_halaman' => $id_halaman,
                        'gambar' => $uploadData['file_name'],
                    );

                    $imageModel->insert($dataImage);
                }
            }
        }

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        redirect('manual-book');
    }

    public function delete_kategori($id)
    {
        $this->KategoriMBModel->delete_kategori($id);
        redirect('/');
    }

    public function delete_multiple($id)
    {
        $mbModel = new ManualBookModel();
        $imgModel = new ImageMBModel();

        $images = $imgModel->get_images_by_halaman($id);

        foreach ($images as $img) {
            $filePath = './assets/uploads/img/' . $img['gambar'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $deleteMb = $mbModel->delete_manual_book($id);
        $deleteImg = $imgModel->delete_images_by_halaman($id);

        // if ($deleteMb && $deleteImg) {
        //     $this->session->set_flashdata('success', 'Data berhasil dihapus');
        // } else {
        //     $this->session->set_flashdata('error', 'Gagal menghapus data');
        // }

        redirect('manual-book');
    }

    public function delete_img($id)
    {
        $imgModel = new ImageMBModel();

        $image = $imgModel->get_image($id);
        if (!$image) {
            show_404('Gambar tidak ditemukan');
        }

        $filePath = './assets/public/img/' . $image['gambar'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $imgModel->delete_image($id);

        redirect('detail/' . $image['id_halaman']);
    }

    public function delete_imgs() {
        $imgModel = new ImageMBModel();
        $ids = $this->input->post('ids');  // Pastikan ini adalah 'ids', bukan 'id'
    
        error_log('ID yang diterima: ' . print_r($ids, true));
    
        if ($ids && is_array($ids)) {
            $allDeleted = true;
            foreach ($ids as $id) {
                error_log('Memproses ID: ' . $id);
                $image = $imgModel->get_image($id);
                if ($image) {
                    $filePath = './assets/uploads/img/' . $image['gambar'];
                    if (file_exists($filePath)) {
                        if (!unlink($filePath)) {
                            error_log('Gagal menghapus file: ' . $filePath);
                            $allDeleted = false;
                        }
                    }
                    if (!$imgModel->delete_image($id)) {
                        error_log('Gagal menghapus gambar dari database: ID ' . $id);
                        $allDeleted = false;
                    }
                } else {
                    error_log('Gambar tidak ditemukan: ID ' . $id);
                    $allDeleted = false;
                }
            }
            echo json_encode(['success' => $allDeleted]);
        } else {
            error_log('Tidak ada ID yang diterima atau bukan array');
            echo json_encode(['success' => false]);
        }
    }       


    public function update($id)
    {
        $mbModel = new ManualBookModel();
        $imgModel = new ImageMBModel();

        $dataMb = [
            'nama_halaman' => $this->input->post('nama_halaman'),
            'id_kategori' => $this->input->post('id_kategori'),
            'nama_kategori' => $this->input->post('nama_kategori'),
        ];

        if ($mbModel->update($id, $dataMb)) {

        } else {
            redirect('detail/' . $id);
            return;
        }

        $id_gambar = $this->input->post('id');
        $id_kategori = $this->input->post('id_kategori');
        $imgFiles = $_FILES['gambar'];

        for ($index = 0; $index < count($imgFiles['name']); $index++) {
            if ($imgFiles['error'][$index] == 0) {
                $_FILES['single_name']['name'] = $imgFiles['name'][$index];
                $_FILES['single_name']['type'] = $imgFiles['type'][$index];
                $_FILES['single_name']['tmp_name'] = $imgFiles['tmp_name'][$index];
                $_FILES['single_name']['error'] = $imgFiles['error'][$index];
                $_FILES['single_name']['size'] = $imgFiles['size'][$index];
        
                $config['upload_path'] = './assets/uploads/img/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['file_name'] = time() . '_' . $_FILES['single_name']['name'];
        
                $this->upload->initialize($config);
        
                if ($this->upload->do_upload('single_name')) {
                    $uploadData = $this->upload->data();
                    $newName = $uploadData['file_name'];
        
                    $dataImg = [
                        'id_kategori' => $id_kategori,
                        'id_halaman' => $id,
                        'gambar' => $newName,
                    ];
        
                    // Update atau insert data gambar
                    if (!empty($id_gambar[$index])) {
                        $oldImage = $imgModel->find($id_gambar[$index]);
                        if ($oldImage) {
                            $oldFilePath = './assets/uploads/img/' . $oldImage['gambar'];
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }
                        $imgModel->updateImage($id_gambar[$index], $dataImg);
                    } else {
                        $imgModel->insert($dataImg);
                    }
                }
            } else {
                // Update kategori untuk gambar yang tidak diupload ulang
                if (!empty($id_gambar[$index])) {
                    $imgModel->updateImage($id_gambar[$index], ['id_kategori' => $id_kategori]);
                }
            }
        }
        

        redirect('detail/' . $id);
    }
}