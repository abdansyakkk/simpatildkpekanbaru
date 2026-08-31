<?php if(! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-plus" style="color:green"></i> <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-plus"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="box box-primary">
          <div class="box-header with-border"></div>

          <div class="box-body">
            <form action="<?= base_url('data/prosesrole'); ?>" method="POST">
              <div class="form-group">
                <label>Nama Role</label>
                <input type="text" class="form-control" name="nama_role" placeholder="Contoh: Pranata, Kepala Kantor" required>
              </div>

              <div class="pull-right">
                <input type="hidden" name="tambah" value="tambah">
                <button type="submit" class="btn btn-primary btn-md">Simpan</button>
                <a href="<?= base_url('data/role'); ?>" class="btn btn-danger btn-md">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>