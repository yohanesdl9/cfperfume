<div class="row">

  <div class="col-md-12">
    <?php if ($access->is_create == 1) { ?>
    <a href="<?= base_url('penjualan/add') ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
    <?php } if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="card">
      <div class="card-body">
        <div class="form-group row">
          <?=  form_label('Sumber Transaksi', '', ['class' => 'col-md-3 col-form-label text-left']); ?>
          <div class="col-md-3">
            <?= form_dropdown('sumber_transaksi', ['all' => 'Semua Sumber', 'Tokopedia' => 'Tokopedia', 'Shopee' => 'Shopee', 'WhatsApp' => 'WhatsApp', 'Website' => 'Website'], 'all', ['class' => 'form-control']); ?>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3">
            <div class="form-check mt-2">
              <?php echo form_radio('filter_waktu', 'periode', true, ['class' => 'form-check-input']); echo form_label('Periode Transaksi dari', '', ['class' => 'form-check-label']) ?>
            </div>
          </div>
          <div class="col-md-3">
            <?= form_input('tanggal_range', NULL, ['class' => 'form-control', 'id' => 'daterangepicker']); ?>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3">
            <div class="form-check mt-2">
              <?php echo form_radio('filter_waktu', 'tanggal', false, ['class' => 'form-check-input']); echo form_label('Tanggal Transaksi', '', ['class' => 'form-check-label']) ?>
            </div>
          </div>
          <div class="col-md-3">
            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" disabled>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3">
            <div class="form-check mt-2">
              <?php echo form_radio('filter_waktu', 'bulan', false, ['class' => 'form-check-input']); echo form_label('Bulan Transaksi', '', ['class' => 'form-check-label']) ?>
            </div>
          </div>
          <div class="col-md-2">
            <?php $bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
            echo form_dropdown('bulan', $bulan, date('m'), ['class' => 'form-control', 'disabled' => true]); ?>
          </div>
          <div class="col-md-1">
            <?php $tahun = []; for ($i = date('Y', strtotime('-2 years')); $i <= date('Y', strtotime('+2 years')); $i++) $tahun[$i] = $i;
            echo form_dropdown('tahun', $tahun, date('Y'), ['class' => 'form-control', 'disabled' => true]); ?>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-borderless table-striped table-hover" id="tablePenjualan">
            <thead>
              <tr>
                <th class="text-center">Kode</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Nama Toko</th>
                <th class="text-center">Nama Pelanggan</th>
                <th class="text-center">Sumber Transaksi</th>
                <th class="text-center">Subtotal</th>
                <th class="text-center">Grand Total</th>
                <th class="text-center">Status Pembayaran</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($penjualan as $t){ ?>
              <tr>
                <td><?= $t['kode'] ?></td>
                <td><?= $t['tanggal'] ?></td>
                <td><?= $t['nama_toko'] ?></td>
                <td><?= $t['nama_pelanggan'] ?></td>
                <td class="text-center"><?php switch($t['sumber_transaksi']) {
                  case 'Tokopedia': 
                    echo '<span class="badge badge-success">' . $t['sumber_transaksi'] . '</span>'; 
                    break;
                  case 'Website': 
                    echo '<span class="badge badge-primary">' . $t['sumber_transaksi'] . '</span>'; 
                    break;
                  case 'Shopee': 
                    echo '<span class="badge badge-orange">' . $t['sumber_transaksi'] . '</span>'; 
                    break;
                  case 'WhatsApp': 
                    echo '<span class="badge badge-info">' . $t['sumber_transaksi'] . '</span>'; 
                    break;
                } ?></td>
                <td>Rp <?= number_format($t['subtotal'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($t['grand_total'], 0, ',', '.') ?></td>
                <td class="text-center"><?= ($t['status_pembayaran'] == 0 ? '<span class="badge badge-danger">BELUM BAYAR</span>' : '<span class="badge badge-success">SUDAH BAYAR</span>') ?></td>
                <td class="text-center">
                  <a href="#" data-toogle="tooltip" data-placement="top" title="Detail" onclick="detailJual('<?= $t['id'] ?>')"><i class="fas fa-eye"></i></a>
                  <?php if ($t['status_pembayaran'] == 0) { ?>
                  <a href="#" data-toogle="tooltip" data-placement="top" title="Sudah Bayar" onclick="sudahBayar('<?= $t['id'] ?>')"><i class="fas fa-cash-register"></i></a>
                  <?php } if ($access->is_edit == 1) { ?>
                  <a href="<?= base_url('penjualan/edit/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                  <?php } if ($access->is_delete == 1) { ?>
                  <a href="#" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="hapusData('<?= $t['id'] ?>')"><i class="fas fa-trash"></i></a>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="detailBeliModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Penjualan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <td><strong>Kode Penjualan</strong></td>
              <td id="kode_penjualan"></td>
            </tr>
            <tr>
              <td><strong>Tanggal</strong></td>
              <td id="tanggal_jual"></td>
            </tr>
            <tr>
              <td><strong>Pelanggan</strong></td>
              <td id="pelanggan"></td>
            </tr>
            <tr>
              <td><strong>Alamat Pengiriman</strong></td>
              <td id="alamat_pelanggan"></td>
            </tr>
            <tr>
              <td><strong>Sumber Transaksi</strong></td>
              <td id="sumber_transaksi"></td>
            </tr>
            <tr>
              <td><strong>No. Resi (Kurir)</strong></td>
              <td id="kurir_resi"></td>
            </tr>
          </tbody>
        </table>
        <table class="table table-borderless table-striped">
          <thead>
            <tr>
              <th class="text-center">Nama Item</th>
              <th class="text-center">Harga</th>
              <th class="text-center">Kuantitas</th>
              <th class="text-center">Diskon</th>
              <th class="text-center">Subtotal</th>
              <th class="text-center">Potongan Admin</th>
              <th class="text-center">Grand Total</th>
            </tr>
          </thead>
          <tbody id="detail-jual">

          </tbody>
        </table>
        <table class="table table-borderless col-md-4 offset-md-8">
          <tbody>
            <tr>
              <td><strong>Diskon</strong></td>
              <td id="diskon" class="text-right"></td>
            </tr>
            <tr>
              <td><strong>Subtotal</strong></td>
              <td id="subtotal" class="text-right"></td>
            </tr>
            <tr>
              <td><strong>Grand Total</strong></td>
              <td id="grandtotal" style="font-size: 18pt" class="text-right"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  var datatable = $('#tablePenjualan').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    'searching': false,
    'ordering': false,
    'destroy': true,
    'ajax': {
      'url': "<?= base_url('penjualan/get_penjualan_all') ?>",
      'data': function(data){
        data.filter_waktu = $('input[name="filter_waktu"]:checked').val();
        data.daterange = $('#daterangepicker').val();
        data.tanggal = $('input[name="tanggal"]').val();
        data.bulan = $('select[name="bulan"]').val();
        data.tahun = $('select[name="tahun"]').val();
        data.sumber = $('select[name="sumber_transaksi"]').val();
      }
    },
    'columns': [
      { data: 'kode' }, 
      { data: 'tanggal' },
      { data: 'nama_toko' },
      { data: 'nama_pelanggan' },
      { data: 'sumber_transaksi' }, 
      { data: 'subtotal' },
      { data: 'grand_total' },
      { data: 'status_pembayaran' },
      { data: 'aksi' }
    ],
    'columnDefs': [
      {
        targets: 4,
        className: 'text-center'
      },
      {
        targets: 7,
        className: 'text-center'
      },
      {
        targets: 8,
        className: 'text-center'
      }
    ]
  });

  $('#daterangepicker').change(function(){
    datatable.draw();
  });

  $('input[name="tanggal"]').change(function(){
    datatable.draw();
  });

  $('select[name="bulan"]').change(function(){
    datatable.draw();
  });

  $('select[name="tahun"]').change(function(){
    datatable.draw();
  });

  $('select[name="sumber_transaksi"]').change(function(){
    datatable.draw();
  })

  $('input[name="filter_waktu"]').change(function(){
    var value = $(this).val();
    $('#daterangepicker').attr('disabled', (value != 'periode'));
    $('input[name="tanggal"]').attr('disabled', (value != 'tanggal'));
    $('select[name="bulan"]').attr('disabled', (value != 'bulan'));
    $('select[name="tahun"]').attr('disabled', (value != 'bulan'));
    datatable.draw();
  });
});
$("#daterangepicker").daterangepicker({
  alwaysShowCalendars: true,
  locale: {
    format: 'YYYY-MM-DD'
  },
  startDate: moment().subtract(1, 'months'),
  endDate: moment()
});
function detailJual(id) {
  $.ajax({
    url: '<?= base_url('penjualan/get_penjualan/') ?>' + id,
    type: 'GET',
    dataType: 'JSON',
    success: function(data) {
      $('#kode_penjualan').html(data.kode);
      $('#tanggal_jual').html(data.tanggal);
      $('#pelanggan').html(data.nama_pelanggan);
      $('#alamat_pelanggan').html(data.alamat_pelanggan + ', ' + data.kel + ', ' + data.kec + ', ' + data.kota + ', ' + data.prov);
      $('#sumber_transaksi').html(data.sumber_transaksi);
      $('#kurir_resi').html(data.nomor_resi + ' (' + data.kurir + ')');
      $.each(data.detail, function(key, value){
        $('#detail-jual').append('<tr>'+
          '<td>'+value.nama+'</td>'+
          '<td>'+formatNumber(value.harga)+'</td>'+
          '<td class="text-center">'+value.qty+'</td>'+
          '<td class="text-center">'+formatNumber(value.diskon)+(value.diskon_tipe == 'persen' ? '%' : '')+'</td>'+
          '<td class="text-right">'+formatNumber(value.subtotal)+'</td>'+
          '<td class="text-right">'+formatNumber(value.potongan_admin)+'</td>'+
          '<td class="text-right">'+formatNumber(value.grand_total)+'</td>'+
        '</tr>');
      });
      $('#diskon').html(formatNumber(data.diskon)+(data.diskon_tipe == 'persen' ? '%' : ''));
      $('#subtotal').html('Rp ' + formatNumber(data.subtotal));
      $('#grandtotal').html('Rp ' + formatNumber(data.grand_total));
      $('#detailBeliModal').modal('show');
    },
    failure: function(){
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Gagal mengambil data'
      })
    }
  });
}

function hapusData(id){
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda tidak akan dapat mengembalikan data Anda!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak"
  }).then((result) => {
    if (result.value) {
      window.location.href = '<?= base_url('penjualan/hapus_penjualan/') ?>' + id;
    }
  });
}

function sudahBayar(id){
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda tidak akan dapat mengembalikan data Anda!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak"
  }).then((result) => {
    if (result.value) {
      window.location.href = '<?= base_url('penjualan/set_sudah_bayar/') ?>' + id;
    }
  });
}
</script>