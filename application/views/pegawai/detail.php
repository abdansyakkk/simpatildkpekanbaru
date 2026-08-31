<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
function get_nama_role($roles, $id) {
    foreach ($roles as $r) {
        if ($r['id_role'] == $id) return $r['nama_role'];
    }
    return '<span class="text-muted" title="Data belum tersedia">-</span>';
}
?>

<style>
  .box.box-primary {
    border-top: 3px solid #00a65a;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-radius: 6px;
  }

  .section-title {
    font-weight: 600;
    margin: 25px 0 15px;
    font-size: 18px;
    border-left: 4px solid #3c8dbc;
    padding-left: 10px;
    color: #34495e;
  }

  .table-detail tr:nth-child(odd) {
    background-color: #f9f9f9;
  }

  .badge-custom {
    font-size: 13px;
    background-color: #3c8dbc;
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
  }

  .text-muted {
    color: #999;
    font-style: italic;
  }

  .fa {
    margin-right: 5px;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-eye" style="color:green;"></i> <?= $title_web; ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-user"></i> <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user text-green"></i> <?= $pegawai->nama; ?></h3>
          </div>
          <div class="box-body">

            <!-- Informasi Pegawai -->
            <div class="section-title"><i class="fa fa-info-circle text-blue"></i> Informasi Pegawai</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Nama Lengkap</th>
                <td><?= $pegawai->nama; ?></td>
              </tr>
              <tr>
                <th>NIP</th>
                <td><?= $pegawai->NIP ?: '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Jabatan</th>
                <td><?= get_nama_role($roles, $pegawai->jabatan); ?></td>
              </tr>
              <tr>
                <th>Asal Satuan Kerja</th>
                <td><?= $pegawai->asal_satker ?: '<span class="text-muted">-</span>'; ?></td>
              </tr>
            </table>

            <!-- Metadata -->
            <div class="section-title"><i class="fa fa-clock-o text-gray"></i> Metadata</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Dibuat pada</th>
                <td><?= date('d-m-Y H:i:s', strtotime($pegawai->created_at)); ?></td>
              </tr>
              <tr>
                <th>Diperbarui pada</th>
                <td><?= date('d-m-Y H:i:s', strtotime($pegawai->updated_at)); ?></td>
              </tr>
            </table>

            <div class="text-right">
              <a href="<?= base_url('data/pegawai'); ?>" class="btn btn-danger btn-md">
                <i class="fa fa-arrow-left"></i> Kembali
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
