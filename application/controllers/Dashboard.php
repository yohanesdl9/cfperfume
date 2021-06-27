<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  protected $menu;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_dashboard');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
  }

  public function index() {
    $dashboard = [
      ['label' => 'Pengeluaran Hari Ini', 'id' => 'peng_today', 'label_id' => 'label_peng_today', 'icon' => 'dripicons-cart', 'value' => nice_number($this->M_dashboard->get_subtotal_freq('tb_pengeluaran', 'grand_total'))],
      ['label' => 'Pengeluaran Bulan Ini', 'id' => 'peng_month', 'label_id' => 'label_peng_month', 'icon' => 'dripicons-cart', 'value' => nice_number($this->M_dashboard->get_subtotal_freq('tb_pengeluaran', 'grand_total', 'month'))],
      ['label' => 'Pembelian Hari Ini', 'id' => 'pemb_today', 'label_id' => 'label_pemb_today', 'icon' => 'dripicons-stack', 'value' => nice_number($this->M_dashboard->get_subtotal_freq('tb_pembelian', 'grand_total'))],
      ['label' => 'Pembelian Bulan Ini', 'id' => 'pemb_month', 'label_id' => 'label_pemb_month', 'icon' => 'dripicons-stack', 'value' => nice_number($this->M_dashboard->get_subtotal_freq('tb_pembelian', 'grand_total', 'month'))],
      ['label' => 'Penjualan Hari Ini', 'id' => 'penj_today', 'label_id' => 'label_pennj_today', 'icon' => 'dripicons-store', 'value' => nice_number($this->M_dashboard->get_subtotal_freq('tb_penjualan', 'grand_total'))],
      ['label' => 'Penjualan Bulan Ini', 'id' => 'penj_month', 'label_id' => 'label_penj_month', 'icon' => 'dripicons-store', 'value' => nice_number($this->M_dashboard->get_subtotal_freq('tb_penjualan', 'grand_total', 'month'))],
    ];

    $grafik = $this->M_dashboard->grafikPenjualanSetahun();
    $omset = [];
    foreach ($grafik as $g) $omset[$g['MONTH']] = $g['value'];

    $this->load->view('template/index', [
      'title' => 'Dashboard',
      'page_title' => 'Dashboard',
      'content' => 'dashboard/index',
      'menu' => $this->menu,
      'dashboard' => $dashboard,
      'omset' => $omset
    ]);
  }

  public function get_filtered_stats($month, $year){
    $data = [
      'pembelian' => nice_number($this->M_dashboard->get_subtotal_freq_month_year('tb_pembelian', 'grand_total', $month, $year)),
      'pengeluaran' => nice_number($this->M_dashboard->get_subtotal_freq_month_year('tb_pengeluaran', 'grand_total', $month, $year)),
      'penjualan' => nice_number($this->M_dashboard->get_subtotal_freq_month_year('tb_penjualan', 'grand_total', $month, $year))
    ];
    echo json_encode($data);
  }

}

/* End of file Dashboard.php */
?>