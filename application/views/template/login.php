<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>">

    <link href="<?= base_url('assets/plugins/sweet-alert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/jquery-ui.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/metisMenu.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/app.min.css') ?>" rel="stylesheet" type="text/css" />

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/metismenu.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/waves.js') ?>"></script>
    <script src="<?= base_url('assets/js/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sweet-alert2/sweetalert2.min.js') ?>"></script>    

  </head>
  <body class="account-body accountbg">

    <div class="container">
      <div class="row vh-100 ">
        <div class="col-12 align-self-center">
          <div class="auth-page">
            <div class="card auth-card shadow-lg">
              <div class="card-body">
                <div class="px-3">
                  <img src="<?= base_url('assets/images/logo-sm.png') ?>" height="100" class="ml-auto mr-auto d-block">         
                  <div class="text-center auth-logo-text">
                    <?php if($this->session->flashdata('message')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mt-2" role="alert">
                      <?= $this->session->flashdata('message') ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div> 
                    <?php } ?> 
                  </div>
                  <?php $this->load->view($content) ?>
                </div>
              </div>
            </div>
          </div>
        </div>       
      </div>
    </div>    

    <script src="<?= base_url('assets/js/app.js') ?>"></script>
      
  </body>
</html>