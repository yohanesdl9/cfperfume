<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="mb-2">
      <a href="<?= base_url($base_url) ?>"><i class="fas fa-chevron-circle-left"></i><?= $back_text ?></a>
    </div>
    <div class="card">
      <div class="card-body">
        <?= $form ?>
      </div>
    </div>
  </div>
</div>