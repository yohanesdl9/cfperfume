<?php
function nice_number($n) {
  $n = (0+str_replace(".above", "", $n));

  if (!is_numeric($n)) return false;

  if ($n > 1000000000000) return round(($n/1000000000000), 2).' T';
  elseif ($n > 1000000000) return round(($n/1000000000), 2).' M';
  elseif ($n > 1000000) return round(($n/1000000), 2).' jt';

  return number_format($n, 0, ',', '.');
}

function dateIndo($date) {
  $bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
  ];
  $dates = explode('-', $date);
  return (int) $dates[2] . ' ' . $bulan[(int) $dates[1]] . ' ' . $dates[0];
}

function timeIndo($time) {
  return date('H:i', strtotime($time));
}

function dateTimeIndo($datetime) {
  $datetimes = explode(' ', $datetime);
  return dateIndo($datetimes[0]) . ' - ' . timeIndo($datetimes[1]);
}

function form_ubah_password() {
  $forms = [
    ['label' => 'Password Baru', 'label_width' => 'col-md-3', 'name' => 'password', 'type' => 'password', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Password Baru']],
    ['label' => 'Ulangi Password Baru', 'label_width' => 'col-md-3', 'name' => 'retype_password', 'type' => 'password', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Ulangi Password Baru']],
  ];
  return $forms;
}
?>