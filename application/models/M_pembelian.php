<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pembelian extends CI_Model {

  public function __construct(){
    parent::__construct();
    $this->load->model('M_item');    
  }
  
  public function get_pembelian($id_pembelian = '') {
    $this->db->select('tp.*, ts.nama_supplier');
    $this->db->join('tb_supplier AS ts', 'tp.id_supplier = ts.id');
    if ($id_pembelian != '') $this->db->where('tp.id', $id_pembelian);
    $this->db->where('tp.deleted_at', NULL);
    return $this->db->get('tb_pembelian AS tp');
  }

  public function getLatestIdPembelian() {
    $id = $this->db->select('COUNT(*) AS id')->where('DATE(tanggal)', date('Y-m-d'))->get('tb_pembelian')->row_array()['id'] + 1;
    return 'PMB/' . date('dmy') . '/' . str_pad($id, 5, '0', STR_PAD_LEFT);
  }

  public function insert_pembelian($data, $detail_beli) {
    $proc = $this->db->insert('tb_pembelian', $data) && $this->db->insert_batch('tb_pembelian_detail', $detail_beli);
    if ($proc == TRUE) {
      // Jika proses berhasil, setiap detail pembelian akan ditambahkan ke data kartu stok dan item
      for ($i = 0; $i < count($detail_beli); $i++) {
        $data_stock = [
          'id' => $this->M_app->getLatestId('id', 'tb_item_stok') + $i,
          'id_item' => $detail_beli[$i]['id_item'],
          'tanggal' => date('Y-m-d H:i:s'),
          'stok_masuk' => $detail_beli[$i]['kuantitas'],
          'stok_keluar' => 0,
          'keterangan' => 'Penambahan stok dari transaksi pembelian ' . $detail_beli[$i]['kode_pembelian'],
          'created_at' => date('Y-m-d H:i:s'),
          'created_by' => $this->session->userdata('name')
        ];
        $proc = $this->M_item->tambah_stock($detail_beli[$i]['id_item'], $data_stock);
      }
    }
    return $proc;
  }

  public function update_pembelian($id, $data, $detail_beli) {
    $current_detail = $this->get_detail_pembelian($id);
    $proc = FALSE;
    // Kembalikan angka stok ke angka semula
    foreach ($current_detail as $cur) {
      $data_stock = [
        'id' => $this->M_app->getLatestId('id', 'tb_item_stok') + $i,
        'id_item' => $cur['id_item'],
        'tanggal' => date('Y-m-d H:i:s'),
        'stok_masuk' => 0,
        'stok_keluar' => $cur['kuantitas'],
        'keterangan' => 'Penyesuaian stok dari transaksi pembelian ' . $cur['kode_pembelian'],
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $this->session->userdata('name')
      ];
      $proc = $this->M_item->tambah_stock($cur['id_item'], $data_stock);
    }
    // Sesuaikan dengan data stok yang baru
    $proc = $this->db->update('tb_pembelian', $data, ['id' => $id]) && $this->db->delete('tb_pembelian_detail', ['id_pembelian' => $id]) && $this->db->insert_batch('tb_pembelian_detail', $detail_beli);
    if ($proc == TRUE) {
      for ($i = 0; $i < count($detail_beli); $i++) {
        $data_stock = [
          'id' => $this->M_app->getLatestId('id', 'tb_item_stok') + $i,
          'id_item' => $detail_beli[$i]['id_item'],
          'tanggal' => date('Y-m-d H:i:s'),
          'stok_masuk' => $detail_beli[$i]['kuantitas'],
          'stok_keluar' => 0,
          'keterangan' => 'Penyesuaian stok dari transaksi pembelian ' . $detail_beli[$i]['kode_pembelian'],
          'created_at' => date('Y-m-d H:i:s'),
          'created_by' => $this->session->userdata('name')
        ];
        $proc = $this->M_item->tambah_stock($detail_beli[$i]['id_item'], $data_stock);
      }
    }
    return $proc;
  }

  public function delete_pembelian($id) {
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->db->update('tb_pembelian', $data, ['id' => $id]);
  }

  public function get_detail_pembelian($id_pembelian) {
    return $this->db->where('id_pembelian', $id_pembelian)->get('tb_pembelian_detail')->result_array();
  }
}

/* End of file M_pembelian.php */
?>