<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_pengeluaran');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 7;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'pengeluaran' => $this->M_pengeluaran->get_pengeluaran()->result_array(),
      'title' => 'Pengeluaran',
      'content' => 'pengeluaran/index',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add(){
    $kode = 'PENG' . str_pad($this->M_app->getLatestId('id', 'tb_pengeluaran'), 6, '0', STR_PAD_LEFT);

    $form = [
      // Row 1 sebelum detail pengeluaran
      [
        ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
        ['label' => 'Tanggal', 'label_width' => 'col-md-1', 'name' => 'tanggal', 'type' => 'date', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => date('Y-m-d')],
      ],
      // Row 2 setelah detail pengeluaran
      [
        ['label' => 'Grand Total', 'label_width' => 'col-md-1', 'name' => 'grand_total', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Keterangan', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
      ]
    ];

    $this->load->view('template/index', [
      'form' => $form,
      'title' => 'Tambah Pengeluaran',
      'content' => 'pengeluaran/tambah',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function tambah_pengeluaran(){
    $detail = [];
    $nama_barang = $this->input->post('nama_produk');
    $harga = $this->input->post('harga');
    $kuantitas = $this->input->post('kuantitas');
    $subtotal = $this->input->post('subtotal');
    $id = $this->M_app->getLatestId('id', 'tb_pengeluaran');
    $data = [
      'id' => $id,
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'tanggal' => $this->input->post('tanggal'),
      'subtotal' => $this->input->post('grand_total'),
      'pajak' => 0,
      'diskon_tipe' => 'nominal',
      'diskon' => 0,
      'grand_total' => $this->input->post('grand_total'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name'),
      'users_id' => $this->session->userdata('id')
    ];
    for ($i = 0; $i < count($nama_barang); $i++) {
      $detail[] = [
        'id' => $this->M_app->getLatestId('id', 'tb_pengeluaran_detail') + $i,
        'id_pengeluaran' => $id,
        'kode_pengeluaran' => $this->input->post('kode'),
        'nama_produk' => $nama_barang[$i],
        'harga' => $harga[$i],
        'kuantitas' => $kuantitas[$i],
        'subtotal' => $subtotal[$i],
        'created_at' => date('Y-m-d H:i:s')
      ];
    }
    $proc = $this->M_pengeluaran->insert_pengeluaran($data, $detail);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('pengeluaran/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('pengeluaran');
  }

  public function edit($id){
    $pengeluaran = $this->M_pengeluaran->get_pengeluaran($id)->row_array();
    $detail = $this->M_pengeluaran->get_detail_pengeluaran($id);
    $form = [
      // Row 1 sebelum detail pengeluaran
      [
        ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $pengeluaran['kode']],
        ['label' => 'Tanggal', 'label_width' => 'col-md-1', 'name' => 'tanggal', 'type' => 'date', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $pengeluaran['tanggal']],
      ],
      // Row 2 setelah detail pengeluaran
      [
        ['label' => 'Grand Total', 'label_width' => 'col-md-1', 'name' => 'grand_total', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $pengeluaran['grand_total']],
        ['label' => 'Keterangan', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $pengeluaran['keterangan']],
      ]
    ];

    $this->load->view('template/index', [
      'form' => $form,
      'detail' => $detail,
      'title' => 'Edit Pengeluaran',
      'content' => 'pengeluaran/edit',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function edit_pengeluaran($id){
    $detail = [];
    $nama_barang = $this->input->post('nama_produk');
    $harga = $this->input->post('harga');
    $kuantitas = $this->input->post('kuantitas');
    $subtotal = $this->input->post('subtotal');
    $data = [
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'tanggal' => $this->input->post('tanggal'),
      'subtotal' => $this->input->post('grand_total'),
      'pajak' => 0,
      'diskon_tipe' => 'nominal',
      'diskon' => 0,
      'grand_total' => $this->input->post('grand_total'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name'),
      'users_id' => $this->session->userdata('id')
    ];
    for ($i = 0; $i < count($nama_barang); $i++) {
      $detail[] = [
        'id' => $this->M_app->getLatestId('id', 'tb_pengeluaran_detail') + $i,
        'id_pengeluaran' => $id,
        'kode_pengeluaran' => $this->input->post('kode'),
        'nama_produk' => $nama_barang[$i],
        'harga' => $harga[$i],
        'kuantitas' => $kuantitas[$i],
        'subtotal' => $subtotal[$i],
        'created_at' => date('Y-m-d H:i:s')
      ];
    }
    $proc = $this->M_pengeluaran->update_pengeluaran($id, $data, $detail);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('pengeluaran');
  }

  public function hapus_pengeluaran($id) {
    $proc = $this->M_pengeluaran->delete_pengeluaran($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan.');
    }
    redirect('pengeluaran');
  }
}

/* End of file Pengeluaran.php */
?>