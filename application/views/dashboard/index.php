<div class="row">
<div class="col-md-12 col-lg-12">
  <div class="form-group row">
    <?= form_label('Pilih Bulan', '', ['class' => 'col-md-1 col-form-label text-left']) ?>
    <div class="col-md-2">
      <?php $bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
      echo form_dropdown('bulan', $bulan, date('m'), ['class' => 'form-control']); ?>
    </div>
    <div class="col-md-1">
      <?php $tahun = []; for ($i = date('Y', strtotime('-2 years')); $i <= date('Y', strtotime('+2 years')); $i++) $tahun[$i] = $i;
      echo form_dropdown('tahun', $tahun, date('Y'), ['class' => 'form-control']); ?>
    </div>
  </div>
</div>
<?php foreach ($dashboard as $d) { ?>
  <div class="col-md-6 col-lg-3">
    <div class="card report-card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div>
            <p class="text-dark font-weight-semibold font-14" id="<?= $d['label_id'] ?>"><?= $d['label'] ?></p>
            <h3 class="my-3" id="<?= $d['id'] ?>"><?= $d['value'] ?></h3>
          </div>
          <div class="align-self-center">
            <i class="<?= $d['icon'] ?> report-main-icon bg-soft-purple text-purple"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
</div>
<div class="row">
  <div class="col-md-12">                                                    
    <div class="card">
      <div class="card-body">  
        <h4 class="header-title mt-0">Omset Penjualan Tahun <?= date('Y') ?></h4>                                 
        <div class="">
          <div id="omset" class="apex-charts"></div>
        </div>  
      </div>                      
    </div>
  </div>
</div>
<script>
var options = {
  chart: {
    height: 350,
    type: 'line',
    stacked: true,
    toolbar: {
      show: false,
      autoSelected: 'zoom'
    },
    dropShadow: {
      enabled: true,
      top: 12,
      left: 0,
      bottom: 0,
      right: 0,
      blur: 2,
      color: '#45404a2e',
      opacity: 0.35
    },
  },
  colors: ['#2a77f4', '#1ccab8', '#f02fc2'],
  dataLabels: { enabled: false },
  stroke: {
    curve: 'straight',
    width: [4, 4]
  },
  grid: {
    borderColor: "#45404a2e",
    padding: {
      left: 0,
      right: 0
    }
  },
  markers: {
    size: 0,
    hover: { size: 0 }
  },
  series: [{
    name: 'Omset',
    data: <?= '[' . implode(',', $omset) . ']' ?>
  }],

  xaxis: {
    type: 'text',
    categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],  
    axisBorder: {
      show: true,
      color: '#45404a2e',
    },  
    axisTicks: {
      show: true,
      color: '#45404a2e',
    },                  
  },

  fill: {
    type: 'gradient',
    gradient: { gradientToColors: ['#F55555', '#B5AC49', '#6094ea'] },
  },
  tooltip: {
    x: { format: 'MM/yy' },
  },
  legend: {
    position: 'top',
    horizontalAlign: 'right'
  },
}

var chart = new ApexCharts(
  document.querySelector("#omset"),
  options
);

chart.render();

$('select[name="bulan"]').change(function(){
  var bulan = $(this).val();
  var tahun = $('select[name="tahun"]').val();
  changeStatistic(bulan, tahun);
});

$('select[name="tahun"]').change(function(){
  var tahun = $(this).val();
  var bulan = $('select[name="bulan"]').val();
  changeStatistic(bulan, tahun);
});

function changeStatistic(bulan, tahun){
  var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

  var today = new Date();
  var year_today = today.getFullYear();
  var month_today = today.getMonth();

  if (bulan != (month_today + 1) || tahun != year_today) {
    $('#label_peng_month').html('Pengeluaran Bulan ' + months[bulan - 1] + ' ' + year_today);
    $('#label_pemb_month').html('Pembelian Bulan ' + months[bulan - 1] + ' ' + year_today);
    $('#label_penj_month').html('Penjualan Bulan ' + months[bulan - 1] + ' ' + year_today);
  } else {
    $('#label_peng_month').html('Pengeluaran Bulan Ini');
    $('#label_pemb_month').html('Pembelian Bulan Ini');
    $('#label_penj_month').html('Penjualan Bulan Ini');
  }

  $.ajax({
    url: '<?= base_url('dashboard/get_filtered_stats/') ?>' + bulan + '/' + tahun,
    type: 'GET',
    dataType: 'JSON',
    success: function(data) {
      $('#peng_month').html(data.pengeluaran);
      $('#pemb_month').html(data.pembelian);
      $('#penj_month').html(data.penjualan);
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
</script>