
<!-- Content Wrapper -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>Dashboard <small>Control panel</small></h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-sm-12">
        
        <!-- Laporan PJJ -->
        <div class="col-lg-4 col-xs-12">
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $count_pelatihan_pjj; ?></h3>
              <p>Laporan Pelatihan PJJ</p>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
            <a class="small-box-footer" href="<?php echo base_url("data?jenis=PJJ");?>">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- Laporan PDWK -->
        <div class="col-lg-4 col-xs-12">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $count_pelatihan_pdwk; ?></h3>
              <p>Laporan Pelatihan PDWK</p>
            </div>
            <div class="icon"><i class="fa fa-user"></i></div>
            <a class="small-box-footer" href="<?php echo base_url("data?jenis=PDWK");?>">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <!-- Laporan Latsar -->
        <div class="col-lg-4 col-xs-12">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $count_pelatihan_latsar; ?></h3>
              <p>Laporan Pelatihan Dasar CPNS</p>
            </div>
            <div class="icon"><i class="fa fa-user"></i></div>
            <a class="small-box-footer" href="<?php echo base_url("data?jenis=Latsar");?>">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <!-- Cetak Laporan -->
        <div class="col-lg-4 col-xs-12">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $count_pelatihan; ?></h3>
              <p>Cetak Laporan</p>
            </div>
            <div class="icon"><i class="fa fa-graduation-cap"></i></div>
            <a class="small-box-footer" href="<?php echo base_url("data/cetaklaporan");?>">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>
