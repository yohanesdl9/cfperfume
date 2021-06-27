<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_supplier');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 8;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'supplier' => $this->M_supplier->get_supplier()->result_array(),
      'title' => 'Supplier',
      'content' => 'supplier',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add(){
    $kode = 'SUP/' . str_pad($this->M_app->getLatestId('id', 'tb_supplier'), 6, '0', STR_PAD_LEFT);

    $form = [
      ['label' => 'Kode Supplier*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
      ['label' => 'Nama Supplier*', 'label_width' => 'col-md-2', 'name' => 'nama_supplier', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Alamat Supplier', 'label_width' => 'col-md-2', 'name' => 'alamat', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 5]],
      ['label' => 'Lokasi', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'id_provinsi', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_provinsi,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Provinsi']],
          ['name' => 'id_kota', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kota,keterangan', 'parent_select' => 'id_provinsi', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kota/Kabupaten']],
          ['name' => 'id_kecamatan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kecamatan,keterangan', 'parent_select' => 'id_kota', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kecamatan']],
          ['name' => 'id_kelurahan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kelurahan,keterangan', 'parent_select' => 'id_kecamatan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kelurahan']],
        ]
      ],
      ['label' => 'Telepon*', 'label_width' => 'col-md-2', 'name' => 'telepon', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control']],
    ];
    
    $this->load->view('template/index', [
      'form' => create_form('supplier/tambah_supplier', $form, true),
      'title' => 'Tambah Supplier',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Supplier',
      'base_url' => 'supplier'
    ]);
  }

  public function tambah_supplier(){
    $data = [
      'id' => $this->M_app->getLatestId('id', 'tb_supplier'),
      'kode' => $this->input->post('kode'),
      'nama_supplier' => $this->input->post('nama_supplier'),
      'alamat_supplier' => $this->input->post('alamat'),
      'telepon' => $this->input->post('telepon'),
      'id_provinsi' => $this->input->post('id_provinsi'),
      'id_kota' => $this->input->post('id_kota'),
      'id_kecamatan' => $this->input->post('id_kecamatan'),
      'id_kelurahan' => $this->input->post('id_kelurahan'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_supplier->tambah_supplier($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('supplier/tambah');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('supplier');
  }

  public function edit($id){
    $toko = $this->M_supplier->get_supplier($id)->row_array();

    $form = [
      ['label' => 'Kode Supplier*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $toko['kode']],
      ['label' => 'Nama Supplier*', 'label_width' => 'col-md-2', 'name' => 'nama_supplier', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => $toko['nama_supplier']],
      ['label' => 'Alamat Toko', 'label_width' => 'col-md-2', 'name' => 'alamat', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 5], 'value' => $toko['alamat_supplier']],
      ['label' => 'Lokasi', 'label_width' => 'col-md-2', 
        'group_forms' => [
          ['name' => 'id_provinsi', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_provinsi,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Provinsi'], 'value' => $toko['id_provinsi']],
          ['name' => 'id_kota', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kota,keterangan', 'parent_select' => 'id_provinsi', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kota/Kabupaten'], 'value' => $toko['id_kota']],
          ['name' => 'id_kecamatan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kecamatan,keterangan', 'parent_select' => 'id_kota', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kecamatan'], 'value' => $toko['id_kecamatan']],
          ['name' => 'id_kelurahan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kelurahan,keterangan', 'parent_select' => 'id_kecamatan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kelurahan'], 'value' => $toko['id_kelurahan']],
        ]
      ],
      ['label' => 'Telepon*', 'label_width' => 'col-md-2', 'name' => 'telepon', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => $toko['telepon']]
    ];

    $this->load->view('template/index', [
      'form' => create_form('supplier/edit_supplier/' . $id, $form),
      'title' => 'Edit Supplier',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke halaman Supplier',
      'base_url' => 'supplier'
    ]);
  }

  public function edit_supplier($id) {
    $data = [
      'kode' => $this->input->post('kode'),
      'nama_supplier' => $this->input->post('nama_supplier'),
      'alamat_supplier' => $this->input->post('alamat'),
      'telepon' => $this->input->post('telepon'),
      'id_provinsi' => $this->input->post('id_provinsi'),
      'id_kota' => $this->input->post('id_kota'),
      'id_kecamatan' => $this->input->post('id_kecamatan'),
      'id_kelurahan' => $this->input->post('id_kelurahan'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_supplier->edit_supplier($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('supplier');
  }

  public function hapus_supplier($id) {
    $proc = $this->M_supplier->delete_supplier($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('supplier');
  }

}

/* End of file Toko.php */
?>