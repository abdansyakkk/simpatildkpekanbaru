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
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border"></div>
          <div class="box-body">
            <form action="<?= base_url('data/prosespelatihan'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
              <div class="row">
                <div class="col-sm-6">

                  <div class="form-group">
                    <label>Nama Kegiatan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Masukkan nama kegiatan pelatihan secara lengkap"></i></label>
                    <input type="text" name="nama_kegiatan" class="form-control" required value="<?= $pelatihan->nama_kegiatan; ?>">
                  </div>

                  <div class="form-group">
                    <label>Nama Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Nama resmi pelatihan sesuai dokumen"></i></label>
                    <input type="text" name="nama_pelatihan" class="form-control" required value="<?= $pelatihan->nama_pelatihan; ?>">
                  </div>
                  
                  <div class="form-group">
                      <label>Jenis Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jenis pelatihan tidak dapat diubah"></i></label>
                    <input type="text" class="form-control" value="<?= $jenis_pelatihan->nama_jenis_pelatihan; ?>" readonly>
                    <input type="hidden" name="id_jenis_pelatihan" value="<?= $pelatihan->id_jenis_pelatihan; ?>">
                </div>

                  <div class="form-group">
                    <label>Provinsi <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Provinsi tempat pelatihan dilaksanakan"></i></label>
                    <input type="text" name="provinsi" class="form-control" required value="<?= $pelatihan->provinsi; ?>">
                  </div>

                  <div class="form-group">
                    <label>Kabupaten/Kota <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Kabupaten/Kota tempat pelatihan"></i></label>
                    <input type="text" name="kab_kota" class="form-control" value="<?= $pelatihan->kab_kota; ?>">
                  </div>

                  <div class="form-group">
                    <label>Tempat <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Nama lokasi pelatihan"></i></label>
                    <input type="text" name="tempat" class="form-control" required value="<?= $pelatihan->tempat; ?>">
                  </div>

                  <div class="form-group">
                    <label>Tanggal Mulai Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal pertama kegiatan pelatihan"></i></label>
                    <input type="date" name="tanggal_mulai" class="form-control" required value="<?= $pelatihan->tanggal_mulai_pelatihan; ?>">
                  </div>

                  <div class="form-group">
                    <label>Tanggal Selesai Pelatihan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal terakhir kegiatan pelatihan"></i></label>
                    <input type="date" name="tanggal_selesai" class="form-control" required value="<?= $pelatihan->tanggal_selesai_pelatihan; ?>">
                  </div>

                  <div class="form-group">
                    <label>Bulan TTD Laporan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Bulan yang tercantum pada dokumen laporan"></i></label>
                    <input type="text" name="bulan_ttd" class="form-control" required value="<?= $pelatihan->bulan_ttd_lap; ?>">
                  </div>

                  <div class="form-group">
                    <label>Tahun <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tahun pelaksanaan pelatihan"></i></label>
                    <input type="number" name="tahun" class="form-control" required value="<?= $pelatihan->tahun; ?>">
                  </div>

                </div>

                <div class="col-sm-6">

                  <div class="form-group">
                    <label>Tanggal Pembukaan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal acara pembukaan pelatihan"></i></label>
                    <input type="date" name="hari_tanggal_pembukaan" class="form-control" value="<?= $pelatihan->hari_tanggal_pembukaan; ?>">
                  </div>

                  <div class="form-group">
                    <label>Waktu Pembukaan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jam acara pembukaan (format 24 jam)"></i></label>
                    <input type="time" name="waktu_pembukaan" class="form-control" value="<?= $pelatihan->waktu_pembukaan; ?>">
                  </div>

                  <div class="form-group">
                    <label>Pejabat Pembuka <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pejabat yang membuka acara pelatihan"></i></label>
                    <select class="form-control select2" name="id_pejabat_pembuka" required>
                      <option disabled selected value> -- Pilih Pegawai -- </option>
                      <?php foreach($pegawais as $isi){ ?>
                        <option value="<?= $isi['id_pegawai']; ?>" <?= ($isi['id_pegawai'] == $pelatihan->id_pejabat_pembuka) ? 'selected' : ''; ?>>
                          <?= $isi['nama']; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Jabatan Pejabat Pembuka <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jabatan pejabat pembuka acara"></i></label>
                    <select class="form-control select2" name="id_role_pembuka" required>
                      <option disabled selected value> -- Pilih Jabatan -- </option>
                      <?php foreach($roles as $isi){ ?>
                        <option value="<?= $isi['id_role']; ?>" <?= ($isi['id_role'] == $pelatihan->id_role_pembuka) ? 'selected' : ''; ?>>
                          <?= $isi['nama_role']; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Tanggal Penutupan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Tanggal acara penutupan pelatihan"></i></label>
                    <input type="date" name="hari_tanggal_penutupan" class="form-control" value="<?= $pelatihan->hari_tanggal_penutupan; ?>">
                  </div>

                  <div class="form-group">
                    <label>Waktu Penutupan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jam acara penutupan (format 24 jam)"></i></label>
                    <input type="time" name="waktu_penutupan" class="form-control" value="<?= $pelatihan->waktu_penutupan; ?>">
                  </div>

                  <div class="form-group">
                    <label>Pejabat Penutup <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pejabat yang menutup acara pelatihan"></i></label>
                    <select class="form-control select2" name="id_pejabat_penutup" required>
                      <option disabled selected value> -- Pilih Pegawai -- </option>
                      <?php foreach($pegawais as $isi){ ?>
                        <option value="<?= $isi['id_pegawai']; ?>" <?= ($isi['id_pegawai'] == $pelatihan->id_pejabat_penutup) ? 'selected' : ''; ?>>
                          <?= $isi['nama']; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Jabatan Pejabat Penutup <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jabatan pejabat penutup acara"></i></label>
                    <select class="form-control select2" name="id_role_penutup" required>
                      <option disabled selected value> -- Pilih Jabatan -- </option>
                      <?php foreach($roles as $isi){ ?>
                        <option value="<?= $isi['id_role']; ?>" <?= ($isi['id_role'] == $pelatihan->id_role_penutup) ? 'selected' : ''; ?>>
                          <?= $isi['nama_role']; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                </div>
              </div>

              <div class="pull-right">
                <input type="hidden" name="edit" value="<?= $pelatihan->id_pelatihan; ?>">
                <button type="submit" class="btn btn-primary btn-md">Submit</button>
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
        placement: 'right',
        container: 'body'
    }); 
});
</script>