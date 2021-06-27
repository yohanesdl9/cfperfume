<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_pelanggan');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 5;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'pelanggan' => $this->M_pelanggan->get_pelanggan()->result_array(),
      'title' => 'Pelanggan',
      'content' => 'pelanggan/index',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add() {
    $kode = 'PLGN/' . str_pad($this->M_app->getLatestId('id', 'tb_pelanggan'), 5, '0', STR_PAD_LEFT);

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
      ['label' => 'Nama*', 'label_width' => 'col-md-1', 'name' => 'nama', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Email*', 'label_width' => 'col-md-1', 'name' => 'email', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Telepon*', 'label_width' => 'col-md-1', 'name' => 'telepon', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Telepon 2', 'label_width' => 'col-md-1', 'name' => 'telepon2', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
    ];

    $this->load->view('template/index', [
      'form' => create_form('pelanggan/tambah_pelanggan', $form, true),
      'title' => 'Tambah Pelanggan',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Pelanggan',
      'base_url' => 'pelanggan'
    ]);
  }

  public function tambah_pelanggan(){
    $data = [
      'id' => $this->M_app->getLatestId('id', 'tb_pelanggan'),
      'kode' => $this->input->post('kode'),
      'nama' => $this->input->post('nama'),
      'email' => $this->input->post('email'),
      'telepon' => $this->input->post('telepon'),
      'telepon2' => $this->input->post('telepon2') ? $this->input->post('telepon2') : NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_pelanggan->insert_pelanggan($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('pelanggan/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('pelanggan');
  }

  public function edit($id) {
    $pelanggan = $this->M_pelanggan->get_pelanggan($id)->row_array();

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $pelanggan['kode']],
      ['label' => 'Nama*', 'label_width' => 'col-md-1', 'name' => 'nama', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $pelanggan['nama']],
      ['label' => 'Email*', 'label_width' => 'col-md-1', 'name' => 'email', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $pelanggan['email']],
      ['label' => 'Telepon*', 'label_width' => 'col-md-1', 'name' => 'telepon', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $pelanggan['telepon']],
      ['label' => 'Telepon 2', 'label_width' => 'col-md-1', 'name' => 'telepon2', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $pelanggan['telepon2']],
    ];

    $this->load->view('template/index', [
      'form' => create_form('pelanggan/edit_pelanggan/' . $id, $form),
      'title' => 'Edit Pelanggan',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Pelanggan',
      'base_url' => 'pelanggan'
    ]);
  }

  public function edit_pelanggan($id) {
    $data = [
      'kode' => $this->input->post('kode'),
      'nama' => $this->input->post('nama'),
      'email' => $this->input->post('email'),
      'telepon' => $this->input->post('telepon'),
      'telepon2' => $this->input->post('telepon2') ? $this->input->post('telepon2') : NULL,
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_pelanggan->update_pelanggan($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('pelanggan');
  }

  public function hapus_pelanggan($id) {
    $proc = $this->M_pelanggan->delete_pelanggan($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('pelanggan');
  }

  public function alamat($id_pelanggan) {
    $this->load->view('template/index', [
      'pelanggan' => $this->M_pelanggan->get_pelanggan($id_pelanggan)->row_array(),
      'alamat' => $this->M_pelanggan->alamat_pelanggan($id_pelanggan)->result_array(),
      'title' => 'Alamat Pelanggan',
      'content' => 'pelanggan/alamat',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add_alamat($id_pelanggan) {
    $kode = 'ALMT/' . str_pad($this->M_app->getLatestId('id', 'tb_pelanggan_alamat'), 5, '0', STR_PAD_LEFT);

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-2', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Alamat Lengkap', 'label_width' => 'col-md-2', 'name' => 'alamat_lengkap', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 5]],
      ['label' => 'Lokasi', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'id_provinsi', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_provinsi,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Provinsi']],
          ['name' => 'id_kota', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kota,keterangan', 'parent_select' => 'id_provinsi', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kota/Kabupaten']],
          ['name' => 'id_kecamatan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kecamatan,keterangan', 'parent_select' => 'id_kota', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kecamatan']],
          ['name' => 'id_kelurahan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kelurahan,keterangan', 'parent_select' => 'id_kecamatan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kelurahan']],
        ]
      ],
    ];

    $this->load->view('template/index', [
      'form' => create_form('pelanggan/tambah_pelanggan_alamat/' . $id_pelanggan, $form, true),
      'title' => 'Tambah Alamat Pelanggan',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Pelanggan',
      'base_url' => 'pelanggan/alamat/' . $id_pelanggan
    ]);
  }

  public function tambah_pelanggan_alamat($id_pelanggan){
    $data = [
      'id' => $this->M_app->getLatestId('id', 'tb_pelanggan_alamat'),
      'kode' => $this->input->post('kode'),
      'id_pelanggan' => $id_pelanggan,
      'keterangan' => $this->input->post('keterangan'),
      'alamat_lengkap' => $this->input->post('alamat_lengkap'),
      'id_provinsi' => $this->input->post('id_provinsi'),
      'id_kota' => $this->input->post('id_kota'),
      'id_kecamatan' => $this->input->post('id_kecamatan'),
      'id_kelurahan' => $this->input->post('id_kelurahan'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_pelanggan->insert_pelanggan_alamat($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('pelanggan/add_alamat/' . $id_pelanggan);
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('pelanggan/alamat/' . $id_pelanggan);
  }

  public function edit_alamat($id_pelanggan, $id) {
    $alamat = $this->M_pelanggan->alamat_pelanggan($id_pelanggan, $id)->row_array();

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $alamat['kode']],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-2', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => $alamat['keterangan']],
      ['label' => 'Alamat Lengkap', 'label_width' => 'col-md-2', 'name' => 'alamat_lengkap', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 5], 'value' => $alamat['alamat_lengkap']],
      ['label' => 'Lokasi', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'id_provinsi', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_provinsi,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Provinsi'], 'value' => $alamat['id_provinsi']],
          ['name' => 'id_kota', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kota,keterangan', 'parent_select' => 'id_provinsi', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kota/Kabupaten'], 'value' => $alamat['id_kota']],
          ['name' => 'id_kecamatan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kecamatan,keterangan', 'parent_select' => 'id_kota', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kecamatan'], 'value' => $alamat['id_kecamatan']],
          ['name' => 'id_kelurahan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kelurahan,keterangan', 'parent_select' => 'id_kecamatan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kelurahan'], 'value' => $alamat['id_kelurahan']],
        ]
      ],
    ];

    $this->load->view('template/index', [
      'form' => create_form('pelanggan/edit_pelanggan_alamat/' . $id_pelanggan . '/' . $id, $form),
      'title' => 'Edit Alamat Pelanggan',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Alamat Pelanggan',
      'base_url' => 'pelanggan/alamat/' . $id_pelanggan 
    ]);
  }

  public function edit_pelanggan_alamat($id_pelanggan, $id) {
    $data = [
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'alamat_lengkap' => $this->input->post('alamat_lengkap'),
      'id_provinsi' => $this->input->post('id_provinsi'),
      'id_kota' => $this->input->post('id_kota'),
      'id_kecamatan' => $this->input->post('id_kecamatan'),
      'id_kelurahan' => $this->input->post('id_kelurahan'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_pelanggan->update_pelanggan_alamat($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('pelanggan/alamat/' . $id_pelanggan);
  }

  public function hapus_alamat($id_pelanggan, $id) {
    $proc = $this->M_pelanggan->delete_pelanggan_alamat($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('pelanggan/alamat/' . $id_pelanggan);
  }
}

/* End of file Pelanggan.php */
 ?>