<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Toko extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_toko');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 3;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'toko' => $this->M_toko->get_toko()->result_array(),
      'title' => 'Toko',
      'content' => 'toko',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add(){
    $kode = 'TOKO/' . str_pad($this->M_app->getLatestId('id', 'tb_toko'), 5, '0', STR_PAD_LEFT);

    $form = [
      ['label' => 'Kode Toko*', 'label_width' => 'col-md-2', 'name' => 'kode_toko', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
      ['label' => 'Nama Toko*', 'label_width' => 'col-md-2', 'name' => 'nama_toko', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Alamat Toko', 'label_width' => 'col-md-2', 'name' => 'alamat', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 5]],
      ['label' => 'Lokasi', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'id_provinsi', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_provinsi,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Provinsi']],
          ['name' => 'id_kota', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kota,keterangan', 'parent_select' => 'id_provinsi', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kota/Kabupaten']],
          ['name' => 'id_kecamatan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kecamatan,keterangan', 'parent_select' => 'id_kota', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kecamatan']],
          ['name' => 'id_kelurahan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kelurahan,keterangan', 'parent_select' => 'id_kecamatan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kelurahan']],
        ]
      ],
      ['label' => 'Telepon*', 'label_width' => 'col-md-2', 'name' => 'telepon', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Email*', 'label_width' => 'col-md-2', 'name' => 'email', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Logo', 'label_width' => 'col-md-2', 'name' => 'logo', 'type' => 'upload', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control-file']],
      ['label' => 'Jam Operasional*', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'jam_buka', 'type' => 'time', 'width' => 'col-md-2', 'attributes' => ['class' => 'form-control']],
          ['name' => 'jam_tutup', 'type' => 'time', 'width' => 'col-md-2', 'attributes' => ['class' => 'form-control']]
        ]
      ],
    ];

    $this->load->view('template/index', [
      'form' => create_form('toko/tambah_toko', $form, true, true, true),
      'title' => 'Tambah Toko',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Toko',
      'base_url' => 'toko'
    ]);
  }

  public function tambah_toko(){
    $data = [
      'id' => $this->M_app->getLatestId('id', 'tb_toko'),
      'created_at' => date('Y-m-d H:i:s'),
      'kode_toko' => $this->input->post('kode_toko'),
      'nama_toko' => $this->input->post('nama_toko'),
      'alamat' => $this->input->post('alamat') ? $this->input->post('alamat') : NULL,
      'telepon' => $this->input->post('telepon') ? $this->input->post('telepon') : NULL,
      'email' => $this->input->post('email') ? $this->input->post('email') : NULL,
      'id_provinsi' => $this->input->post('id_provinsi'),
      'id_kota' => $this->input->post('id_kota'),
      'id_kecamatan' => $this->input->post('id_kecamatan'),
      'id_kelurahan' => $this->input->post('id_kelurahan'),
      'created_by' => $this->session->userdata('name'),
      'jam_buka' => $this->input->post('jam_buka') ? $this->input->post('jam_buka') : NULL,
      'jam_buka' => $this->input->post('jam_buka') ? $this->input->post('jam_tutup') : NULL,
    ];
    $config = [
      'upload_path' => './uploads/',
      'allowed_types' => 'png|jpg|jpeg|gif',
      'encrypt_name' => true,
      'max_sizes' => 2048,
    ];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('logo')){
      $data['logo'] = 'uploads/' . $this->upload->data('file_name');
    }
    $proc = $this->M_toko->tambah_toko($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('toko/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('toko');
  }

  public function edit($id){
    $toko = $this->M_toko->get_toko($id)->row_array();

    $form = [
      ['label' => 'Kode Toko*', 'label_width' => 'col-md-2', 'name' => 'kode_toko', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $toko['kode_toko']],
      ['label' => 'Nama Toko*', 'label_width' => 'col-md-2', 'name' => 'nama_toko', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => $toko['nama_toko']],
      ['label' => 'Alamat Toko', 'label_width' => 'col-md-2', 'name' => 'alamat', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 5], 'value' => $toko['alamat']],
      ['label' => 'Lokasi', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'id_provinsi', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_provinsi,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Provinsi'], 'value' => $toko['id_provinsi']],
          ['name' => 'id_kota', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kota,keterangan', 'parent_select' => 'id_provinsi', 'datatable_where' => 'id_provinsi = ' . $toko['id_provinsi'], 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kota/Kabupaten'], 'value' => $toko['id_kota']],
          ['name' => 'id_kecamatan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kecamatan,keterangan', 'parent_select' => 'id_kota', 'datatable_where' => 'id_kota = ' . $toko['id_kota'],  'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kecamatan'], 'value' => $toko['id_kecamatan']],
          ['name' => 'id_kelurahan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kelurahan,keterangan', 'parent_select' => 'id_kecamatan', 'datatable_where' => 'id_kecamatan = ' . $toko['id_kecamatan'], 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kelurahan'], 'value' => $toko['id_kelurahan']],
        ]
      ],
      ['label' => 'Telepon*', 'label_width' => 'col-md-2', 'name' => 'telepon', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => $toko['telepon']],
      ['label' => 'Email*', 'label_width' => 'col-md-2', 'name' => 'email', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => $toko['email']],
      ['label' => 'Logo', 'label_width' => 'col-md-2', 'name' => 'logo', 'type' => 'upload', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control-file'], 'value' => $toko['logo'], 'url_remove_picture' => 'toko/hapus_logo/' . $id],
      ['label' => 'Jam Operasional*', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'jam_buka', 'type' => 'time', 'width' => 'col-md-2', 'attributes' => ['class' => 'form-control'], 'value' => $toko['jam_buka']],
          ['name' => 'jam_tutup', 'type' => 'time', 'width' => 'col-md-2', 'attributes' => ['class' => 'form-control'], 'value' => $toko['jam_tutup']]
        ]
      ],
    ];

    $this->load->view('template/index', [
      'form' => create_form('toko/edit_toko/' . $id, $form, false, true, true),
      'title' => 'Edit Toko',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Toko',
      'base_url' => 'toko'
    ]);
  }

  public function edit_toko($id) {
    $data = [
      'updated_at' => date('Y-m-d H:i:s'),
      'kode_toko' => $this->input->post('kode_toko'),
      'nama_toko' => $this->input->post('nama_toko'),
      'alamat' => $this->input->post('alamat') ? $this->input->post('alamat') : NULL,
      'telepon' => $this->input->post('telepon') ? $this->input->post('telepon') : NULL,
      'email' => $this->input->post('email') ? $this->input->post('email') : NULL,
      'id_provinsi' => $this->input->post('id_provinsi'),
      'id_kota' => $this->input->post('id_kota'),
      'id_kecamatan' => $this->input->post('id_kecamatan'),
      'id_kelurahan' => $this->input->post('id_kelurahan'),
      'updated_by' => $this->session->userdata('name'),
      'jam_buka' => $this->input->post('jam_buka') ? $this->input->post('jam_buka') : NULL,
      'jam_buka' => $this->input->post('jam_buka') ? $this->input->post('jam_tutup') : NULL,
    ];
    $config = [
      'upload_path' => './uploads/',
      'allowed_types' => 'png|jpg|jpeg|gif',
      'encrypt_name' => true,
      'max_sizes' => 2048,
    ];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('logo')){
      $data['logo'] = 'uploads/' . $this->upload->data('file_name');
    }
    $proc = $this->M_toko->edit_toko($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('toko');
  }

  public function hapus_logo($id) {
    $logo = $this->M_toko->get_toko($id)->row_array()['logo'];
    unlink($logo);
    $data = [
      'logo' => NULL,
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_toko->edit_toko($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Gambar berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus gambar. Terjadi kesalahan.');
    }
    redirect('toko/edit/' . $id);
  }

  public function hapus_toko($id) {
    $proc = $this->M_toko->delete_toko($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan.');
    }
    redirect('toko');
  }
}

/* End of file Toko.php */
?>