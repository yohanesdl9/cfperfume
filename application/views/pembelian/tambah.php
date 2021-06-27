<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="mb-2">
      <a href="<?= base_url('pembelian') ?>"><i class="fas fa-chevron-circle-left"></i> Kembali ke halaman Pembelian</a>
    </div>
    <div class="card">
      <div class="card-body">
        <form action="<?= base_url('pembelian/tambah_pembelian') ?>" method="POST">
          <?php foreach ($form[0] as $f) echo generate($f); ?>
          <div class="card">
            <div class="card-body">
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title">Form</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <?php foreach ($form[1] as $f) { ?>
                    <div class="<?= $f['width'] ?>"><?= generate($f, false); ?></div>
                    <?php } ?>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-success mt-4" id="tambah"><i class="fas fa-plus"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title">Tabel Detail</h6>
                </div>
                <div class="card-body">
                  <table class="table table-borderless table-striped table-hover">
                    <thead>
                      <tr>
                        <th class="text-center">Produk</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-center">Tipe Diskon</th>
                        <th class="text-center">Diskon</th>
                        <th class="text-center">Subtotal</th>
                        <th class="text-center">Grand Total</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="row-detail">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <?php foreach ($form[2] as $f) echo generate($f); ?>
          <input type="submit" class="btn btn-success" name="submit" value="Simpan & Tambah Lagi">
          <input type="submit" class="btn btn-success float-md-right" name="submit" value="Simpan">
        </form>
      </div>
    </div>
  </div>
</div>
<script>
var i = 1;
$('select[name="id_produk1"]').change(function(){
  var value = $(this).val();
  if (value != null) {
    $.ajax({
      url: '<?= base_url('item/get_item_detail/') ?>' + value,
      type: 'GET',
      dataType: 'JSON',
      success: function(data) {
        var tipe_diskon = $('select[name="diskon_tipe1"]').val();
        $('input[name="harga1"]').val(data.harga_beli);
        if (tipe_diskon == 'nominal') {
          $('input[name="diskon1"]').attr('max', $('input[name="subtotal1"]').val());
        } else {
          if (diskon > 100) $('input[name="diskon1"]').val(0);
          $('input[name="diskon1"]').attr('max', 100);
        }
      },
      failure: function(){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Gagal mengambil data'
        });
      }
    });
  }
});

$('input[name="kuantitas1"]').change(function(){
  performChange();
});

$('select[name="diskon_tipe1"]').change(function(){
  performChange();
});

$('input[name="diskon1"]').change(function(){
  performChange();
});

function performChange(){
  var tipe_diskon = $('select[name="diskon_tipe1"]').val();
  var harga = parseInt($('input[name="harga1"]').val());
  var kuantitas = parseInt($('input[name="kuantitas1"]').val());
  var subtotal = harga * kuantitas;
  var diskon = parseInt($('input[name="diskon1"]').val());
  if (tipe_diskon == 'nominal') {
    $('input[name="diskon1"]').attr('max', subtotal);
  } else {
    if (diskon > 100) $('input[name="diskon1"]').val(0);
    $('input[name="diskon1"]').attr('max', 100);
  }
  diskon = parseInt($('input[name="diskon1"]').val());
  var grand_total = (tipe_diskon == 'nominal') ? (subtotal - diskon) : (subtotal * ((100-diskon)/100));
  $('input[name="subtotal1"]').val(subtotal);
  $('input[name="grandtotal1"]').val(grand_total);
}

$('#tambah').click(function(e){
  e.preventDefault();
  i++;
  var id_produk = $('select[name="id_produk1"]').val();
  var nama_produk = $('select[name="id_produk1"]').find('option:selected').text();
  var harga = parseInt($('input[name="harga1"]').val());
  var kuantitas = parseInt($('input[name="kuantitas1"]').val());
  var tipe_diskon = $('select[name="diskon_tipe1"]').val();
  var diskon = $('input[name="diskon1"]').val();
  var subtotal = harga * kuantitas;
  var grand_total = (tipe_diskon == 'nominal') ? (subtotal - diskon) : (subtotal * ((100-diskon)/100));
  if (kuantitas > 0 && id_produk != null && harga != null) {
    $('#row-detail').append('<tr id="row'+i+'">'+
      '<td id="nama_produk-'+i+'"><input type="hidden" name="id_produk[]" id="id_produk-'+i+'" value="'+id_produk+'">'+nama_produk+'</td>'+
      '<td class="text-center"><input type="hidden" name="harga[]" class="harga" id="harga-'+i+'" value="'+harga+'">'+harga+'</td>'+
      '<td class="text-center"><input type="number" name="kuantitas[]" class="form-control kuantitas" min="1" id="kuantitas-'+i+'" value="'+kuantitas+'"></td>'+
      '<td><select name="diskon_tipe[]" class="form-control diskon_tipe" id="diskontipe-'+i+'">'+
        '<option value="nominal" '+ (tipe_diskon == 'nominal' ? 'selected="selected"' : '') +'>Nominal</option>'+
        '<option value="persen" '+ (tipe_diskon == 'persen' ? 'selected="selected"' : '') +'>Persen</option>'+
      '</select></td>'+
      '<td class="text-center"><input type="number" name="diskon[]" class="form-control diskon" min="0" id="diskon-'+i+'" value="'+diskon+'"></td>'+
      '<td class="text-center" id="textsub-'+i+'"><input type="hidden" name="subtotal[]" class="subtotal" id="subtotal-'+i+'" value="'+subtotal+'">'+subtotal+'</td>'+
      '<td class="text-center" id="textgrd-'+i+'"><input type="hidden" name="grandtotal[]" class="grandtotal" id="grandtotal-'+i+'" value="'+grand_total+'">'+grand_total+'</td>'+
      '<td class="text-center"><button type="button" class="btn btn-danger remove" id="'+i+'"><i class="fas fa-trash-alt"></i></button></td>'+
    +'</tr>');
    $('option[value="'+id_produk+'"]').remove();
    resetFormInput();
    $('.kuantitas').change(function(){
      var id = $(this).attr("id");
      var number = id.split('-')[1];
      hitungSubtotal(number);
    });
    $('.diskon_tipe').change(function(){
      var id = $(this).attr("id");
      var number = id.split('-')[1];
      hitungSubtotal(number);
    });
    $('.diskon').change(function(){
      var id = $(this).attr("id");
      var number = id.split('-')[1];
      hitungSubtotal(number);
    });
    hitungTotal();
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Harap masukkan data dengan benar!'
    });
  }
});

$('.kuantitas').change(function(){
  var id = $(this).attr("id");
  var number = id.split('-')[1];
  hitungSubtotal(number);
});
$('.diskon_tipe').change(function(){
  var id = $(this).attr("id");
  var number = id.split('-')[1];
  hitungSubtotal(number);
});
$('.diskon').change(function(){
  var id = $(this).attr("id");
  var number = id.split('-')[1];
  hitungSubtotal(number);
});

function resetFormInput(){
  $('select[name="id_produk1"]').val('').trigger('change');
  $('input[name="harga1"]').val(0);
  $('input[name="kuantitas1"]').val(0);
  $('select[name="diskon_tipe1"]').val('nominal');
  $('input[name="diskon1"]').val(0);
  $('input[name="diskon1"]').removeAttr('max');
  $('input[name="subtotal1"]').val(0);
  $('input[name="grandtotal1"]').val(0);
}

$(document).on('click', '.remove',function(e){
  e.preventDefault();
  var button_id = $(this).attr("id");
  var id_item = $('#id_produk-'+button_id+'').val();
  var nama_item = $('#nama_produk-'+button_id+'').text();
  reappend(id_item, nama_item);
  $('#row'+button_id+'').remove();
  hitungTotal();
});

function hitungSubtotal(number) {
  var diskonTipe = $('#diskontipe-'+number+'').val();
  var harga = parseInt($('#harga-'+number+'').val());
  var kuantitas = parseInt($('#kuantitas-'+number+'').val());
  var subtotal = harga * kuantitas;
  var diskon = parseInt($('#diskon-'+number+'').val());
  if (diskonTipe == 'nominal') {
    $('#diskon-'+number+'').attr('max', subtotal);
  } else {
    if (diskon > 100) $('#diskon-'+number+'').val(0);
    $('#diskon-'+number+'').attr('max', 100);
  }
  diskon = parseInt($('#diskon-'+number+'').val());
  var grandtotal = diskonTipe == 'nominal' ? (subtotal - diskon) : (subtotal * (100 - diskon)/100);
  $('#subtotal-'+number+'').val(subtotal);
  $('#grandtotal-'+number+'').val(grandtotal);
  $('#textsub-'+number+'').html('<input type="hidden" name="subtotal[]" class="subtotal" id="subtotal-'+i+'" value="'+subtotal+'">'+subtotal);
  $('#textgrd-'+number+'').html('<input type="hidden" name="grandtotal[]" class="grandtotal" id="grandtotal-'+i+'" value="'+grandtotal+'">'+grandtotal);
  hitungTotal();
}

function reappend(id_item, nama_item){
  $('select[name="id_produk1"] option').removeAttr("selected");
  var options_len = $('select[name="id_produk1"] option').length;
  for (var j = 0; j < options_len; j++) {
    var val = $('select[name="id_produk1"] option').eq(j).val()*1;
    if( val > id_item ){
      if(j != 0 && ( $('select[name="id_produk1"] option').eq(j-1).val()*1 ) != id_item ){
        $('select[name="id_produk1"] option').eq(j).before('<option value="'+id_item+'">'+nama_item+'</option>');
        break;
      } else if( j != 0 && ( $('select[name="id_produk1"] option').eq(j-1).val()*1 ) == id_item ){
        $('select[name="id_produk1"] option').eq(j-1).prop("selected", true );
        break;
      }
    }
  }
}

$('input[name="pajak"]').change(function(){
  hitungTotal();
}); 

$('input[name="diskon_tipe_all"]').change(function(){
  hitungTotal();
});

$('input[name="diskon_all"]').change(function(){
  hitungTotal();
});

function hitungTotal(){
  var total = 0;
  var grandtotal = 0;
  $('.grandtotal').each(function(){ total += parseInt($(this).val()) });
  var pajak = parseInt($('input[name="pajak"]').val()) / 100;
  grandtotal = total + (total * pajak);
  var diskonTipe = $('input[name="diskon_tipe_all"]:checked').val();
  var diskon = parseInt($('input[name="diskon_all"]').val());
  grandtotal -= (diskonTipe == 'nominal' ? diskon : (grandtotal * (100 - diskon)/100));
  $('input[name="subtotal_all"]').val(total);
  $('input[name="grand_total_all"]').val(grandtotal);
}
</script>