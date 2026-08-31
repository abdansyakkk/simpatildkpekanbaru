<?php if(! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"></i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-edit"></i> <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-8 col-sm-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Edit Data Dokumen</h3>
          </div>

          <form action="<?= base_url('data/prosesdokumen'); ?>" method="POST">
            <div class="box-body">

              <div class="form-group">
                <label for="nama_dokumen">Nama Dokumen</label>
                <input type="text" name="nama_dokumen" class="form-control" id="nama_dokumen"
                  value="<?= htmlentities($dokumen->nama_dokumen); ?>" required>
              </div>

              <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" id="deskripsi" rows="4"><?= htmlentities($dokumen->deskripsi); ?></textarea>
              </div>

              <!-- Hidden input for ID dokumen -->
              <input type="hidden" name="edit" value="<?= $dokumen->id_dokumen; ?>">

            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
              <a href="<?= base_url('data/dokumen'); ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </section>
</div>
