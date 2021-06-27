<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model(array('M_pembelian', 'M_item'));
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 6;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'pembelian' => $this->M_pembelian->get_pembelian()->result_array(),
      'title' => 'Pembelian',
      'content' => 'pembelian/index',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add(){
    $kode = $this->M_pembelian->getLatestIdPembelian();
    $data_item = $this->M_item->get_item()->result_array();
    $dropdown_item = [];
    foreach ($data_item as $di) $dropdown_item[$di['id']] = $di['keterangan'];

    $form = [
      // Row 1 sebelum detail pembelian
      [
        ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
        ['label' => 'Tanggal', 'label_width' => 'col-md-1', 'name' => 'tanggal', 'type' => 'datetime-local', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => date('Y-m-d\TH:i:s')],
        ['label' => 'Supplier', 'label_width' => 'col-md-1', 'name' => 'id_supplier', 'type' => 'select', 'width' => 'col-md-11', 'datatable' => 'tb_supplier,nama_supplier', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Supplier']]
      ],
      [
        ['label' => 'Item', 'name' => 'id_produk1', 'width' => 'col-md-2', 'type' => 'select', 'dataenum' => $dropdown_item, 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Item']],
        ['label' => 'Harga', 'name' => 'harga1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true]],
        ['label' => 'Kuantitas', 'name' => 'kuantitas1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Tipe Diskon', 'name' => 'diskon_tipe1',  'width' => 'col-md-2', 'type' => 'select', 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'attributes' => ['class' => 'form-control', 'placeholder' => 'Pilih Tipe Diskon'], 'value' => 'nominal'],
        ['label' => 'Diskon', 'name' => 'diskon1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Subtotal', 'name' => 'subtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Grand Total', 'name' => 'grandtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0]
      ],
      // Row 3 setelah detail pembelian
      [
        ['label' => 'Subtotal', 'label_width' => 'col-md-1', 'name' => 'subtotal_all', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Pajak (%)', 'label_width' => 'col-md-1', 'name' => 'pajak', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Tipe Diskon', 'label_width' => 'col-md-1', 'name' => 'diskon_tipe_all', 'type' => 'radio', 'inline' => true, 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'width' => 'col-md-11', 'attributes' => ['class' => 'form-check-input'], 'value' => 'nominal'],
        ['label' => 'Diskon', 'label_width' => 'col-md-1', 'name' => 'diskon_all', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Grand Total', 'label_width' => 'col-md-1', 'name' => 'grand_total_all', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Keterangan', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
      ]
    ];

    $this->load->view('template/index', [
      'form' => $form,
      'title' => 'Tambah Pembelian',
      'content' => 'pembelian/tambah',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function tambah_pembelian(){
    $id = $this->M_app->getLatestId('id', 'tb_pembelian');
    $kode = $this->input->post('kode');
    $id_produks = $this->input->post('id_produk');
    $hargas = $this->input->post('harga');
    $quantities = $this->input->post('kuantitas');
    $diskon_tipes = $this->input->post('diskon_tipe');
    $diskons = $this->input->post('diskon');
    $subtotals = $this->input->post('subtotal');
    $grandtotals = $this->input->post('grandtotal');
    $detail_beli = [];
    $data = [
      'id' => $id,
      'kode' => $kode,
      'tanggal' => date('Y-m-d H:i:s', $this->input->post('tanggal')),
      'keterangan' => $this->input->post('keterangan'),
      'id_supplier' => $this->input->post('id_supplier'),
      'pajak' => $this->input->post('pajak'),
      'subtotal' => $this->input->post('subtotal_all'),
      'diskon_tipe' => $this->input->post('diskon_tipe_all'),
      'diskon' => $this->input->post('diskon_all'),
      'grand_total' => $this->input->post('grand_total_all'),
      'created_at' => date('Y-m-d H:i:s'),
      'users_id' => $this->session->userdata('id')
    ];
    for ($i = 0; $i < count($id_produks); $i++) {
      $item = $this->M_app->getDataByParameter('id', $id_produks[$i], 'tb_item')->row_array();
      $detail_beli[] = [
        'id' => $this->M_app->getLatestId('id', 'tb_pembelian_detail') + $i,
        'id_pembelian' => $id,
        'kode_pembelian' => $kode,
        'id_item' => $id_produks[$i],
        'kode_item' => $item['kode'],
        'nama_item' => $item['keterangan'],
        'satuan' => $item['id_satuan'],
        'harga' => $hargas[$i],
        'kuantitas' => $quantities[$i],
        'diskon' => $diskons[$i],
        'diskon_tipe' => $diskon_tipes[$i],
        'subtotal' => $subtotals[$i],
        'grand_total' => $grandtotals[$i],
        'created_at' => date('Y-m-d H:i:s')
      ];
    }
    $proc = $this->M_pembelian->insert_pembelian($data, $detail_beli);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('pembelian/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('pembelian');
  }

  public function edit($id){
    $pembelian = $this->M_pembelian->get_pembelian($id)->row_array();
    $detail_pembelian = $this->M_pembelian->get_detail_pembelian($id);
    $data_item = $this->M_item->get_item()->result_array();
    $dropdown_item = [];
    foreach ($data_item as $di) $dropdown_item[$di['id']] = $di['keterangan'];
    foreach ($detail_pembelian as $dpi) {
      if (in_array($dpi['nama_item'], $dropdown_item)) unset($dropdown_item[$dpi['id_item']]);
    }

    $form = [
      // Row 1 sebelum detail pembelian
      [
        ['label' => 'Kode*', 'label_width' => 'col-md-1', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $pembelian['kode']],
        ['label' => 'Tanggal', 'label_width' => 'col-md-1', 'name' => 'tanggal', 'type' => 'datetime-local', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => date('Y-m-d\TH:i:s', strtotime($pembelian['tanggal']))],
        ['label' => 'Supplier', 'label_width' => 'col-md-1', 'name' => 'id_supplier', 'type' => 'select', 'width' => 'col-md-11', 'datatable' => 'tb_supplier,nama_supplier', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Supplier'], 'value' => $pembelian['id_supplier']]
      ],
      [
        ['label' => 'Item', 'name' => 'id_produk1', 'width' => 'col-md-2', 'type' => 'select', 'dataenum' => $dropdown_item, 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Item']],
        ['label' => 'Harga', 'name' => 'harga1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true]],
        ['label' => 'Kuantitas', 'name' => 'kuantitas1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Tipe Diskon', 'name' => 'diskon_tipe1',  'width' => 'col-md-2', 'type' => 'select', 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'attributes' => ['class' => 'form-control', 'placeholder' => 'Pilih Tipe Diskon'], 'value' => 'nominal'],
        ['label' => 'Diskon', 'name' => 'diskon1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Subtotal', 'name' => 'subtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Grand Total', 'name' => 'grandtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0]
      ],
      // Row 3 setelah detail pembelian
      [
        ['label' => 'Subtotal', 'label_width' => 'col-md-1', 'name' => 'subtotal_all', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $pembelian['subtotal']],
        ['label' => 'Pajak (%)', 'label_width' => 'col-md-1', 'name' => 'pajak', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => $pembelian['pajak']],
        ['label' => 'Tipe Diskon', 'label_width' => 'col-md-1', 'name' => 'diskon_tipe_all', 'type' => 'radio', 'inline' => true, 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'width' => 'col-md-11', 'attributes' => ['class' => 'form-check-input'], 'value' => $pembelian['diskon_tipe']],
        ['label' => 'Diskon', 'label_width' => 'col-md-1', 'name' => 'diskon_all', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => $pembelian['diskon']],
        ['label' => 'Grand Total', 'label_width' => 'col-md-1', 'name' => 'grand_total_all', 'type' => 'number', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $pembelian['grand_total']],
        ['label' => 'Keterangan', 'label_width' => 'col-md-1', 'name' => 'keterangan', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control'], 'value' => $pembelian['keterangan']],
      ],
    ];

    $form_detail = []; $i = 1;
    foreach ($detail_pembelian as $d) {
      $form_detail[] = [
        ['name' => 'id_produk[]', 'type' => 'hidden', 'attributes' => ['id' => 'id_produk-' . $i], 'value' => $d['id_item']],
        ['name' => 'nama_produk[]', 'type' => 'hidden', 'attributes' => ['id' => 'nama_produk-' . $i], 'value' => $d['nama_item']],
        ['name' => 'harga[]', 'type' => 'hidden', 'attributes' => ['id' => 'harga-' . $i, 'class' => 'harga'], 'value' => $d['harga']],
        ['name' => 'kuantitas[]', 'type' => 'number', 'attributes' => ['id' => 'kuantitas-' . $i, 'class' => 'form-control kuantitas', 'min' => 1], 'value' => $d['kuantitas']],
        ['name' => 'diskon_tipe[]', 'type' => 'select', 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'attributes' => ['class' => 'form-control diskon_tipe', 'id' => 'diskontipe-' . $i], 'value' => $d['diskon_tipe']],
        ['name' => 'diskon[]', 'type' => 'number', 'attributes' => ['id' => 'diskon-' . $i, 'class' => 'form-control diskon', 'min' => 0, 'max' => ($d['diskon_tipe'] == 'persen' ? '100' : $d['subtotal'])], 'value' => $d['diskon']],
        ['name' => 'subtotal[]', 'type' => 'hidden', 'attributes' => ['id' => 'subtotal-' . $i, 'class' => 'subtotal'], 'value' => $d['subtotal']],
        ['name' => 'grandtotal[]', 'type' => 'hidden', 'attributes' => ['id' => 'grandtotal-' . $i, 'class' => 'grandtotal'], 'value' => $d['grand_total']],
      ];
      $i++;
    }
    $this->load->view('template/index', [
      'form' => $form,
      'title' => 'Edit Pembelian',
      'content' => 'pembelian/edit',
      'detail_pembelian' => $detail_pembelian,
      'menu' => $this->menu,
      'access' => $this->access,
      'form_detail' => $form_detail
    ]);
  }

  public function edit_pembelian($id){
    $kode = $this->input->post('kode');
    $id_produks = $this->input->post('id_produk');
    $hargas = $this->input->post('harga');
    $quantities = $this->input->post('kuantitas');
    $diskon_tipes = $this->input->post('diskon_tipe');
    $diskons = $this->input->post('diskon');
    $subtotals = $this->input->post('subtotal');
    $grandtotals = $this->input->post('grandtotal');
    $detail_beli = [];
    $data = [
      'kode' => $kode,
      'tanggal' => date('Y-m-d H:i:s', $this->input->post('tanggal')),
      'keterangan' => $this->input->post('keterangan'),
      'id_supplier' => $this->input->post('id_supplier'),
      'pajak' => $this->input->post('pajak'),
      'subtotal' => $this->input->post('subtotal_all'),
      'diskon_tipe' => $this->input->post('diskon_tipe_all'),
      'diskon' => $this->input->post('diskon_all'),
      'grand_total' => $this->input->post('grand_total_all'),
      'updated_at' => date('Y-m-d H:i:s'),
      'users_id' => $this->session->userdata('id')
    ];
    for ($i = 0; $i < count($id_produks); $i++) {
      $item = $this->M_app->getDataByParameter('id', $id_produks[$i], 'tb_item')->row_array();
      $detail_beli[] = [
        'id' => $this->M_app->getLatestId('id', 'tb_pembelian_detail') + $i,
        'id_pembelian' => $id,
        'kode_pembelian' => $kode,
        'id_item' => $id_produks[$i],
        'kode_item' => $item['kode'],
        'nama_item' => $item['keterangan'],
        'satuan' => $item['id_satuan'],
        'harga' => $hargas[$i],
        'kuantitas' => $quantities[$i],
        'diskon' => $diskons[$i],
        'diskon_tipe' => $diskon_tipes[$i],
        'subtotal' => $subtotals[$i],
        'grand_total' => $grandtotals[$i],
        'created_at' => date('Y-m-d H:i:s')
      ];
    }
    $proc = $this->M_pembelian->update_pembelian($id, $data, $detail_beli);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('pembelian');
  }

  public function hapus_pembelian($id) {
    $proc = $this->M_pembelian->delete_pembelian($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan.');
    }
    redirect('pembelian');
  }

  public function get_pembelian($id) {
    $pembelian = $this->M_pembelian->get_pembelian($id)->row_array();
    $pembelian['detail'] = $this->M_pembelian->get_detail_pembelian($id);
    echo json_encode($pembelian);
  }
}

/* End of file Pembelian.php */
?>