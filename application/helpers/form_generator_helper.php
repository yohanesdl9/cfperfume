<?php


/*
  Generating HTML form scripts with standard Bootstrap and CodeIgniter form helper
  Params :
    $forms : associative array to specify generated form input type, class, and attributes
    $is_horizontal (default = true) : if true, form will generated as horizontal form (which all labels and input fields are left-aligned in a single row), otherwise it will generated as stacked form,

  $form = [
    'name' : input name,
    'type' : input type (text, number, date, time, datetime, email, password, hidden, checkbox, radio, select, upload),
    'width' : if $is_horizontal is true, this index will specify input width in Bootstrap (col-sm-x, col-md-x, dll. with x is from 1-12),
    'value' : input value if exists,
    'attributes' : additional input attributes specified for this form input (like class, id, placeholder, etc.),
    'dataenum' : (for select, radio and checkbox only) display option specified by user, can be a sequential array (array with index 0, 1, 2, etc.) or associative array.
    If the option is sequential array, the option value and option label is the array values (by index), otherwise the option value is the array keys, and the option label is the array values.
    'datatable' : (for select, radio and checkbox only) to display option from database. First value is a table name, and the second value is a field wich you want to show as option label. 
    Example : 'tb_general,keterangan' will get data from tb_general, and showing field keterangan as option label, option value always using field 'id'.
    'datatable_where' : (for select, radio and checkbox only) to make a condition while querying for 'datatable' options, like in SQL. Example : 'id = 2'
  ];
*/

function create_form($action, $forms, $is_simpan_tambah_lagi = false, $is_horizontal = true, $is_multipart = false) {
  $form = '';
  $form .= $is_multipart ? form_open_multipart($action) : form_open($action);
  foreach ($forms as $f) {
    // if (isset($f['type']) && $f['type'] == 'upload' && isset($f['value'])) {
    //   $form .= '<div class="form-group row">';
    //   $form .= '<label class="' . $f['label_width'] . ' col-form-label">' . $f['label'] . '</label>';
    //   $form .= '<div class="' . $f['width'] . '">';
    //   $form .= '<img src="' . base_url($f['value']) . '" width="150"><br>';
    //   $form .= '<a href="#" class="btn btn-danger mt-1" onclick="hapusGambar(\'' . $f['url_remove_picture'] . '\')"><i class="fas fa-trash"></i> Hapus Gambar</a></div></div>';
    // } else {
      $form .= generate($f, $is_horizontal);
    // }
  }
  if ($is_simpan_tambah_lagi) $form .= form_submit('submit', 'Simpan & Tambah Lagi', ['class' => 'btn btn-success']);
  $form .= form_submit('submit', 'Simpan', ['class' => 'btn btn-success float-md-right']);
  $form .= form_close();
  return $form;
}

function generate($forms, $is_horizontal = true) {
  $form = '';
  if (isset($forms['group_forms'])) {
    if ($is_horizontal) {
      $form .= '<div class="form-group row">' . form_label($forms['label'], '', ['class' => $forms['label_width'] . ' col-form-label']);
    } else {
      $form .= '<div class="form-group">' . form_label($forms['label']);
    }
    if ($is_horizontal) {
      foreach ($forms['group_forms'] as $gf) {
        $form .= '<div class="' . $gf['width'] . '">';
        $form .= generate_form($gf);
        $form .= '</div>';
      }
    } else {
      $form .= '<div class="input-group">';
      foreach ($forms['group_forms'] as $gf) $form .= generate_form($gf);
      $form .= '</div>';
    }
    $form .= '</div>';
  } else {
    if ($is_horizontal) {
      if ($forms['type'] != 'hidden')  $form .= '<div class="form-group row">' . form_label($forms['label'], '', ['class' => $forms['label_width'] . ' col-form-label']);
    } else {
      if ($forms['type'] != 'hidden') $form .= '<div class="form-group">' . form_label($forms['label']);
    }
    if ($is_horizontal) $form .= $forms['type'] != 'hidden' ? '<div class="' . $forms['width'] . '">' : '';
    $form .= generate_form($forms);
    if ($is_horizontal) $form .= $forms['type'] != 'hidden' ? '</div>' : '';
    $form .= $forms['type'] != 'hidden' ? '</div>' : '';
  }
  
  return $form;
}

function generate_form($forms) {
  $CI =& get_instance();
  $form = '';
  switch ($forms['type']) {
    case 'text':
      $form .= form_input($forms['name'], isset($forms['value']) ? $forms['value'] : null, isset($forms['attributes']) ? $forms['attributes'] : null);
      break;
    case 'textarea':
      $form .= form_textarea($forms['name'], isset($forms['value']) ? $forms['value'] : null, isset($forms['attributes']) ? $forms['attributes'] : null);
      break;
    case 'number':
    case 'date':
    case 'time':
    case 'datetime-local':
    case 'email':
      $form .= '<input type="' . $forms['type'] . '" name="' . $forms['name'] . '"';
      if (isset($forms['attributes'])) foreach ($forms['attributes'] as $key => $value) $form .= (' ' . $key . '="' . $value . '"');
      if (isset($forms['value'])) $form .= ' value="' . $forms['value'] . '"';
      $form .= '>';
      break;
    case 'password':
      $form .= form_password($forms['name'], '', isset($forms['attributes']) ? $forms['attributes'] : null);
      break;
    case 'hidden':
      $form .= '<input type="hidden" name="' . $forms['name'] . '"';
      if (isset($forms['attributes'])) foreach ($forms['attributes'] as $key => $value) $form .= (' ' . $key . '="' . $value . '"');
      if (isset($forms['value'])) $form .= ' value="' . $forms['value'] . '"';
      $form .= '>';
      break;
    case 'radio':
    case 'checkbox';
      $checkboxes_value = [];
      if ($forms['type'] == 'checkbox') {
        if (isset($forms['value'])) foreach ($data as $d) $checkboxes_value[] = $d['id'];
      }
      if (isset($forms['dataenum'])) {
        if (is_associative_array($forms['dataenum'])) {
          foreach ($forms['dataenum'] as $key => $value) {
            $form .= '<div class="form-check' . (isset($forms['inline']) && $forms['inline'] == true ? '-inline' : '') . ' mt-2">';
            if ($forms['type'] == 'radio') {
              $form .= form_radio($forms['name'], $key, isset($forms['value']) ? $forms['value'] == $key : NULL, isset($forms['attributes']) ? $forms['attributes'] : null);
            } else {
              $form .= form_checkbox($forms['name'], $key, in_array($key, $checkboxes_value), isset($forms['attributes']) ? $forms['attributes'] : null);
            }
            $form .= form_label($value, '', ['class' => 'form-check-label']);
            $form .= '</div>';
          }
        } else {
          for ($i = 0; $i < count($forms['dataenum']); $i++){
            $form .= '<div class="form-check' . ($forms['inline'] ? '-inline' : '') . ' mt-2">';
            if ($forms['type'] == 'radio') {
              $form .= form_radio($forms['name'], $forms['dataenum'][$i], $forms['value'] == $forms['dataenum'][$i], isset($forms['attributes']) ? $forms['attributes'] : null);
            } else {
              $form .= form_checkbox($forms['name'], $forms['dataenum'][$i], in_array($forms['dataenum'][$i], $checkboxes_value), isset($forms['attributes']) ? $forms['attributes'] : null);
            }
            $form .= form_label($forms['dataenum'][$i], '', ['class' => 'form-check-label']);
            $form .= '</div>';
          }
        }
      } else if (isset($forms['datatable'])) {
        $data = [];
        $datatable = explode(',', $forms['datatable']);
        if (isset($forms['datatable_where'])) {
          $wheres = explode(',', $forms['datatable_where']);
          $where = [];
          for ($i = 0; $i < count($wheres); $i++){
            $condition = explode(' ', $wheres[$i]);
            $where[$condition[0] . ($condition[1] == '=' ? '' : ' ' . $condition[1])] = $condition[2];
          }
          $database = getDataByParameters($where, $datatable[0])->result_array();
        } else {
          $CI->db->where('deleted_at', NULL);
          $database = $CI->db->get($datatable[0])->result_array();
        }
        foreach ($database as $d) {
          $form .= '<div class="form-check' . ($forms['inline'] ? '-inline' : '') . ' mt-2">';
          if ($forms['type'] == 'radio') {
            $form .= form_radio($forms['name'], $d['id'], isset($forms['value']) && $forms['value'] == $d['id'], isset($forms['attributes']) ? $forms['attributes'] : null);
          } else {
            $form .= form_checkbox($forms['name'], $d['id'], in_array($d['id'], $checkboxes_value), isset($forms['attributes']) ? $forms['attributes'] : null);
          }
          $form .= form_label($d[$datatable[1]], '', ['class' => 'form-check-label']);
          $form .= '</div>';
        }
      }
      break;
    case 'select':
      $data = [];
      if (isset($forms['attributes']['placeholder'])) $data[''] = $forms['attributes']['placeholder'];
      if (isset($forms['dataenum'])) {
        if (is_associative_array($forms['dataenum'])) {
          foreach ($forms['dataenum'] as $key => $value) $data[$key] = $value;
        } else {
          for ($i = 0; $i < count($forms['dataenum']); $i++) $data[$forms['dataenum'][$i]] = $forms['dataenum'][$i];
        }
      } else if (isset($forms['datatable'])) {
        $datatable = explode(',', $forms['datatable']);
        if (isset($forms['datatable_where'])) {
          $wheres = explode(',', $forms['datatable_where']);
          $where = [];
          for ($i = 0; $i < count($wheres); $i++){
            $condition = explode(' ', $wheres[$i]);
            $where[$condition[0] . ($condition[1] == '=' ? '' : ' ' . $condition[1])] = $condition[2];
          }
          $database = getDataByParameters($where, $datatable[0])->result_array();
        } else {
          $CI->db->where('deleted_at', NULL);
          $database = $CI->db->get($datatable[0])->result_array();
        }
        if (!isset($forms['parent_select']) || isset($forms['value'])) {
          foreach ($database as $d) $data[$d['id']] = $d[$datatable[1]];
        }
      } else if (isset($forms['dataoptions'])) {
        $data = $forms['dataopt'];
      }
      $form .= my_form_dropdown($forms['name'], $data, isset($forms['value']) ? $forms['value'] : NULL, NULL, NULL, isset($forms['attributes']) ? $forms['attributes'] : null);
      if(isset($forms['parent_select'])) $form .= javascript_chained_dropdown($forms);
      break;
    case 'upload':
      if (isset($forms['value'])) {
        $form .= '<img src="' . base_url($forms['value']) . '" width="150"><br>';
        $form .= '<a href="#" class="btn btn-danger mt-1" onclick="hapusGambar(\'' . $forms['url_remove_picture'] . '\')"><i class="fas fa-trash"></i> Hapus Gambar</a>';
        $form .= javascript_remove_picture($forms['url_remove_picture']);
      } else {
        $form .= form_upload($forms['name'], '', isset($forms['attributes']) ? $forms['attributes'] : null);
      }
      break;
  }
  return $form;
}

function is_associative_array($arr1) {
  return array_keys($arr1) !== range(0, count($arr1) - 1);
}

function my_form_dropdown($data = '', $options = array(), $selected = array(), $disabled = array(), $hidden= array(), $extra = ''){
    $defaults = array();
    if (is_array($data)){
      if (isset($data['selected'])){
        $selected = $data['selected'];
        unset($data['selected']); // select tags don't have a selected attribute
      }
      if (isset($data['options'])){
        $options = $data['options'];
        unset($data['options']); // select tags don't use an options attribute
      }
      if (isset($data['disabled'])){
        $disabled = $data['disabled'];
        unset($data['disabled']); // select tags don't use an disabled attribute
      }
      if (isset($data['hidden'])){
        $hidden = $data['hidden'];
        unset($data['hidden']); // select tags don't use an hidden attribute
      }
    } else {
      $defaults = array('name' => $data);
    }

    is_array($selected) OR $selected = array($selected);
    is_array($options) OR $options = array($options);
    is_array($disabled) OR $disabled = array($disabled);
    is_array($hidden) OR $hidden = array($hidden);

    // If no selected state was submitted we will attempt to set it automatically
    if (empty($selected)){
      if (is_array($data)){
        if (isset($data['name'], $_POST[$data['name']])){
          $selected = array($_POST[$data['name']]);
        }
      }
      else if (isset($_POST[$data])){
        $selected = array($_POST[$data]);
      }
    }

    $extra = _attributes_to_string($extra);
    $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';
    $form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

    foreach ($options as $key => $val){
      $key = (string) $key;
      if (is_array($val)){
        if (empty($val)){
            continue;
        }
        $form .= '<optgroup label="'.$key."\">\n";
        foreach ($val as $optgroup_key => $optgroup_val){
          $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
          $dis = in_array($optgroup_key, $disabled) ? ' disabled' : '';
          $hid = in_array($optgroup_key, $hidden) ? ' hidden' : '';
          $form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.$dis.$hid.'>'.(string) $optgroup_val."</option>\n";
        }
        $form .= "</optgroup>\n";
      } else {
        $form .= '<option value="'.html_escape($key).'"'
            .(stripos($extra, 'selectpicker') == TRUE ? ' data-icon="' . $key . '"' : '')
            .(in_array($key, $selected) ? ' selected="selected"' : ''). (in_array($key, $disabled) ? ' disabled': ''). (in_array($key, $hidden) ? ' hidden': '').'>'
            .(string) $val."</option>\n";
      }
    }

    return $form."</select>\n";
  } 

function getDataByParameters($parameters, $tables, $order = NULL, $order_mode = "ASC"){
  $CI =& get_instance();
  $CI->db->where($parameters);
  $CI->db->where('deleted_at', NULL);
  if($order != NULL) $CI->db->order_by($order, $order_mode);
  return $CI->db->get($tables);
}

function javascript_remove_picture($forms) {
  $form = "<script>function hapusGambar(id){";
  $form .= "Swal.fire({";
  $form .= "title: 'Apakah Anda yakin?',";
  $form .= "text: 'Gambar akan terhapus!',";
  $form .= "icon: 'warning',";
  $form .= "showCancelButton: true,";
  $form .= "confirmButtonText: 'Ya',";
  $form .= "cancelButtonText: 'Tidak'";
  $form .= "}).then((result) => {";
  $form .= "if (result.value) { window.location.href = '" . base_url($forms) . "'; } }); }</script>";
  return $form;
}

function javascript_chained_dropdown($forms) {
  $form = '<script>';
  $form .= '$(\'select[name="' . $forms['parent_select'] . '"]\').change(function() {';
  $form .= '$.ajax({';
  $form .= 'url: \'' . base_url('general/nested_dropdown/' . explode(',', $forms['datatable'])[0] . '/' . $forms['parent_select'] . '/') .'\' + $(this).val(),';
  $form .= 'type: \'GET\',';
  $form .= 'dataType: \'JSON\',';
  $form .= 'async: false,';
  $form .= 'cache: false,';
  $form .= 'success: function(data) {';
  $form .= '$(\'select[name="' . $forms['name'] . '"]\').html(\'<option value selected="selected" disabled hidden>' . $forms['attributes']['placeholder'] . '</option>\');';
  $form .= '$.each(data, function(key, value){';
  $form .= '$(\'select[name="' . $forms['name'] . '"]\').append(\'<option value="\' + value.id + \'">\' + value.' . explode(',', $forms['datatable'])[1] . '+\'</option>\');';
  $form .= '});},';
  $form .= 'failure: function() {';
  $form .= 'Swal.fire({';
  $form .= 'icon: \'error\',';
  $form .= 'title: \'Oops...\',';
  $form .= 'text: \'Terjadi kesalahan. Gagal mengambil data.\'';
  $form .= '}); } });';
  $form .= '});';
  if (isset($forms['value'])) {
    $form .= '$(document).ready(function(){';
    $form .= '$.ajax({';
    $form .= 'url: \'' . base_url('general/nested_dropdown/' . explode(',', $forms['datatable'])[0] . '/' . $forms['parent_select'] . '/') .'\' + $(\'select[name="' . $forms['parent_select'] . '"]\').val(),';
    $form .= 'type: \'GET\',';
    $form .= 'dataType: \'JSON\',';
    $form .= 'async: false,';
    $form .= 'cache: false,';
    $form .= 'success: function(data) {';
    $form .= '$(\'select[name="' . $forms['name'] . '"]\').html(\'<option value selected="selected" disabled hidden>' . $forms['attributes']['placeholder'] . '</option>\');';
    $form .= '$.each(data, function(key, value){';
    $form .= '$(\'select[name="' . $forms['name'] . '"]\').append(\'<option value="\' + value.id + \'">\' + value.' . explode(',', $forms['datatable'])[1] . '+\'</option>\');';
    $form .= '});},';
    $form .= 'failure: function() {';
    $form .= 'Swal.fire({';
    $form .= 'icon: \'error\',';
    $form .= 'title: \'Oops...\',';
    $form .= 'text: \'Terjadi kesalahan. Gagal mengambil data.\'';
    $form .= '}); } });';
    $form .= '$(\'select[name="' . $forms['name'] . '"]\').val("'. $forms['value'] .'")';
    $form .= '});';
  }
  $form .= '</script>';
  return $form;
} 

?>