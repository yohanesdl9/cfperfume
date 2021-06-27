<div class="row">
  <div class="col-md-12">
    <?php if ($access->is_create == 1) { ?>
    <a href="<?= base_url('item/add') ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
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
                <th class="text-center">Gambar</th>
                <th class="text-center">Kode</th>
                <th class="text-center">Nama Item</th>
                <th class="text-center">Kategori</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Harga Beli</th>
                <th class="text-center">Harga Jual</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($item as $t){ ?>
              <tr>
                <td class="text-center"><img src="<?= base_url(isset($t['gambar']) ? $t['gambar'] : 'assets/images/users/user-1.jpg') ?>" alt="" width="50"></td>
                <td><?= $t['kode'] ?></td>
                <td><?= $t['keterangan'] ?></td>
                <td><?= $t['kategori'] ?></td>
                <td><?= $t['satuan'] ?></td>
                <td>Rp <?= number_format($t['harga_beli'], 0, ',', '.'); ?></td>
                <td>Rp <?= number_format($t['harga_jual'], 0, ',', '.'); ?></td>
                <td class="text-center"><?= $t['qty'] ?></td>
                <td class="text-center">
                <a href="<?= base_url('item/stok/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Stok"><i class="fas fa-cube"></i></a>
                  <?php if ($access->is_edit == 1) { ?>
                  <a href="<?= base_url('item/edit/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
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
    if (result.value) {
      window.location.href = '<?= base_url('item/hapus_item/') ?>' + id;
    }
  });
}
</script>