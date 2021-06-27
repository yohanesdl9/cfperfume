<div class="row">
  <div class="col-md-12">
    <?php if ($access->is_create == 1) { ?>
    <a href="<?= base_url('item/stok_tambah/' . $this->uri->segment(3)) ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
    <?php } if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="mb-2">
      <a href="<?= base_url('item') ?>"><i class="fas fa-chevron-circle-left"></i> Kembali ke halaman Item</a>
    </div>
    <div class="card">
      <div class="card-body">
        <table class="table table-borderless">
          <tr>
            <td class="w-25"><strong>Kode</strong></td>
            <td><?= $item['kode'] ?></td>
          </tr>
          <tr>
            <td class="w-25"><strong>Keterangan</strong></td>
            <td><?= $item['keterangan'] ?></td>
          </tr>
        </table>
        <table class="table table-borderless table-striped table-hover datatable">
          <thead>
            <tr>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Stok Masuk</th>
              <th class="text-center">Stok Keluar</th>
              <th class="text-center">Keterangan</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($stok as $s){ ?>
            <tr>
              <td><?= $s['tanggal'] ?></td>
              <td class="text-center"><?= number_format($s['stok_masuk'], 0, ',', '.') ?></td>
              <td class="text-center"><?= number_format($s['stok_keluar'], 0, ',', '.') ?></td>
              <td><?= $s['keterangan'] ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
function hapusData(id){
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda tidak akan dapat mengembalikan data Anda!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak"
  }).then((result) => {
    console.log(result.value);
    if (result.value) {
      window.location.href = '<?= base_url('bahan_jasa/hapus_bahan_jasa/') ?>' + id;
    }
  });
}
</script>