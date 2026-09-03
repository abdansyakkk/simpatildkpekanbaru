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
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border"></div>
          <div class="box-body">
            <form action="<?= base_url('data/prosespelatihan'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
              <input type="hidden" name="id_jenis_pelatihan" value="<?= $default_jenis ?>">
              <div class="row">
                <div class="col-sm-6">
                  
                  <div class="form-group">
                    <label>Nama Kegiatan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Masukkan nama kegiatan pelatihan secara lengkap"></i></label>
                    <input type="text" name="nama_kegiatan" class="form-control" required placeholder="Contoh: Pelatihan Literasi Digital">
                  </div>

                  <div class="form-group">
                    <label>Nama Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Nama resmi pelatihan sesuai dokumen"></i></label>
                    <input type="text" name="nama_pelatihan" class="form-control" required>
                  </div>
                  
                  <div class="form-group">
                      <label>Jenis Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jenis pelatihan sesuai dokumen"></i></label>
                      <?php if (!empty($default_jenis)): ?>
                          <?php 
                              $jenis = $this->db->get_where('tbl_jenis_pelatihan', ['id_jenis_pelatihan' => $default_jenis])->row();
                          ?>
                          <input type="text" class="form-control" value="<?= $jenis->nama_jenis_pelatihan ?>" readonly>
                          <!-- Tambahkan input hidden untuk mengirim nilai id_jenis_pelatihan -->
                          <input type="hidden" name="id_jenis_pelatihan" value="<?= $default_jenis ?>">
                      <?php else: ?>
                          <select name="id_jenis_pelatihan" class="form-control" required>
                              <option value="">- Pilih Jenis -</option>
                              <?php foreach($jenis_pelatihan_options as $jenis): ?>
                                  <option value="<?= $jenis->id_jenis_pelatihan ?>">
                                      <?= $jenis->nama_jenis_pelatihan ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      <?php endif; ?>
                  </div>

                  <div class="form-group">
                    <label>Provinsi <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Provinsi tempat pelatihan dilaksanakan"></i></label>
                    <input type="text" name="provinsi" class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label>Kabupaten/Kota <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Kabupaten/Kota tempat pelatihan"></i></label>
                    <input type="text" name="kab_kota" class="form-control">
                  </div>

                  <div class="form-group">
                    <label>Tempat <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Nama lokasi pelatihan"></i></label>
                    <input type="text" name="tempat" class="form-control" required>
                  </div>
                  
                  <?php if ($default_jenis == 2): ?>
                    <div class="form-group">
                      <label>Alamat <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Alamat lengkap tempat pelatihan"></i></label>
                      <input type="text" name="alamat" class="form-control" required>
                    </div>
                  <?php endif; ?>

                  <div class="form-group">
                    <label>Tanggal Mulai Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal pertama kegiatan pelatihan"></i></label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label>Tanggal Selesai Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal terakhir kegiatan pelatihan"></i></label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label>Bulan TTD Laporan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Bulan yang tercantum pada dokumen laporan"></i></label>
                    <input type="text" name="bulan_ttd" class="form-control" required placeholder="Contoh: Juli">
                  </div>                  
                </div>
                
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Tahun <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tahun pelaksanaan pelatihan"></i></label>
                    <input type="number" name="tahun" class="form-control" required placeholder="Contoh: 2025">
                  </div>

                  <div class="form-group">
                    <label>Tanggal Pembukaan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal acara pembukaan pelatihan"></i></label>
                    <input type="date" name="hari_tanggal_pembukaan" class="form-control">
                  </div>

                  <div class="form-group">
                    <label>Waktu Pembukaan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jam acara pembukaan (format 24 jam)"></i></label>
                    <input type="time" name="waktu_pembukaan" class="form-control">
                  </div>

                <!-- Pejabat Pembuka -->
                <div class="form-group">
                <label>Pejabat Pembuka <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pejabat yang membuka acara pelatihan"></i></label>
                <select class="form-control select2" required name="id_pejabat_pembuka">
                    <option disabled selected value> -- Pilih Pegawai -- </option>
                    <?php foreach($pegawais as $isi){ ?>
                        <option value="<?= $isi['id_pegawai']; ?>"><?= $isi['nama']; ?></option>
                    <?php } ?>
                </select>
                </div>

                <!-- Jabatan Pejabat Pembuka -->
                <div class="form-group">
                <label>Jabatan Pejabat Pembuka <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jabatan pejabat pembuka acara"></i></label>
                <select class="form-control select2" required name="id_role_pembuka">
                    <option disabled selected value> -- Pilih Jabatan -- </option>
                    <?php foreach($roles as $isi){ ?>
                        <option value="<?= $isi['id_role']; ?>"><?= $isi['nama_role']; ?></option>
                    <?php } ?>
                </select>
                </div>

                  <div class="form-group">
                    <label>Tanggal Penutupan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal acara penutupan pelatihan"></i></label>
                    <input type="date" name="hari_tanggal_penutupan" class="form-control">
                  </div>

                  <div class="form-group">
                    <label>Waktu Penutupan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jam acara penutupan (format 24 jam)"></i></label>
                    <input type="time" name="waktu_penutupan" class="form-control">
                  </div>

                <!-- Pejabat Penutup -->
                <div class="form-group">
                <label>Pejabat Penutup <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pejabat yang menutup acara pelatihan"></i></label>
                <select class="form-control select2" required name="id_pejabat_penutup">
                    <option disabled selected value> -- Pilih Pegawai -- </option>
                    <?php foreach($pegawais as $isi){ ?>
                        <option value="<?= $isi['id_pegawai']; ?>"><?= $isi['nama']; ?></option>
                    <?php } ?>
                </select>
                </div>

                <!-- Jabatan Pejabat Penutup -->
                <div class="form-group">
                <label>Jabatan Pejabat Penutup <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jabatan pejabat penutup acara"></i></label>
                <select class="form-control select2" required name="id_role_penutup">
                    <option disabled selected value> -- Pilih Jabatan -- </option>
                    <?php foreach($roles as $isi){ ?>
                        <option value="<?= $isi['id_role']; ?>"><?= $isi['nama_role']; ?></option>
                    <?php } ?>
                </select>
                </div>

                </div>
              </div>

              <div class="pull-right">
                <input type="hidden" name="tambah" value="tambah">
                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                <?php if($default_jenis == 1){ ?>
                  <a href="<?= base_url('data?jenis=PJJ'); ?>" class="btn btn-danger btn-md">
                    <i class="fa fa-arrow-left"></i> Kembali
                  </a>
                <?php }elseif($default_jenis == 2){ ?>
                  <a href="<?= base_url('data?jenis=PDWK'); ?>" class="btn btn-danger btn-md">
                    <i class="fa fa-arrow-left"></i> Kembali
                  </a>
                <?php }else{ ?>
                  <a href="<?= base_url('data?jenis=Latsar'); ?>" class="btn btn-danger btn-md">
                    <i class="fa fa-arrow-left"></i> Kembali
                  </a>
                <?php } ?>
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
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        placement: 'right'
    }); 
});
</script>