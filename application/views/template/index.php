<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>">

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/sweet-alert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/plugins/nestable/jquery.nestable.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap4.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatables/buttons.bootstrap4.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatables/responsive.bootstrap4.min.css') ?>" /> 
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.16/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker.css') ?>" />

    <!-- App css -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery-ui.min.css') ?>" >
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/icons.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/metisMenu.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/app.min.css') ?>" />

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/metismenu.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/waves.js') ?>"></script>
    <script src="<?= base_url('assets/js/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script src="<?= base_url('assets/plugins/moment/moment.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/apexcharts/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js') ?>"></script>
    <script src="<?= base_url('assets/pages/jquery.analytics_dashboard.init.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/flot-chart/jquery.flot-dataType.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sweet-alert2/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/buttons.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/jszip.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/pdfmake.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/vfs_fonts.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/buttons.html5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/buttons.print.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/buttons.colVis.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/responsive.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/moment/moment.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/daterangepicker/daterangepicker.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/tinymce/tinymce.min.js') ?>"></script>
    <script src="<?= base_url('assets/pages/jquery.form-editor.init.js') ?>"></script> 
  </head>
  <body>
    <div class="topbar">
      <div class="topbar-left">
        <a href="<?= base_url() ?>" class="logo">
          <span>
            <img src="<?= base_url('assets/images/logo-sm.png') ?>" alt="logo-small" class="logo-sm">
          </span>
        </a>
      </div>
      <nav class="navbar-custom">    
        <ul class="list-unstyled topbar-nav float-right mb-0"> 
          <li class="dropdown">
            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
              <img src="<?= base_url('assets/images/users/user-1.png') ?>" alt="profile-user" class="rounded-circle" /> 
              <span class="ml-1 nav-user-name hidden-sm"><?= $this->session->userdata('name') ?><i class="mdi mdi-chevron-down"></i> </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="<?= base_url('user/profile') ?>"><i class="ti-user text-muted mr-2"></i> Profile</a>
              <div class="dropdown-divider mb-0"></div>
              <a class="dropdown-item" href="#" onclick="confirmLogout()"><i class="ti-power-off text-muted mr-2"></i> Logout</a>
            </div>
          </li>
        </ul>
        <ul class="list-unstyled topbar-nav mb-0">                        
          <li>
            <button class="nav-link button-menu-mobile waves-effect waves-light"><i class="ti-menu nav-icon"></i></button>
          </li>
        </ul>
      </nav>
    </div>
    <div class="left-sidenav">
      <ul class="metismenu left-sidenav-menu">
        <?php foreach ($menu as $m){
          if (isset($m['child']) && count($m['child']) > 0) { ?>
          <li>
            <a href="javascript: void(0);"><i class="<?= $m['icon'] ?>"></i><span><?= $m['name'] ?></span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
            <ul class="nav-second-level" aria-expanded="false">
            <?php foreach ($m['child'] as $mc) { ?>
              <li class="nav-item"><a class="nav-link" href="<?= base_url($mc['path']) ?>"><i class="<?= $mc['icon'] ?>"></i><?= $mc['name'] ?></a></li>
            <?php } ?>
            </ul>
          </li>
          <?php } else { ?>
          <li>
            <a href="<?= base_url($m['path']) ?>"><i class="<?= $m['icon'] ?>"></i><span><?= $m['name'] ?></span></a>
          </li>
          <?php } ?>
        <?php } ?>
        <?php if ($this->session->userdata('id_privileges') == 1) { ?>
        <li class="navbar-header">Superadmin</li>
        <li><a href="<?= base_url('hak_akses') ?>"><i class="ti-key"></i><span>Detail Hak Akses</span></a></li>
        <li><a href="<?= base_url('user') ?>"><i class="ti-user"></i><span>Manajemen User</span></a></li>
        <li><a href="<?= base_url('menu') ?>"><i class="ti-menu"></i><span>Manajemen Menu</span></a></li>
        <?php } ?>
      </ul>
    </div>
    <div class="page-wrapper">
      <div class="page-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <h4 class="page-title"><?= $title ?></h4>
              </div>
            </div>
          </div>
          <?php $this->load->view($content) ?>
        </div>
        <footer class="footer text-center text-sm-left">
          &copy; 2020 Beautica Store <span class="text-muted d-none d-sm-inline-block float-right">Crafted with <i class="mdi mdi-heart text-danger"></i> by YDL</span>
        </footer>
      </div>
    </div>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script>
    $(document).ready(function(){
      $(".select2").select2({
        width: '100%'
      });
      $('.datatable').DataTable({
        ordering: false
      });
    });
    function confirmLogout(id){
      Swal.fire({
        title: "Apakah Anda ingin keluar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak"
      }).then((result) => {
        if (result.value) {
          window.location.href = '<?= base_url('login/logout') ?>';
        }
      });
    }
    $('input[type="number"]').keypress(function(evt){
      var min = parseInt($(this).attr("min"));
      var max = $(this).attr("max");
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (
      //0~9
      charCode >= 48 && charCode <= 57 ||
      //number pad 0~9
      charCode >= 96 && charCode <= 105 ||
      //backspace
      charCode == 8 ||
      //tab
      charCode == 9 ||
      //enter
      charCode == 13 || 
      //left, right, delete..
      charCode >= 35 && charCode <= 46) {
        if (parseInt(this.value + String.fromCharCode(charCode), 10) >= min) {
          if (typeof max !== 'undefined' && max !== false) {
            if (parseInt(this.value + String.fromCharCode(charCode), 10) <= parseInt(max)) return true;
          } else {
            return true;
          }
        }
      }

      evt.preventDefault();
      evt.stopPropagation();

      return false;
    });

    function formatNumber(number){
      var string = parseInt(number).toLocaleString(window.document.documentElement.lang);
      return string.replace(/,/g, '.');
    }
    </script>
  </body>
</html>