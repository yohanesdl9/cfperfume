<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="mb-2">
      <a href="<?= base_url('pengeluaran') ?>"><i class="fas fa-chevron-circle-left"></i> Kembali ke halaman Pengeluaran</a>
    </div>
    <div class="card">
      <div class="card-body">
        <form action="<?= base_url('pengeluaran/tambah_pengeluaran') ?>" method="POST">
          <?php foreach ($form[0] as $f) echo generate($f); ?>
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Tambah Detail Pengeluaran</h5>
            </div>
            <div class="card-body">
              <div id="row-pembelian">
                <div class="row" id="row">
                  <div class="col-md-6"><?= form_label('Nama Barang', '', ['class' => 'col-form-label']) . generate_form(['name' => 'nama_produk[]', 'type' => 'text', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Nama Barang']]); ?></div>
                  <div class="col-md-2"><?= form_label('Harga', '', ['class' => 'col-form-label']) . generate_form(['name' => 'harga[]', 'type' => 'number', 'attributes' => ['class' => 'form-control harga', 'id' => 'harga-1', 'min' => 0], 'value' => 0]); ?></div>
                  <div class="col-md-1"><?= form_label('Qty', '', ['class' => 'col-form-label']) . generate_form(['name' => 'kuantitas[]', 'type' => 'number', 'attributes' => ['class' => 'form-control kuantitas', 'id' => 'kuantitas-1', 'min' => 0], 'value' => 0]); ?></div>
                  <div class="col-md-2"><?= form_label('Subtotal', '', ['class' => 'col-form-label']) . generate_form(['name' => 'subtotal[]', 'type' => 'number', 'attributes' => ['class' => 'form-control subtotal', 'readonly' => true, 'id' => 'subtotal-1'], 'value' => 0]); ?></div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="tambah" style="margin-top: 36px;"><i class="fas fa-plus"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php foreach ($form[1] as $f) echo generate($f); ?>
          <input type="submit" class="btn btn-success" name="submit" value="Simpan & Tambah Lagi">
          <input type="submit" class="btn btn-success float-md-right" name="submit" value="Simpan">
        </form>
      </div>
    </div>
  </div>
</div>
<script>
var i = 1;
$('#tambah').click(function(e){
  e.preventDefault();
  i++;
  $('#row-pembelian').append('<div class="row mt-2" id="row'+i+'">'+
    '<div class="col-md-6"><input type="text" name="nama_produk[]" class="form-control" placeholder="Nama Barang"></div>'+
    '<div class="col-md-2"><input type="number" name="harga[]" class="form-control harga" id="harga-'+i+'" min="0" value="0"></div>'+
    '<div class="col-md-1"><input type="number" name="kuantitas[]" class="form-control kuantitas" id="kuantitas-'+i+'" min="0" value="0"></div>'+
    '<div class="col-md-2"><input type="number" name="subtotal[]" class="form-control subtotal" id="subtotal-'+i+'" value="0"></div>'+
    '<div class="col-md-1">'+
      '<button type="button" class="btn btn-danger remove" id="'+i+'"><i class="fas fa-minus"></i></button>'+
    '</div></div>');

    $('.harga').change(function(){
      var id = $(this).attr("id");
      var number = id.split('-')[1];
      var harga = parseInt($('#harga-'+number+'').val());
      var kuantitas = parseInt($('#kuantitas-'+number+'').val());
      var total = $('#subtotal-'+number+'').val(harga * kuantitas);
      hitungTotal();
    });

    $('.kuantitas').change(function(){
      var id = $(this).attr("id");
      var number = id.split('-')[1];
      var harga = parseInt($('#harga-'+number+'').val());
      var kuantitas = parseInt($('#kuantitas-'+number+'').val());
      var total = $('#subtotal-'+number+'').val(harga * kuantitas);
      hitungTotal();
    });
});

$('.harga').change(function(){
  var id = $(this).attr("id");
  var number = id.split('-')[1];
  var harga = parseInt($('#harga-'+number+'').val());
  var kuantitas = parseInt($('#kuantitas-'+number+'').val());
  var total = $('#subtotal-'+number+'').val(harga * kuantitas);
  hitungTotal();
});

$('.kuantitas').change(function(){
  var id = $(this).attr("id");
  var number = id.split('-')[1];
  var harga = parseInt($('#harga-'+number+'').val());
  var kuantitas = parseInt($('#kuantitas-'+number+'').val());
  var total = $('#subtotal-'+number+'').val(harga * kuantitas);
  hitungTotal();
});

$(document).on('click', '.remove',function(e){
  e.preventDefault();
  var button_id = $(this).attr("id");
  $('#row'+button_id+'').remove();
  hitungTotal();
});

function hitungTotal(){
  var total = 0;
  $('.subtotal').each(function(){ total += parseInt($(this).val()) });
  $('input[name="grand_total"]').val(total);
}
</script>