<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_penjualan extends CI_Model {

  public function __construct(){
    parent::__construct();
    $this->load->model('M_item');    
  }
  
  public function get_penjualan($id_penjualan = '') {
    $this->db->select('tp.*, tk.keterangan AS kurir, prov.keterangan AS prov, kota.keterangan AS kota, kec.keterangan AS kec, kel.keterangan AS kel');
    $this->db->join('tb_kurir AS tk', 'tp.id_kurir = tk.id');
    $this->db->join('tb_pelanggan_alamat AS tpa', 'tp.id_alamat_pelanggan = tpa.id');
    $this->db->join('tb_provinsi AS prov', 'tpa.id_provinsi = prov.id');
    $this->db->join('tb_kota AS kota', 'tpa.id_kota = kota.id');
    $this->db->join('tb_kecamatan AS kec', 'tpa.id_kecamatan = kec.id');
    $this->db->join('tb_kelurahan AS kel', 'tpa.id_kelurahan = kel.id');
    if ($id_penjualan != '') $this->db->where('tp.id', $id_penjualan);
    if ($this->session->userdata('id_toko')) $this->db->where('tp.id_toko', $this->session->userdata('id_toko'));
    $this->db->where('tp.deleted_at', NULL);
    return $this->db->get('tb_penjualan AS tp');
  }

  public function getLatestIdPenjualan() {
    $id = $this->db->select('COUNT(*) AS id')->where('DATE(tanggal)', date('Y-m-d'))->get('tb_penjualan')->row_array()['id'] + 1;
    return 'PENJ' . date('dmy') . '/' . str_pad($id, 5, '0', STR_PAD_LEFT);
  }

  public function insert_penjualan($data, $detail_jual) {
    $proc = $this->db->insert('tb_penjualan', $data) && $this->db->insert_batch('tb_penjualan_detail', $detail_jual);
    if ($proc == TRUE) {
      // Jika proses berhasil, setiap detail penjualan akan ditambahkan ke data kartu stok dan item
      for ($i = 0; $i < count($detail_jual); $i++) {
        $data_stock = [
          'id' => $this->M_app->getLatestId('id', 'tb_item_stok') + $i,
          'id_item' => $detail_jual[$i]['id_item'],
          'tanggal' => date('Y-m-d H:i:s'),
          'stok_masuk' => 0,
          'stok_keluar' => $detail_jual[$i]['qty'],
          'keterangan' => 'Pengurangan stok dari transaksi penjualan ' . $detail_jual[$i]['kode_penjualan'],
          'created_at' => date('Y-m-d H:i:s'),
          'created_by' => $this->session->userdata('name')
        ];
        $proc = $this->M_item->tambah_stock($detail_jual[$i]['id_item'], $data_stock);
      }
    }
    return $proc;
  }

  public function update_penjualan($id, $data, $detail_jual) {
    $current_detail = $this->get_detail_penjualan($id);
    $proc = FALSE;
    // Kembalikan angka stok ke angka semula
    foreach ($current_detail as $cur) {
      $data_revert = [
        'id' => $this->M_app->getLatestId('id', 'tb_item_stok') + $i,
        'id_item' => $cur['id_item'],
        'tanggal' => date('Y-m-d H:i:s'),
        'stok_masuk' => $cur['qty'],
        'stok_keluar' => 0,
        'keterangan' => 'Penyesuaian stok dari transaksi penjualan ' . $cur['kode_penjualan'],
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $this->session->userdata('name')
      ];
      $proc = $this->M_item->tambah_stock($cur['id_item'], $data_revert);
    }
    // Sesuaikan dengan data stok yang baru
    $proc = $this->db->update('tb_penjualan', $data, ['id' => $id]) && $this->db->delete('tb_penjualan_detail', ['id_penjualan' => $id]) && $this->db->insert_batch('tb_penjualan_detail', $detail_jual);
    if ($proc == TRUE) {
      for ($i = 0; $i < count($detail_jual); $i++) {
        $data_stock = [
          'id' => $this->M_app->getLatestId('id', 'tb_item_stok') + $i,
          'id_item' => $detail_jual[$i]['id_item'],
          'tanggal' => date('Y-m-d H:i:s'),
          'stok_masuk' => 0,
          'stok_keluar' => $detail_jual[$i]['qty'],
          'keterangan' => 'Penyesuaian stok dari transaksi penjualan ' . $detail_jual[$i]['kode_penjualan'],
          'created_at' => date('Y-m-d H:i:s'),
          'created_by' => $this->session->userdata('name')
        ];
        $proc = $this->M_item->tambah_stock($detail_jual[$i]['id_item'], $data_stock);
      }
    }
    return $proc;
  }

  public function update_penjualan_only($id, $data) {
    return $this->db->update('tb_penjualan', $data, ['id' => $id]);
  }

  public function delete_penjualan($id) {
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->db->update('tb_penjualan', $data, ['id' => $id]);
  }

  public function get_detail_penjualan($id_penjualan) {
    return $this->db->where('id_penjualan', $id_penjualan)->get('tb_penjualan_detail')->result_array();
  }

  public function get_transaksi_dua($postData = null, $access) {
    $response = array();

    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length'];
    // $columnIndex = $postData['order'][0]['column'];
    // $columnName = $postData['columns'][$columnIndex]['data'];
    // $columnSortOrder = $postData['order'][0]['dir'];
    // $searchValue = $postData['search']['value'];

    // Custom search filter
    $filter_mode = $postData['filter_waktu'];
    $daterange = $postData['daterange'];
    $bulan = $postData['bulan'];
    $tahun = $postData['tahun'];
    $date = $postData['tanggal'];
    $sumber = $postData['sumber'];

    // // Total number of records without filtering
    $this->db->select('tp.*, tk.keterangan AS kurir, prov.keterangan AS prov, kota.keterangan AS kota, kec.keterangan AS kec, kel.keterangan AS kel');
    $this->db->join('tb_kurir AS tk', 'tp.id_kurir = tk.id');
    $this->db->join('tb_pelanggan_alamat AS tpa', 'tp.id_alamat_pelanggan = tpa.id');
    $this->db->join('tb_provinsi AS prov', 'tpa.id_provinsi = prov.id');
    $this->db->join('tb_kota AS kota', 'tpa.id_kota = kota.id');
    $this->db->join('tb_kecamatan AS kec', 'tpa.id_kecamatan = kec.id');
    $this->db->join('tb_kelurahan AS kel', 'tpa.id_kelurahan = kel.id');
    $this->db->where('tp.deleted_at', NULL);
    if ($sumber != 'all') $this->db->where('tp.sumber_transaksi', $sumber);
    if ($this->session->userdata('id_toko')) $this->db->where('tp.id_toko', $this->session->userdata('id_toko'));
    $this->db->order_by('tp.tanggal', 'desc');
    $totalRecords = $this->db->get('tb_penjualan AS tp')->num_rows();

    // // Total number of records with filtering
    $this->db->select('tp.*, tk.keterangan AS kurir, prov.keterangan AS prov, kota.keterangan AS kota, kec.keterangan AS kec, kel.keterangan AS kel');
    $this->db->join('tb_kurir AS tk', 'tp.id_kurir = tk.id');
    $this->db->join('tb_pelanggan_alamat AS tpa', 'tp.id_alamat_pelanggan = tpa.id');
    $this->db->join('tb_provinsi AS prov', 'tpa.id_provinsi = prov.id');
    $this->db->join('tb_kota AS kota', 'tpa.id_kota = kota.id');
    $this->db->join('tb_kecamatan AS kec', 'tpa.id_kecamatan = kec.id');
    $this->db->join('tb_kelurahan AS kel', 'tpa.id_kelurahan = kel.id');
    $this->db->where('tp.deleted_at', NULL);
    if ($sumber != 'all') $this->db->where('tp.sumber_transaksi', $sumber);
    if ($filter_mode == 'periode') {
      $dates = explode(' - ', $daterange);
      $this->db->where('DATE(tp.tanggal) <=', $dates[1])->where('DATE(tp.tanggal) >=', $dates[0]);
    } else if ($filter_mode == 'bulan') {
      $this->db->where('MONTH(tp.tanggal)', $bulan);
      $this->db->where('YEAR(tp.tanggal)', $tahun);
    } else if ($filter_mode == 'tanggal') {
      $this->db->where('DATE(tp.tanggal)', $date);
    }
    if ($this->session->userdata('id_toko')) $this->db->where('tp.id_toko', $this->session->userdata('id_toko'));
    $this->db->order_by('tp.tanggal', 'desc');
    $totalRecordsWithFilter = $this->db->get('tb_penjualan AS tp')->num_rows();

    // // Fetch records
    $this->db->select('tp.*, tk.keterangan AS kurir, prov.keterangan AS prov, kota.keterangan AS kota, kec.keterangan AS kec, kel.keterangan AS kel');
    $this->db->join('tb_kurir AS tk', 'tp.id_kurir = tk.id');
    $this->db->join('tb_pelanggan_alamat AS tpa', 'tp.id_alamat_pelanggan = tpa.id');
    $this->db->join('tb_provinsi AS prov', 'tpa.id_provinsi = prov.id');
    $this->db->join('tb_kota AS kota', 'tpa.id_kota = kota.id');
    $this->db->join('tb_kecamatan AS kec', 'tpa.id_kecamatan = kec.id');
    $this->db->join('tb_kelurahan AS kel', 'tpa.id_kelurahan = kel.id');
    $this->db->where('tp.deleted_at', NULL);
    if ($sumber != 'all') $this->db->where('tp.sumber_transaksi', $sumber);
    if ($filter_mode == 'periode') {
      $dates = explode(' - ', $daterange);
      $this->db->where('DATE(tp.tanggal) <=', $dates[1])->where('DATE(tp.tanggal) >=', $dates[0]);
    } else if ($filter_mode == 'bulan') {
      $this->db->where('MONTH(tp.tanggal)', $bulan);
      $this->db->where('YEAR(tp.tanggal)', $tahun);
    } else if ($filter_mode == 'tanggal') {
      $this->db->where('DATE(tp.tanggal)', $date);
    }
    if ($this->session->userdata('id_toko')) $this->db->where('tp.id_toko', $this->session->userdata('id_toko'));
    $this->db->order_by('tp.tanggal', 'desc');
    $this->db->limit($rowperpage, $start);
    $record = $this->db->get('tb_penjualan AS tp')->result_array();

    $data = [];
    foreach ($record as $r) {
      $data[] = [
        'kode' => $r['kode'],
        'tanggal' => dateTimeIndo($r['tanggal']),
        'nama_toko' => $r['nama_toko'],
        'nama_pelanggan' => $r['nama_pelanggan'],
        'sumber_transaksi' => $this->generateSpan($r['sumber_transaksi']),
        'subtotal' => 'Rp ' . number_format($r['subtotal'], 0, ',', '.'),
        'grand_total' => 'Rp ' . number_format($r['grand_total'], 0, ',', '.'),
        'status_pembayaran' => ($r['status_pembayaran'] == 0 ? '<span class="badge badge-danger">BELUM BAYAR</span>' : '<span class="badge badge-success">SUDAH BAYAR</span>'),
        'aksi' => '<a href="#" data-toogle="tooltip" data-placement="top" title="Detail" onclick="detailJual(' . $r['id'] . ')"><i class="fas fa-eye"></i></a> ' .
        ($r['status_pembayaran'] == 0 ? '<a href="#" data-toogle="tooltip" data-placement="top" title="Sudah Bayar" onclick="sudahBayar(' . $r['id'] . ')"><i class="fas fa-cash-register"></i></a> ' : '') .
        ($access->is_edit == 1 ? '<a href="' . base_url('penjualan/edit/' . $r['id']) . '" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a> ' : '') .
        ($access->is_delete == 1 ? '<a href="#" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="hapusData(\'' . $r['id'] . '\')"><i class="fas fa-trash"></i></a> ' : '')
      ];
    }
    $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordsWithFilter,
      "aaData" => $data
    );
    return $response; 
  }

  public function generateSpan($sumber) {
    $span = '';
    switch($sumber) {
      case 'Tokopedia': 
        $span = '<span class="badge badge-success">' . $sumber . '</span>'; 
        break;
      case 'Website': 
        $span = '<span class="badge badge-primary">' . $sumber . '</span>'; 
        break;
      case 'Shopee': 
        $span = '<span class="badge badge-orange">' . $sumber . '</span>'; 
        break;
      case 'WhatsApp': 
        $span = '<span class="badge badge-info">' . $sumber . '</span>'; 
        break;
    }
    return $span;
  }
}

/* End of file M_penjualan.php */
?>