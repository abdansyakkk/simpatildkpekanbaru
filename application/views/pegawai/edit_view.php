<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-user"></i> <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="box box-primary">
          <div class="box-header with-border"></div>
          <div class="box-body">
            <form action="<?= base_url('data/prosespegawai'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
              
              <div class="form-group">
                <label>Nama Pegawai 
                  <i class="fa fa-info-circle text-blue" data-toggle="tooltip" data-placement="right" 
                     title="Masukkan nama lengkap pegawai sesuai dengan identitas resmi"></i>
                </label>
                <input type="text" name="nama" class="form-control" required value="<?= $pegawai->nama; ?>">
              </div>

              <div class="form-group">
               <label>NIP
                  <i class="fa fa-info-circle text-blue" data-toggle="tooltip" data-placement="right" 
                     title="Nomor Induk Pegawai (NIP) 18 digit. Bisa dikosongkan jika tidak tersedia"></i>
                </label>
                <input type="text" name="nip" class="form-control" value="<?= $pegawai->NIP; ?>">
              </div>

              <div class="form-group">
                  <label>Jabatan
                  <i class="fa fa-info-circle text-blue" data-toggle="tooltip" data-placement="right" 
                     title="Pilih jabatan pegawai dari daftar yang tersedia"></i>
                </label>
                <select name="jabatan" class="form-control select2" required>
                  <option disabled selected value>-- Pilih Jabatan --</option>
                  <?php foreach ($roles as $role) { ?>
                    <option value="<?= $role['id_role']; ?>" <?= ($pegawai->jabatan == $role['id_role']) ? 'selected' : ''; ?>>
                      <?= $role['nama_role']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group">
           <label>Asal Satuan Kerja
                  <i class="fa fa-info-circle text-blue" data-toggle="tooltip" data-placement="right" 
                     title="Unit kerja asal pegawai. Contoh: Pusbangkom MK MB, Loka Diklat Keagamaan Pekanbaru"></i>
                </label>
                <input type="text" name="asal_satker" class="form-control" value="<?= $pegawai->asal_satker; ?>">
              </div>

              <div class="pull-right">
                <input type="hidden" name="edit" value="<?= $pegawai->id_pegawai; ?>">
                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                <a href="<?= base_url('data/pegawai'); ?>" class="btn btn-danger btn-md">Kembali</a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
// Initialize tooltips
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>