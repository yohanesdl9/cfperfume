<div class="row">
  <div class="col-md-12">
    <?php if ($access->is_create == 1) { ?>
    <a href="<?= base_url('pembelian/add') ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
    <?php } if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless table-striped table-hover datatable">
            <thead>
              <tr>
                <th class="text-center">Kode</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Subtotal</th>
                <th class="text-center">Grand Total</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($pembelian as $t){ ?>
              <tr>
                <td><?= $t['kode'] ?></td>
                <td><?= $t['tanggal'] ?></td>
                <td>Rp <?= number_format($t['subtotal'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($t['grand_total'], 0, ',', '.') ?></td>
                <td><?= $t['keterangan'] ?></td>
                <td class="text-center">
                  <a href="#" data-toogle="tooltip" data-placement="top" title="Detail" onclick="detailBeli('<?= $t['id'] ?>')"><i class="fas fa-eye"></i></a>
                  <?php if ($access->is_edit == 1) { ?>
                  <a href="<?= base_url('pembelian/edit/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
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
        <h5 class="modal-title" id="exampleModalLabel">Detail Pembelian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <td><strong>Kode Pembelian</strong></td>
              <td id="kode_pembelian"></td>
            </tr>
            <tr>
              <td><strong>Tanggal</strong></td>
              <td id="tanggal_beli"></td>
            </tr>
            <tr>
              <td><strong>Supplier</strong></td>
              <td id="supplier"></td>
            </tr>
            <tr>
              <td><strong>Keterangan</strong></td>
              <td id="keterangan_beli"></td>
            </tr>
          </tbody>
        </table>
        <table class="table table-borderless table-striped">
          <thead>
            <tr>
              <th class="text-center">Kode Item</th>
              <th class="text-center">Nama Item</th>
              <th class="text-center">Harga</th>
              <th class="text-center">Kuantitas</th>
              <th class="text-center">Diskon</th>
              <th class="text-center">Subtotal</th>
              <th class="text-center">Grand Total</th>
            </tr>
          </thead>
          <tbody id="detail-beli">

          </tbody>
        </table>
        <table class="table table-borderless col-md-4 offset-md-8">
          <tbody>
            <tr>
              <td><strong>Pajak</strong></td>
              <td id="pajak" class="text-right"></td>
            </tr>
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
function detailBeli(id) {
  $.ajax({
    url: '<?= base_url('pembelian/get_pembelian/') ?>' + id,
    type: 'GET',
    dataType: 'JSON',
    success: function(data) {
      $('#kode_pembelian').html(data.kode);
      $('#tanggal_beli').html(data.tanggal);
      $('#keterangan_beli').html(data.keterangan);
      $('#supplier').html(data.nama_supplier);
      $.each(data.detail, function(key, value){
        $('#detail-beli').append('<tr>'+
          '<td class="text-center">'+value.kode_item+'</td>'+
          '<td>'+value.nama_item+'</td>'+
          '<td>'+formatNumber(value.harga)+'</td>'+
          '<td class="text-center">'+value.kuantitas+'</td>'+
          '<td class="text-center">'+formatNumber(value.diskon)+(value.diskon_tipe == 'persen' ? '%' : '')+'</td>'+
          '<td class="text-right">'+formatNumber(value.subtotal)+'</td>'+
          '<td class="text-right">'+formatNumber(value.grand_total)+'</td>'+
        '</tr>');
      });
      $('#pajak').html(data.pajak + '%');
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
      window.location.href = '<?= base_url('pembelian/hapus_pembelian/') ?>' + id;
    }
  });
}
</script>