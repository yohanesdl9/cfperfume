<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipe extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_tipe');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 11;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'tipe' => $this->M_tipe->get_tipe()->result_array(),
      'title' => 'Tipe',
      'content' => 'tipe',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }
  
  public function add(){
    $kode = 'TP/' . str_pad($this->M_app->getLatestId('id', 'tb_tipe'), 3, '0', STR_PAD_LEFT);
    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']]
    ];

    $this->load->view('template/index', [
      'form' => create_form('tipe/tambah_tipe', $form, true),
      'title' => 'Tambah Tipe',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Tipe',
      'base_url' => 'tipe'
    ]);
  }

  public function edit($id){
    $tipe = $this->M_tipe->get_tipe($id)->row_array();

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $tipe['kode']],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $tipe['keterangan']]
    ];

    $this->load->view('template/index', [
      'form' => create_form('tipe/update_tipe/' . $id, $form),
      'title' => 'Edit Tipe',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Tipe',
      'base_url' => 'tipe'
    ]);
  }

  public function general($id){
		$this->load->view('template/index', [
      'general' => $this->M_tipe->get_general($id)->result_array(),
      'tipe' => $this->M_tipe->get_tipe($id)->row_array(),
      'title' => 'General',
      'content' => 'general',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function general_add($id){
    $kode = 'GN/' . str_pad($this->M_app->getLatestId('id', 'tb_general'), 5, '0', STR_PAD_LEFT);

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
      ['label' => 'Gambar', 'label_width' => 'col-md-1', 'name' => 'gambar', 'type' => 'upload', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control-file']],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']]
    ];

    $this->load->view('template/index', [
      'form' => create_form('tipe/tambah_general/' . $id, $form, true, true, true),
      'title' => 'Tambah General',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman General',
      'base_url' => 'tipe/general/' . $id
    ]);
  }

  public function general_edit($id_general, $id){
    $general = $this->M_tipe->get_general($id_general, $id)->row_array();

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $general['kode']],
      ['label' => 'Gambar', 'label_width' => 'col-md-1', 'name' => 'gambar', 'type' => 'upload', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control-file'], 'value' => $general['gambar'], 'url_remove_picture' => 'general/hapus_gambar/' . $id_general . '/' . $id],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $general['keterangan']]
    ];

    $this->load->view('template/index', [
      'form' => create_form('tipe/update_general/' . $id_general . '/' . $id, $form, false, true, true),
      'title' => 'Tambah General',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman General',
      'base_url' => 'tipe/general/' . $id_general
    ]);
  }

  public function tambah_tipe(){
    $data = [
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_tipe->insert_tipe($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('tipe/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('tipe');
  }

  public function update_tipe($id){
    $data = [
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_tipe->update_tipe($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('tipe');
  }

  public function hapus_tipe($id){
    $proc = $this->M_tipe->delete_tipe($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan.');
    }
    redirect('tipe');
  }

  public function tambah_general($id){
    $config = [
      'upload_path' => './uploads/',
      'allowed_types' => 'png|jpg|jpeg|gif',
      'encrypt_name' => true,
      'max_sizes' => 2048,
    ];
    $this->upload->initialize($config);
    $data = [
      'id_tipe' => $id,
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    if ($this->upload->do_upload('gambar')){
      $data['gambar'] = 'uploads/' . $this->upload->data('file_name');
    }
    $proc = $this->M_tipe->insert_general($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('tipe/general_add/' . $id);
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('tipe/general/' . $id);
  }

  public function hapus_gambar_general($id_tipe, $id){
    $picture = $this->M_tipe->get_general($id_tipe, $id)->row_array()['gambar'];
    unlink($picture);
    $data = [
      'gambar' => NULL, 
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_tipe->update_general($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Gambar berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus gambar. Terjadi kesalahan.');
    }
    redirect('tipe/general_edit/' . $id_tipe . '/' . $id);
  }

  public function update_general($id_tipe, $id){
    $config = [
      'upload_path' => './uploads/',
      'allowed_types' => 'png|jpg|jpeg|gif',
      'encrypt_name' => true,
      'max_sizes' => 2048,
    ];
    $this->upload->initialize($config);
    $data = [
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    if ($this->upload->do_upload('gambar')){
      $data['gambar'] = 'uploads/' . $this->upload->data('file_name');
    }
    $proc = $this->M_tipe->update_general($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('tipe/general/' . $id_tipe);
  }

  public function hapus_general($id_tipe, $id){
    $proc = $this->M_tipe->delete_general($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan.');
    }
    redirect('tipe/general/' . $id_tipe);
  }
}

/* End of file Tipe.php */
 ?>