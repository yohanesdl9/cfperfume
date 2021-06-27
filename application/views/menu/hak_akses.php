<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
  </div>
  <div class="col-md-12">
    <form action="<?php echo base_url('menu/atur_hak_akses/' . $this->uri->segment(3)) ?>" method="post">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Atur Hak Akses Menu</h4>
        </div>
        <div class="card-body">
        <?php foreach ($hak_akses as $ha) {
          $access_rights = isset($ha['id_menu']) ? 1 : 0; ?>
          <div class="form-group row">
            <div class="col-md-3">
              <div class="form-check">
                <input type="checkbox" name="id_privileges[]" class="form-check-input hak-akses" value="<?= $ha['id'] ?>" <?= $access_rights == 1 ? 'checked' : '' ?>>
                <label class="form-check-label"><?= $ha['name'] ?></label>
              </div>
            </div>
            <div class="col-md-9">
              <div class="form-check-inline">
                <input type="checkbox" name="create_<?= $ha['id'] ?>" class="form-check-input" id="create_<?= $ha['id'] ?>" value="1" <?= ($access_rights == 0 ? 'disabled' : '') . ' ' . ($ha['is_create'] == 1 ? 'checked' : '') ?>>
                <label class="form-check-label">Create</label>
              </div>
              <div class="form-check-inline">
                <input type="checkbox" name="read_<?= $ha['id'] ?>" class="form-check-input" id="read_<?= $ha['id'] ?>" value="1" <?= ($access_rights == 0 ? 'disabled' : '') . ' ' . ($ha['is_read'] == 1 ? 'checked' : '') ?>>
                <label class="form-check-label">Read</label>
              </div>
              <div class="form-check-inline">
                <input type="checkbox" name="update_<?= $ha['id'] ?>" class="form-check-input" id="update_<?= $ha['id'] ?>" value="1" <?=  ($access_rights == 0 ? 'disabled' : '') . ' ' . ($ha['is_edit'] == 1 ? 'checked' : '') ?>>
                <label class="form-check-label">Update</label>
              </div>
              <div class="form-check-inline">
                <input type="checkbox" name="delete_<?= $ha['id'] ?>" class="form-check-input" id="delete_<?= $ha['id'] ?>" value="1" <?=  ($access_rights == 0 ? 'disabled' : '') . ' ' . ($ha['is_delete'] == 1 ? 'checked' : '') ?>>
                <label class="form-check-label">Delete</label>
              </div>
            </div>
          </div>
        <?php } ?>
          <input type="submit" class="btn btn-success btn-block" value="Simpan">
        </div>
      </div>
    </form>
  </div>
</div>
<script>  
$('.hak-akses').change(function(){
  var checked = this.checked;
  var id_role = $(this).val();
  if (checked) {
    $('#create_' + id_role).removeAttr('disabled');
    $('#read_' + id_role).removeAttr('disabled');
    $('#update_' + id_role).removeAttr('disabled');
    $('#delete_' + id_role).removeAttr('disabled');
  } else {
    $('#create_' + id_role).attr('disabled', true);
    $('#read_' + id_role).attr('disabled', true);
    $('#update_' + id_role).attr('disabled', true);
    $('#delete_' + id_role).attr('disabled', true);
    $('#create_' + id_role).prop('checked', false);
    $('#read_' + id_role).prop('checked', false);
    $('#update_' + id_role).prop('checked', false);
    $('#delete_' + id_role).prop('checked', false);
  }
});
</script>