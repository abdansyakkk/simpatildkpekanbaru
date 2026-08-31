<?php if(! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-edit"></i> <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Edit Data Role</h3>
          </div>

          <form action="<?= base_url('data/prosesrole'); ?>" method="POST">
            <div class="box-body">

              <div class="form-group">
                <label for="nama_role">Nama Role</label>
                <input type="text" name="nama_role" class="form-control" id="nama_role" value="<?= $role->nama_role; ?>" required>
              </div>

              <input type="hidden" name="edit" value="<?= $role->id_role; ?>">

            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
              <a href="<?= base_url('data/role'); ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
