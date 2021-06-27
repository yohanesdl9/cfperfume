<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model(array('M_penjualan', 'M_item', 'M_pelanggan'));
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 5;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'penjualan' => $this->M_penjualan->get_penjualan()->result_array(),
      'title' => 'Penjualan',
      'content' => 'penjualan/index',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add(){
    $kode = $this->M_penjualan->getLatestIdPenjualan();
    $data_item = $this->M_item->get_item('', true)->result_array();
    $dropdown_item = [];
    foreach ($data_item as $di) $dropdown_item[$di['id']] = $di['keterangan'];

    $form = [
      // Row 1 sebelum detail penjualan
      [
        ['label' => 'Kode*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $kode],
        ['label' => 'Tanggal', 'label_width' => 'col-md-2', 'name' => 'tanggal', 'type' => 'datetime-local', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => date('Y-m-d\TH:i:s')],
        ['label' => 'Toko', 'label_width' => 'col-md-2', 'name' => 'id_toko', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_toko,nama_toko', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Toko']],
        ['label' => 'Pelanggan Baru', 'label_width' => 'col-md-2', 'name' => 'is_new_pelanggan', 'type' => 'checkbox', 'width' => 'col-md-10', 'dataenum' => ['new' => 'Pelanggan Baru'], 'attributes' => ['class' => 'form-check-input']],
        ['label' => 'Pelanggan', 'label_width' => 'col-md-2', 'name' => 'id_pelanggan', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_pelanggan,nama', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Pelanggan']],
        ['label' => 'Nama Pelanggan', 'label_width' => 'col-md-2', 'name' => 'nama_pelanggan', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'disabled' => true]],
        ['label' => 'Alamat Baru', 'label_width' => 'col-md-2', 'name' => 'is_new_alamat', 'type' => 'checkbox', 'width' => 'col-md-10', 'dataenum' => ['new' => 'Alamat Baru'], 'attributes' => ['class' => 'form-check-input']],
        ['label' => 'Alamat Pengiriman', 'label_width' => 'col-md-2', 'name' => 'id_alamat_pelanggan', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'view_alamat_lengkap,alamat_lengkap', 'parent_select' => 'id_pelanggan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Alamat Pelanggan']],
        ['label' => 'Alamat Lengkap', 'label_width' => 'col-md-2', 'name' => 'alamat_lengkap', 'type' => 'textarea', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'rows' => 5, 'disabled' => true]],
        ['label' => 'Lokasi', 'label_width' => 'col-md-2', 
          'group_forms' => [
            ['name' => 'id_provinsi', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_provinsi,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Provinsi', 'disabled' => true]],
            ['name' => 'id_kota', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kota,keterangan', 'parent_select' => 'id_provinsi', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kota/Kabupaten', 'disabled' => true]],
            ['name' => 'id_kecamatan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kecamatan,keterangan', 'parent_select' => 'id_kota', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kecamatan', 'disabled' => true]],
            ['name' => 'id_kelurahan', 'type' => 'select', 'width' => 'col-md-2', 'datatable' => 'tb_kelurahan,keterangan', 'parent_select' => 'id_kecamatan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kelurahan', 'disabled' => true]],
          ]
        ],
        ['label' => 'Sumber Transaksi', 'label_width' => 'col-md-2', 'name' => 'sumber_transaksi', 'type' => 'select', 'width' => 'col-md-10', 'dataenum' => ['Tokopedia','Shopee','WhatsApp','Website'], 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Sumber Transaksi']]
      ],
      [
        ['label' => 'Item', 'name' => 'id_item1', 'width' => 'col-md-2', 'type' => 'select', 'dataenum' => $dropdown_item, 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Item']],
        ['label' => 'Harga', 'name' => 'harga1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true]],
        ['label' => 'Qty', 'name' => 'qty1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Tipe Diskon', 'name' => 'diskon_tipe1',  'width' => 'col-md-1', 'type' => 'select', 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'attributes' => ['class' => 'form-control', 'placeholder' => 'Pilih Tipe Diskon'], 'value' => 'nominal'],
        ['label' => 'Diskon', 'name' => 'diskon1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Subtotal', 'name' => 'subtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Potongan Adm', 'name' => 'potongan1', 'width' => 'col-md-1',  'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Grand Total', 'name' => 'grandtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0]
      ],
      // Row 3 setelah detail penjualan
      [
        ['label' => 'Subtotal', 'label_width' => 'col-md-2', 'name' => 'subtotal_all', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Tipe Diskon', 'label_width' => 'col-md-2', 'name' => 'diskon_tipe_all', 'type' => 'radio', 'inline' => true, 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'width' => 'col-md-10', 'attributes' => ['class' => 'form-check-input'], 'value' => 'nominal'],
        ['label' => 'Diskon', 'label_width' => 'col-md-2', 'name' => 'diskon_all', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Grand Total', 'label_width' => 'col-md-2', 'name' => 'grand_total_all', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'No. Resi (Kurir)', 'label_width' => 'col-md-2', 
          'group_forms' => [
            ['name' => 'nomor_resi', 'type' => 'text', 'width' => 'col-md-7', 'attributes' => ['class' => 'form-control']],
            ['name' => 'id_kurir', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kurir,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kurir']],
          ]
        ],
      ]
    ];

    if ($this->session->has_userdata('id_toko')) $form[0][2] = ['name' => 'id_toko', 'type' => 'hidden', 'value' => $this->session->has_userdata('id_toko')];

    $this->load->view('template/index', [
      'form' => $form,
      'title' => 'Tambah Penjualan',
      'content' => 'penjualan/tambah',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function tambah_penjualan() {
    $id = $this->M_app->getLatestId('id', 'tb_penjualan');
    $kode = $this->input->post('kode');
    $id_produks = $this->input->post('id_item');
    $hargas = $this->input->post('harga');
    $quantities = $this->input->post('qty');
    $diskon_tipes = $this->input->post('diskon_tipe');
    $diskons = $this->input->post('diskon');
    $subtotals = $this->input->post('subtotal');
    $potongans = $this->input->post('potongan');
    $grandtotals = $this->input->post('grandtotal');
    $detail_jual = [];

    $toko = $this->M_app->getDataByParameter('id', $this->input->post('id_toko'), 'tb_toko')->row_array();

    $data = [
      'id' => $id,
      'kode' => $kode,
      'id_toko' => $this->input->post('id_toko'),
      'nama_toko' => $toko['nama_toko'],
      'tanggal' => date('Y-m-d H:i:s', strtotime($this->input->post('tanggal'))),
      'subtotal' => $this->input->post('subtotal_all'),
      'diskon_tipe' => $this->input->post('diskon_tipe_all'),
      'diskon' => $this->input->post('diskon_all'),
      'grand_total' => $this->input->post('grand_total_all'),
      'sumber_transaksi' => $this->input->post('sumber_transaksi'),
      'status_pembayaran' => 0,
      'nomor_resi' => $this->input->post('nomor_resi'),
      'id_kurir' => $this->input->post('id_kurir'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    if ($this->input->post('is_new_pelanggan')) {
      $data['nama_pelanggan'] = $this->input->post('nama_pelanggan');
      $data['alamat_pelanggan'] = $this->input->post('alamat_lengkap');
      // Insert data pelanggan
      $id_pelanggan = $this->M_app->getLatestId('id', 'tb_pelanggan');
      $data_pelanggan = [
        'id' => $id_pelanggan,
        'kode' => 'PLGN/' . str_pad($id_pelanggan, 5, '0', STR_PAD_LEFT),
        'nama' => $this->input->post('nama_pelanggan'),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $this->session->userdata('name')
      ];
      $this->M_pelanggan->insert_pelanggan($data_pelanggan);
      $data['id_pelanggan'] = $id_pelanggan;
      // Insert data alamat pelanggan
      $id_alamat_pelanggan = $this->M_app->getLatestId('id', 'tb_pelanggan_alamat');
      $data_alamat = [
        'id' => $id_alamat_pelanggan,
        'kode' => 'ALMT/' . str_pad($id_alamat_pelanggan, 5, '0', STR_PAD_LEFT),
        'id_pelanggan' => $id_pelanggan,
        'alamat_lengkap' => $this->input->post('alamat_lengkap'),
        'id_provinsi' => $this->input->post('id_provinsi'),
        'id_kota' => $this->input->post('id_kota'),
        'id_kecamatan' => $this->input->post('id_kecamatan'),
        'id_kelurahan' => $this->input->post('id_kelurahan'),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $this->session->userdata('name')
      ];
      $this->M_pelanggan->insert_pelanggan_alamat($data_alamat);
      $data['id_alamat_pelanggan'] = $id_alamat_pelanggan;
    } else if ($this->input->post('is_new_alamat')) {
      $pelanggan = $this->M_app->getDataByParameter('id', $this->input->post('id_pelanggan'), 'tb_pelanggan')->row_array();
      $data['id_pelanggan'] = $this->input->post('id_pelanggan');
      $data['nama_pelanggan'] = $pelanggan['nama'];
      $data['alamat_pelanggan'] = $this->input->post('alamat_lengkap');
      // Insert data alamat pelanggan
      $id_alamat_pelanggan = $this->M_app->getLatestId('id', 'tb_pelanggan_alamat');
      $data_alamat = [
        'id' => $id_alamat_pelanggan,
        'kode' => 'ALMT/' . str_pad($id_alamat_pelanggan, 5, '0', STR_PAD_LEFT),
        'id_pelanggan' => $this->input->post('id_pelanggan'),
        'alamat_lengkap' => $this->input->post('alamat_lengkap'),
        'id_provinsi' => $this->input->post('id_provinsi'),
        'id_kota' => $this->input->post('id_kota'),
        'id_kecamatan' => $this->input->post('id_kecamatan'),
        'id_kelurahan' => $this->input->post('id_kelurahan'),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $this->session->userdata('name')
      ];
      $this->M_pelanggan->insert_pelanggan_alamat($data_alamat);
      $data['id_alamat_pelanggan'] = $id_alamat_pelanggan;
    } else {
      $pelanggan = $this->M_app->getDataByParameter('id', $this->input->post('id_pelanggan'), 'tb_pelanggan')->row_array();
      $alamat_pl = $this->M_app->getDataByParameter('id', $this->input->post('id_alamat_pelanggan'), 'tb_pelanggan_alamat')->row_array();
      $data['id_pelanggan'] = $this->input->post('id_pelanggan');
      $data['nama_pelanggan'] = $pelanggan['nama'];
      $data['id_alamat_pelanggan'] = $this->input->post('id_alamat_pelanggan');
      $data['alamat_pelanggan'] = $alamat_pl['alamat_lengkap'];
    }
    for ($i = 0; $i < count($id_produks); $i++) {
      $item = $this->M_app->getDataByParameter('id', $id_produks[$i], 'tb_item')->row_array();
      $detail_jual[] = [
        'id' => $this->M_app->getLatestId('id', 'tb_penjualan_detail') + $i,
        'id_penjualan' => $id,
        'kode_penjualan' => $kode,
        'id_item' => $id_produks[$i],
        'nama' => $item['keterangan'],
        'harga' => $hargas[$i],
        'qty' => $quantities[$i],
        'diskon' => $diskons[$i],
        'diskon_tipe' => $diskon_tipes[$i],
        'subtotal' => $subtotals[$i],
        'potongan_admin' => $potongans[$i],
        'grand_total' => $grandtotals[$i],
        'created_at' => date('Y-m-d H:i:s')
      ];
    }
    $proc = $this->M_penjualan->insert_penjualan($data, $detail_jual);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('penjualan/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan.');
    }
    redirect('penjualan');
  }

  public function edit($id) {
    $penjualan = $this->M_penjualan->get_penjualan($id)->row_array();
    $detail_penjualan = $this->M_penjualan->get_detail_penjualan($id);
    $data_item = $this->M_item->get_item('', true)->result_array();
    $dropdown_item = [];
    foreach ($data_item as $di) $dropdown_item[$di['id']] = $di['keterangan'];
    foreach ($detail_penjualan as $dpi) {
      if (in_array($dpi['nama'], $dropdown_item)) unset($dropdown_item[$dpi['id_item']]);
    }

    $form = [
      // Row 1 sebelum detail penjualan
      [
        ['label' => 'Kode*', 'label_width' => 'col-md-2', 'name' => 'kode', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $penjualan['kode']],
        ['label' => 'Tanggal', 'label_width' => 'col-md-2', 'name' => 'tanggal', 'type' => 'datetime-local', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control'], 'value' => date('Y-m-d\TH:i:s', strtotime($penjualan['tanggal']))],
        ['label' => 'Toko', 'label_width' => 'col-md-2', 'name' => 'id_toko', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_toko,nama_toko', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Toko'], 'value' => $penjualan['id_toko']],
        ['label' => 'Pelanggan', 'label_width' => 'col-md-2', 'name' => 'id_pelanggan', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'tb_pelanggan,nama', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Pelanggan'], 'value' => $penjualan['id_pelanggan']],
        ['label' => 'Alamat Pengiriman', 'label_width' => 'col-md-2', 'name' => 'id_alamat_pelanggan', 'type' => 'select', 'width' => 'col-md-10', 'datatable' => 'view_alamat_lengkap,alamat_lengkap', 'parent_select' => 'id_pelanggan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Alamat Pelanggan'], 'value' => $penjualan['id_alamat_pelanggan']],
        ['label' => 'Sumber Transaksi', 'label_width' => 'col-md-2', 'name' => 'sumber_transaksi', 'type' => 'select', 'width' => 'col-md-10', 'dataenum' => ['Tokopedia','Shopee','WhatsApp','Website'], 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Sumber Transaksi'], 'value' => $penjualan['sumber_transaksi']]
      ],
      [
        ['label' => 'Item', 'name' => 'id_item1', 'width' => 'col-md-2', 'type' => 'select', 'dataenum' => $dropdown_item, 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Item']],
        ['label' => 'Harga', 'name' => 'harga1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true]],
        ['label' => 'Qty', 'name' => 'qty1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Tipe Diskon', 'name' => 'diskon_tipe1',  'width' => 'col-md-1', 'type' => 'select', 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'attributes' => ['class' => 'form-control', 'placeholder' => 'Pilih Tipe Diskon'], 'value' => 'nominal'],
        ['label' => 'Diskon', 'name' => 'diskon1',  'width' => 'col-md-1', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Subtotal', 'name' => 'subtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0],
        ['label' => 'Potongan Adm', 'name' => 'potongan1', 'width' => 'col-md-1',  'type' => 'number', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => 0],
        ['label' => 'Grand Total', 'name' => 'grandtotal1', 'width' => 'col-md-2', 'type' => 'number', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => 0]
      ],
      // Row 3 setelah detail penjualan
      [
        ['label' => 'Subtotal', 'label_width' => 'col-md-2', 'name' => 'subtotal_all', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $penjualan['subtotal']],
        ['label' => 'Tipe Diskon', 'label_width' => 'col-md-2', 'name' => 'diskon_tipe_all', 'type' => 'radio', 'inline' => true, 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'width' => 'col-md-10', 'attributes' => ['class' => 'form-check-input'], 'value' => $penjualan['diskon_tipe']],
        ['label' => 'Diskon', 'label_width' => 'col-md-2', 'name' => 'diskon_all', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'min' => 0], 'value' => $penjualan['diskon']],
        ['label' => 'Grand Total', 'label_width' => 'col-md-2', 'name' => 'grand_total_all', 'type' => 'number', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'readonly' => true], 'value' => $penjualan['grand_total']],
        ['label' => 'No. Resi (Kurir)', 'label_width' => 'col-md-2', 
          'group_forms' => [
            ['name' => 'nomor_resi', 'type' => 'text', 'width' => 'col-md-7', 'attributes' => ['class' => 'form-control'], 'value' => $penjualan['nomor_resi']],
            ['name' => 'id_kurir', 'type' => 'select', 'width' => 'col-md-3', 'datatable' => 'tb_kurir,keterangan', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Kurir'], 'value' => $penjualan['id_kurir']],
          ]
        ],
      ]
    ];

    $form_detail = []; $i = 1;
    foreach ($detail_penjualan as $d) {
      $form_detail[] = [
        ['name' => 'id_item[]', 'type' => 'hidden', 'attributes' => ['id' => 'id_produk-' . $i], 'value' => $d['id_item']],
        ['name' => 'nama_item[]', 'type' => 'hidden', 'attributes' => ['id' => 'nama_produk-' . $i], 'value' => $d['nama']],
        ['name' => 'harga[]', 'type' => 'hidden', 'attributes' => ['id' => 'harga-' . $i, 'class' => 'harga'], 'value' => $d['harga']],
        ['name' => 'qty[]', 'type' => 'number', 'attributes' => ['id' => 'kuantitas-' . $i, 'class' => 'form-control kuantitas', 'min' => 1], 'value' => $d['qty']],
        ['name' => 'diskon_tipe[]', 'type' => 'select', 'dataenum' => ['nominal' => 'Nominal', 'persen' => 'Persen'], 'attributes' => ['class' => 'form-control diskon_tipe', 'id' => 'diskontipe-' . $i], 'value' => $d['diskon_tipe']],
        ['name' => 'diskon[]', 'type' => 'number', 'attributes' => ['id' => 'diskon-' . $i, 'class' => 'form-control diskon', 'min' => 0, 'max' => ($d['diskon_tipe'] == 'persen' ? '100' : $d['subtotal'])], 'value' => $d['diskon']],
        ['name' => 'subtotal[]', 'type' => 'hidden', 'attributes' => ['id' => 'subtotal-' . $i, 'class' => 'subtotal'], 'value' => $d['subtotal']],
        ['name' => 'potongan[]', 'type' => 'number', 'attributes' => ['id' => 'potongan-' . $i, 'class' => 'form-control potongan'], 'value' => $d['potongan_admin']],
        ['name' => 'grandtotal[]', 'type' => 'hidden', 'attributes' => ['id' => 'grandtotal-' . $i, 'class' => 'grandtotal'], 'value' => $d['grand_total']],
      ];
      $i++;
    }

    if ($this->session->has_userdata('id_toko')) $form[0][2] = ['name' => 'id_toko', 'type' => 'hidden', 'value' => $this->session->has_userdata('id_toko')];

    $this->load->view('template/index', [
      'form' => $form,
      'title' => 'Edit Penjualan',
      'content' => 'penjualan/edit',
      'detail_penjualan' => $detail_penjualan,
      'menu' => $this->menu,
      'access' => $this->access,
      'form_detail' => $form_detail
    ]);
  }

  public function edit_penjualan($id) {
    $kode = $this->input->post('kode');
    $id_produks = $this->input->post('id_item');
    $hargas = $this->input->post('harga');
    $quantities = $this->input->post('qty');
    $diskon_tipes = $this->input->post('diskon_tipe');
    $diskons = $this->input->post('diskon');
    $subtotals = $this->input->post('subtotal');
    $potongans = $this->input->post('potongan');
    $grandtotals = $this->input->post('grandtotal');
    $detail_jual = [];

    $toko = $this->M_app->getDataByParameter('id', $this->input->post('id_toko'), 'tb_toko')->row_array();
    $pelanggan = $this->M_app->getDataByParameter('id', $this->input->post('id_pelanggan'), 'tb_pelanggan')->row_array();
    $alamat_pl = $this->M_app->getDataByParameter('id', $this->input->post('id_alamat_pelanggan'), 'tb_pelanggan_alamat')->row_array();

    $data = [
      'kode' => $kode,
      'id_toko' => $this->input->post('id_toko'),
      'nama_toko' => $toko['nama_toko'],
      'tanggal' => date('Y-m-d H:i:s', strtotime($this->input->post('tanggal'))),
      'id_pelanggan' => $this->input->post('id_pelanggan'),
      'nama_pelanggan' => $pelanggan['nama'],
      'id_alamat_pelanggan' => $this->input->post('id_alamat_pelanggan'),
      'alamat_pelanggan' => $alamat_pl['alamat_lengkap'],
      'subtotal' => $this->input->post('subtotal_all'),
      'diskon_tipe' => $this->input->post('diskon_tipe_all'),
      'diskon' => $this->input->post('diskon_all'),
      'grand_total' => $this->input->post('grand_total_all'),
      'sumber_transaksi' => $this->input->post('sumber_transaksi'),
      'nomor_resi' => $this->input->post('nomor_resi'),
      'id_kurir' => $this->input->post('id_kurir'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    for ($i = 0; $i < count($id_produks); $i++) {
      $item = $this->M_app->getDataByParameter('id', $id_produks[$i], 'tb_item')->row_array();
      $detail_jual[] = [
        'id' => $this->M_app->getLatestId('id', 'tb_penjualan_detail') + $i,
        'id_penjualan' => $id,
        'kode_penjualan' => $kode,
        'id_item' => $id_produks[$i],
        'nama' => $item['keterangan'],
        'harga' => $hargas[$i],
        'qty' => $quantities[$i],
        'diskon' => $diskons[$i],
        'diskon_tipe' => $diskon_tipes[$i],
        'subtotal' => $subtotals[$i],
        'potongan_admin' => $potongans[$i],
        'grand_total' => $grandtotals[$i],
        'created_at' => date('Y-m-d H:i:s')
      ];
    }
    $proc = $this->M_penjualan->update_penjualan($id, $data, $detail_jual);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('penjualan');
  }

  public function delete_penjualan($id) {
    $proc = $this->M_penjualan->delete_penjualan($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan.');
    }
    redirect('penjualan');
  }

  public function get_penjualan($id) {
    $pembelian = $this->M_penjualan->get_penjualan($id)->row_array();
    $pembelian['detail'] = $this->M_penjualan->get_detail_penjualan($id);
    echo json_encode($pembelian);
  }

  public function set_sudah_bayar($id) {
    $data = [
      'status_pembayaran' => 1,
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_penjualan->update_penjualan_only($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan.');
    }
    redirect('penjualan');
  }

  public function get_penjualan_all(){
    $postData = $this->input->post();
    $data = $this->M_penjualan->get_transaksi_dua($postData, $this->access);
    echo json_encode($data);
  }
}
/* End of file Penjualan.php */
?>