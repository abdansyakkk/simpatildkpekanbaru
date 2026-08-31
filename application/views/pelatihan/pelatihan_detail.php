<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
function get_nama_pegawai($pegawais, $id) {
    foreach ($pegawais as $p) {
        if ($p['id_pegawai'] == $id) return $p['nama'];
    }
    return '<span class="text-muted" title="Data belum tersedia">-</span>';
}

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
      <li class="active"><i class="fa fa-eye"></i> <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-graduation-cap text-green"></i> <?= $pelatihan->nama_pelatihan; ?></h3>
            <h3 class="box-title"><i class="fa fa-graduation-cap text-green"></i> <?= $pelatihan->id_pelatihan; ?></h3>
            <h3 class="box-title"><i class="fa fa-graduation-cap text-green"></i> <?= $pelatihan->id_jenis_pelatihan; ?></h3>
          </div>
          <div class="box-body">

            <!-- Informasi Umum -->
            <div class="section-title"><i class="fa fa-info-circle text-blue"></i> Informasi Umum</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Nama Kegiatan</th>
                <td><?= $pelatihan->nama_kegiatan; ?></td>
              </tr>
              <tr>
                <th>Nama Pelatihan</th>
                <td><?= $pelatihan->nama_pelatihan; ?></td>
              </tr>
              <tr>
                <th>Jenis Pelatihan</th>
                <td><?= $jenis_pelatihan->nama_jenis_pelatihan; ?></td>
              </tr>
              <tr>
                <th>Provinsi</th>
                <td><span class="badge badge-custom"><?= $pelatihan->provinsi; ?></span></td>
              </tr>
              <tr>
                <th>Kabupaten/Kota</th>
                <td><?= $pelatihan->kab_kota ?: '<span class="text-muted" title="Tidak tersedia">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Tempat</th>
                <td><?= $pelatihan->tempat; ?></td>
              </tr>
              <tr>
                <th>Tanggal Mulai</th>
                <td><span class="badge badge-success"><?= date('d-m-Y', strtotime($pelatihan->tanggal_mulai_pelatihan)); ?></span></td>
              </tr>
              <tr>
                <th>Tanggal Selesai</th>
                <td><span class="badge badge-success"><?= date('d-m-Y', strtotime($pelatihan->tanggal_selesai_pelatihan)); ?></span></td>
              </tr>
              <tr>
                <th>Bulan TTD Laporan</th>
                <td><?= $pelatihan->bulan_ttd_lap; ?></td>
              </tr>
              <tr>
                <th>Tahun</th>
                <td><span class="badge badge-warning"><?= $pelatihan->tahun; ?></span></td>
              </tr>
            </table>

            <!-- Rangkaian Acara -->
            <div class="section-title"><i class="fa fa-calendar text-orange"></i> Rangkaian Acara</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Tanggal Pembukaan</th>
                <td><?= $pelatihan->hari_tanggal_pembukaan ? date('d-m-Y', strtotime($pelatihan->hari_tanggal_pembukaan)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Waktu Pembukaan</th>
                <td><?= $pelatihan->waktu_pembukaan ?: '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Pejabat Pembuka</th>
                <td><?= get_nama_pegawai($pegawais, $pelatihan->id_pejabat_pembuka); ?></td>
              </tr>
              <tr>
                <th>Jabatan Pejabat Pembuka</th>
                <td><?= get_nama_role($roles, $pelatihan->id_role_pembuka); ?></td>
              </tr>
              <tr>
                <th>Tanggal Penutupan</th>
                <td><?= $pelatihan->hari_tanggal_penutupan ? date('d-m-Y', strtotime($pelatihan->hari_tanggal_penutupan)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Waktu Penutupan</th>
                <td><?= $pelatihan->waktu_penutupan ?: '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Pejabat Penutup</th>
                <td><?= get_nama_pegawai($pegawais, $pelatihan->id_pejabat_penutup); ?></td>
              </tr>
              <tr>
                <th>Jabatan Pejabat Penutup</th>
                <td><?= get_nama_role($roles, $pelatihan->id_role_penutup); ?></td>
              </tr>
            </table>

            <!-- Metadata -->
            <div class="section-title"><i class="fa fa-clock-o text-gray"></i> Metadata</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Dibuat pada</th>
                <td><?= date('d-m-Y H:i:s', strtotime($pelatihan->created_at)); ?></td>
              </tr>
              <tr>
                <th>Diperbarui pada</th>
                <td><?= date('d-m-Y H:i:s', strtotime($pelatihan->updated_at)); ?></td>
              </tr>
            </table>

            <div class="text-right">
              <?php 
              $mapJenis = function ($id) {
                  switch ((int)$id) {
                      case 1: return 'PJJ';
                      case 2: return 'PDWK';
                      case 3: return 'Latsar';
                      default: return 'PDWK';
                  }
              };
              $redirJenis = isset($pelatihan->id_jenis_pelatihan) ? $mapJenis($pelatihan->id_jenis_pelatihan) : 'PDWK';
              
              ?>
              <a href="<?= base_url('data?jenis=' . $redirJenis); ?>" class="btn btn-danger btn-md">
                <i class="fa fa-arrow-left"></i> Kembali
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
