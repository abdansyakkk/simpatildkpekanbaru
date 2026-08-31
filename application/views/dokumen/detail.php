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
      <div class="col-md-8 col-sm-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-file-text-o text-green"></i> <?= $dokumen->nama_dokumen; ?></h3>
          </div>
          <div class="box-body">

            <!-- Informasi Dokumen -->
            <div class="section-title"><i class="fa fa-info-circle text-blue"></i> Informasi Dokumen</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Nama Dokumen</th>
                <td><?= htmlentities($dokumen->nama_dokumen); ?></td>
              </tr>
              <tr>
                <th>Deskripsi</th>
                <td><?= nl2br(htmlentities($dokumen->deskripsi ?: '-')); ?></td>
              </tr>
            </table>

            <!-- Metadata -->
            <div class="section-title"><i class="fa fa-clock-o text-gray"></i> Metadata</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Dibuat pada</th>
                <td><?= date('d-m-Y H:i:s', strtotime($dokumen->created_at)); ?></td>
              </tr>
              <tr>
                <th>Diperbarui pada</th>
                <td><?= date('d-m-Y H:i:s', strtotime($dokumen->updated_at)); ?></td>
              </tr>
            </table>

            <div class="text-right">
              <a href="<?= base_url('data/dokumen'); ?>" class="btn btn-danger btn-md">
                <i class="fa fa-arrow-left"></i> Kembali
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
