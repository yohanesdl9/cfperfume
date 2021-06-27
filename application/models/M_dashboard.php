<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {

  public function __construct(){
    parent::__construct();
  }
  
  public function get_subtotal_freq($table, $field, $mode = 'today') {
    $this->db->select('IFNULL(SUM(' . $field . '), 0) AS total');
    if ($mode == 'today') {
      $this->db->where('DATE(tanggal)', date('Y-m-d'));
    } else if ($mode == 'month') {
      $this->db->where('MONTH(tanggal)', date('m'))->where('YEAR(tanggal)', date('Y'));
    }
    return $this->db->get($table)->row_array()['total'];
  }

  public function get_subtotal_freq_month_year($table, $field, $month, $year) {
    $this->db->select('IFNULL(SUM(' . $field . '), 0) AS total');
    $this->db->where('MONTH(tanggal)', $month)->where('YEAR(tanggal)', $year);
    return $this->db->get($table)->row_array()['total'];
  }

  public function grafikPenjualanSetahun() {
    $query = "SELECT months.MONTH,
      CASE 
        WHEN months.MONTH = 1 THEN 'Januari'
        WHEN months.MONTH = 2 THEN 'Februari'
        WHEN months.MONTH = 3 THEN 'Maret'
        WHEN months.MONTH = 4 THEN 'April'
        WHEN months.MONTH = 5 THEN 'Mei'
        WHEN months.MONTH = 6 THEN 'Juni'
        WHEN months.MONTH = 7 THEN 'Juli'
        WHEN months.MONTH = 8 THEN 'Agustus'
        WHEN months.MONTH = 9 THEN 'September'
        WHEN months.MONTH = 10 THEN 'Oktober' 
        WHEN months.MONTH = 11 THEN 'November' 
        WHEN months.MONTH = 12 THEN 'Desember' END as label, 
      IFNULL(main.total, 0) AS value 
      FROM 
      (SELECT MONTH(tanggal) AS bulan, YEAR(tanggal) AS tahun, IFNULL(SUM(grand_total), 0) AS total 
      FROM tb_penjualan 
      WHERE YEAR(tanggal) = YEAR(NOW()) GROUP BY MONTH(tanggal), YEAR(tanggal)) AS main 
      RIGHT JOIN (
      SELECT 1 AS MONTH UNION 
      SELECT 2 AS MONTH UNION 
      SELECT 3 AS MONTH UNION 
      SELECT 4 AS MONTH UNION 
      SELECT 5 AS MONTH UNION 
      SELECT 6 AS MONTH UNION 
      SELECT 7 AS MONTH UNION 
      SELECT 8 AS MONTH UNION 
      SELECT 9 AS MONTH UNION 
      SELECT 10 AS MONTH UNION 
      SELECT 11 AS MONTH UNION 
      SELECT 12 AS MONTH) as months ON months.MONTH = main.bulan AND main.tahun = YEAR(NOW())
      GROUP BY label
      ORDER BY months.MONTH ASC";
    return $this->db->query($query)->result_array();
  }

}

/* End of file M_dashboard.php */
?>
 