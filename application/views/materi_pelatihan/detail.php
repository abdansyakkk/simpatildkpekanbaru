<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-eye text-green"></i> <?= $title_web; ?></h1>
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
            <h3 class="box-title"><i class="fa fa-clipboard text-green"></i> Detail Materi Pelatihan: <?= $materi_pelatihan->id_materi_pelatihan; ?></h3>
          </div>
          <div class="box-body">

            <!-- Informasi Pelatihan -->
            <div class="section-title"><i class="fa fa-book text-blue"></i> Informasi Materi Pelatihan</div>
            <table class="table table-bordered table-detail">
              <tr><th style="width:30%">ID Pelatihan</th><td><?= $materi_pelatihan->id_pelatihan; ?></td></tr>
              <tr><th>Jumlah JP</th><td><?= $materi_pelatihan->jumlah_jp; ?></td></tr>
              <tr><th>JP Kel. Dasar / Inti / Penunjang</th>
                <td><?= $materi_pelatihan->jp_kel_dasar; ?> / <?= $materi_pelatihan->jp_kel_inti; ?> / <?= $materi_pelatihan->jp_kel_penunjang; ?></td>
              </tr>
            </table>

            <!-- Mata Pelatihan -->
            <div class="section-title"><i class="fa fa-list text-orange"></i> Mata Pelatihan</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th>Kel. Dasar</th>
                <td><?= !empty($materi_pelatihan->nama_mata_pelatihan_kel_dasar) ? nl2br(htmlentities($materi_pelatihan->nama_mata_pelatihan_kel_dasar)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Kel. Inti</th>
                <td><?= !empty($materi_pelatihan->nama_mata_pelatihan_kel_inti) ? nl2br(htmlentities($materi_pelatihan->nama_mata_pelatihan_kel_inti)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Kel. Penunjang</th>
                <td><?= !empty($materi_pelatihan->nama_mata_pelatihan_kel_penunjang) ? nl2br(htmlentities($materi_pelatihan->nama_mata_pelatihan_kel_penunjang)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
            </table>

            <!-- Tujuan & Latar Belakang -->
            <div class="section-title"><i class="fa fa-bullseye text-purple"></i> Latar Belakang & Tujuan</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th>Latar Belakang</th>
                <td><?= !empty($materi_pelatihan->latar_belakang) ? nl2br(htmlentities($materi_pelatihan->latar_belakang)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Tujuan Pelatihan</th>
                <td><?= !empty($materi_pelatihan->tujuan_pelatihan) ? nl2br(htmlentities($materi_pelatihan->tujuan_pelatihan)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Tujuan Kursil</th>
                <td><?= !empty($materi_pelatihan->tujuan_kursil) ? nl2br(htmlentities($materi_pelatihan->tujuan_kursil)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
              <tr>
                <th>Asal Kursil</th>
                <td><?= !empty($materi_pelatihan->asal_kursil) ? nl2br(htmlentities($materi_pelatihan->asal_kursil)) : '<span class="text-muted">-</span>'; ?></td>
              </tr>
            </table>

            <!-- Metadata -->
            <div class="section-title"><i class="fa fa-clock-o text-gray"></i> Metadata</div>
            <table class="table table-bordered table-detail">
              <tr><th>Dibuat pada</th><td><?= date('d-m-Y H:i:s', strtotime($materi_pelatihan->created_at)); ?></td></tr>
              <tr><th>Diperbarui pada</th><td><?= date('d-m-Y H:i:s', strtotime($materi_pelatihan->updated_at)); ?></td></tr>
            </table>

            <div class="text-right">
              <a href="<?= base_url('data/materipelatihan'). (!empty($jenis) ? '?jenis=' . urlencode($jenis) : ''); ?>" class="btn btn-danger btn-md">
                <i class="fa fa-arrow-left"></i> Kembali
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
