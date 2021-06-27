<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_item');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 3;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'item' => $this->M_item->get_item()->result_array(),
      'title' => 'Item',
      'content' => 'item/index',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add(){
    $kode = 'ITEM/' . str_pad($this->M_app->getLatestId('id', 'tb_item'), 5, '0', STR_PAD_LEFT);

    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-2', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Kategori*', 'label_width' => 'col-md-2', 'name' => 'id_kategori', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_general,keterangan', 'datatable_where' => 'id_tipe = 1', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kategori']],
      ['label' => 'Satuan*', 'label_width' => 'col-md-2', 'name' => 'id_satuan', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_general,keterangan', 'datatable_where' => 'id_tipe = 2', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Satuan']],
      ['label' => 'Harga Beli*', 'label_width' => 'col-md-2', 'name' => 'harga_beli', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'min' => 0]],
      ['label' => 'Harga Jual*', 'label_width' => 'col-md-2', 'name' => 'harga_jual', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'min' => 0]],
      ['label' => 'Deskripsi*', 'label_width' => 'col-md-2', 'name' => 'deskripsi', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 10, 'id' => 'elm1']],
      ['label' => 'Gambar', 'label_width' => 'col-md-2', 'name' => 'gambar', 'type' => 'upload', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control-file']],
    ];

    $this->load->view('template/index', [
      'title' => 'Tambah Item',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'form' => create_form('item/tambah_item', $form, true, true, true),
      'back_text' => 'Kembali ke halaman Item',
      'base_url' => 'item'
    ]);
  }

  public function tambah_item(){
    $data = [
      'id' => $this->M_app->getLatestId('id', 'tb_item'),
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'id_kategori' => $this->input->post('id_kategori'),
      'id_satuan' => $this->input->post('id_satuan'),
      'harga_beli' => $this->input->post('harga_beli'),
      'harga_jual' => $this->input->post('harga_jual'),
      'deskripsi' => $this->input->post('deskripsi'),
      'qty' => 0,
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    $config = [
      'upload_path' => './uploads/',
      'allowed_types' => 'png|jpg|jpeg|gif',
      'encrypt_name' => true,
      'max_sizes' => 2048,
    ];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('gambar')){
      $data['gambar'] = 'uploads/' . $this->upload->data('file_name');
    }
    $proc = $this->M_item->tambah_item($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('item/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('item');
  }

  public function edit($id_item){
    $item = $this->M_item->get_item($id_item)->row_array();
    
    $form = [
      ['label' => 'Kode*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $item['kode']],
      ['label' => 'Keterangan*', 'label_width' => 'col-md-2', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => $item['keterangan']],
      ['label' => 'Kategori*', 'label_width' => 'col-md-2', 'name' => 'id_kategori', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_general,keterangan', 'datatable_where' => 'id_tipe = 1', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kategori'], 'value' => $item['id_kategori']],
      ['label' => 'Satuan*', 'label_width' => 'col-md-2', 'name' => 'id_satuan', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_general,keterangan', 'datatable_where' => 'id_tipe = 2', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Satuan'], 'value' => $item['id_satuan']],
      ['label' => 'Harga Beli*', 'label_width' => 'col-md-2', 'name' => 'harga_beli', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => $item['harga_beli']],
      ['label' => 'Harga Jual*', 'label_width' => 'col-md-2', 'name' => 'harga_jual', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => $item['harga_jual']],
      ['label' => 'Deskripsi*', 'label_width' => 'col-md-2', 'name' => 'deskripsi', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 10, 'id' => 'elm1'], 'value' => $item['deskripsi']],
      ['label' => 'Gambar', 'label_width' => 'col-md-2', 'name' => 'gambar', 'type' => 'upload', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control-file'], 'value' => $item['gambar'], 'url_remove_picture' => 'item/hapus_gambar/' . $id_item],
    ];

    $this->load->view('template/index', [
      'title' => 'Edit Item',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'form' => create_form('item/edit_item/' . $id_item, $form, false, true, true),
      'back_text' => 'Kembali ke halaman Item',
      'base_url' => 'item'
    ]);
  }

  public function edit_item($id) {
    $data = [
      'kode' => $this->input->post('kode'),
      'keterangan' => $this->input->post('keterangan'),
      'id_kategori' => $this->input->post('id_kategori'),
      'id_satuan' => $this->input->post('id_satuan'),
      'harga_beli' => $this->input->post('harga_beli'),
      'harga_jual' => $this->input->post('harga_jual'),
      'deskripsi' => $this->input->post('deskripsi'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $config = [
      'upload_path' => './uploads/',
      'allowed_types' => 'png|jpg|jpeg|gif',
      'encrypt_name' => true,
      'max_sizes' => 2048,
    ];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('gambar')){
      $data['gambar'] = 'uploads/' . $this->upload->data('file_name');
    }
    $proc = $this->M_item->edit_item($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('item');
  }

  public function hapus_gambar($id) {
    $logo = $this->M_item->get_item($id)->row_array()['gambar'];
    unlink($logo);
    $data = [
      'gambar' => NULL,
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_item->edit_item($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Gambar berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus gambar. Terjadi kesalahan.');
    }
    redirect('item/edit/' . $id);
  }

  public function hapus_item($id) {
    $proc = $this->M_item->hapus_item($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan.');
    }
    redirect('item');
  }

  public function stok($id) {
    $this->load->view('template/index', [
      'item' => $this->M_item->get_item($id)->row_array(),
      'title' => 'Stock Item',
      'content' => 'item/stok',
      'stok' => $this->M_item->get_item_stock($id),
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function stok_tambah($id) {
    $form = [
      ['label' => 'Tanggal', 'label_width' => 'col-md-1', 'name' => 'tanggal', 'type' => 'datetime', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => date('Y-m-d H:i:s')],
      ['label' => 'Stock Masuk', 'label_width' => 'col-md-1', 'name' => 'stock_masuk', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
      ['label' => 'Stock Keluar', 'label_width' => 'col-md-1', 'name' => 'stock_keluar', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
      ['label' => 'Keterangan', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
    ];
    $this->load->view('template/index', [
      'form' => create_form('item/stok_tambah_data/' . $id, $form, true),
      'title' => 'Tambah Stock Item',
      'content' => 'forms',
      'menu' => $this->menu,
      'access' => $this->access,
      'back_text' => 'Kembali ke Halaman Item Stok',
      'base_url' => 'item/stok/' . $id
    ]);
  }

  public function stok_tambah_data($id) {
    $data = [
      'id' => $this->M_app->getLatestId('id', 'tb_item_stok'),
      'id_item' => $id,
      'tanggal' => date('Y-m-d H:i:s', strtotime($this->input->post('tanggal'))),
      'stok_masuk' => $this->input->post('stock_masuk'),
      'stok_keluar' => $this->input->post('stock_keluar'),
      'keterangan' => $this->input->post('keterangan'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_item->tambah_stock($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('item/stok_tambah/' . $id);
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('item/stok/' . $id);
  }

  public function get_item_detail($id_item) {
    $item = $this->M_item->get_item($id_item)->row_array();
    echo json_encode($item);
  }
}

/* End of file Item.php */
 ?>