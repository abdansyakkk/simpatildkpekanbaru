<?php if(! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-plus" style="color:green"> </i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-plus"></i> <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border"></div>
          <div class="box-body">
            <!-- <form action="<?= base_url('data/prosesjenispelatihan'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?> -->
              <?php echo form_open_multipart(); ?>
              <div class="row">
                <div class="col-sm-12">
                  
                  <div class="form-group">
                    <label>Nama Jenis Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Masukkan nama jenis pelatihan"></i></label>
                    <input type="text" name="nama_jenis_pelatihan" class="form-control" required placeholder="Contoh: Pelatihan Jarak Jauh">
                  </div>

                  <div class="pull-right">
                    <input type="hidden" name="tambah" value="tambah">
                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                    <a href="<?= base_url('data/jenispelatihan'); ?>" class="btn btn-danger btn-md">Kembali</a>
                  </div>
                </div>                

            <!-- </form> -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
// Initialize tooltips
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        placement: 'right'
    }); 
});
</script>