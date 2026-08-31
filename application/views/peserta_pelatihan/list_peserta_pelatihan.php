<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-users" style="color:green"> </i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li><a href="<?php echo base_url('data'); ?>"><i class="fa fa-folder"></i>&nbsp; Pelatihan</a></li>
      <li class="active"><i class="fa fa-user"></i>&nbsp; <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <?php if (!empty($this->session->flashdata())) {
      echo $this->session->flashdata('pesan');
    } ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Pelatihan: <?= htmlentities($pelatihan->nama_pelatihan); ?></h3>
            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
            <div class="box-tools pull-right">
              <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPeserta">
                  <i class="fa fa-plus"> </i> Tambah Peserta
              </button>
              <button class="btn btn-success" data-toggle="modal" data-target="#modalImportExcel">
                  <i class="fa fa-upload"> </i> Import Excel
              </button>
            </div>
            <?php } ?>
          </div>

          <div class="box-body">
            <br/>
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Peserta</th>
                    <th>Jenis Kelamin</th>
                    <th>NIP</th>
                    <th>Pangkat/Gol</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($peserta_pelatihan as $peserta) { ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= htmlentities($peserta->nama_peserta); ?></td>
                      <td><?= $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                      <td><?= htmlentities($peserta->nip); ?></td>
                      <td><?= htmlentities($peserta->pangkatgol); ?></td>
                      <td><?= htmlentities($peserta->jabatan); ?></td>
                      <td><?= htmlentities($peserta->unit_kerja); ?></td>
                      <td><?= date('d-m-Y', strtotime($peserta->created_at)); ?></td>
                      <td>
                        <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                            <!-- Tombol Edit -->
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditPeserta<?= $peserta->id_peserta; ?>">
                            <i class="fa fa-edit"></i> Edit
                            </button>
                          <a href="<?= base_url('data/prosespesertapelatihan?id_peserta=' . $peserta->id_peserta . '&id_pelatihan=' . $id_pelatihan); ?>" onclick="return confirm('Yakin ingin menghapus peserta ini?');">
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
                          </a>
                        <?php } ?>
                      </td>
                    </tr>
                    
                    <!-- Modal Edit Peserta -->
                    <div class="modal fade" id="modalEditPeserta<?= $peserta->id_peserta; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditPesertaLabel<?= $peserta->id_peserta; ?>">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <form action="<?= base_url('data/prosespesertapelatihan'); ?>" method="POST">
                            <div class="modal-header bg-green">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title" id="modalEditPesertaLabel<?= $peserta->id_peserta; ?>">
                                <i class="fa fa-edit" style="color:white;"></i> Edit Data Peserta
                              </h4>
                            </div>

                            <div class="modal-body">
                              <input type="hidden" name="edit" value="<?= $peserta->id_peserta; ?>">
                              <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">

                              <div class="form-group">
                                <label for="nama_peserta_edit_<?= $peserta->id_peserta; ?>">Nama Peserta</label>
                                <input type="text" name="nama_peserta" id="nama_peserta_edit_<?= $peserta->id_peserta; ?>" class="form-control" value="<?= htmlentities($peserta->nama_peserta); ?>" required>
                              </div>
                              
                              <div class="form-group">
                                <label for="jenis_kelamin_edit_<?= $peserta->id_peserta; ?>">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin_edit_<?= $peserta->id_peserta; ?>" class="form-control" required>
                                  <option value="L" <?= $peserta->jenis_kelamin == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                                  <option value="P" <?= $peserta->jenis_kelamin == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                              </div>
                              
                              <div class="form-group">
                                <label for="nip_edit_<?= $peserta->id_peserta; ?>">NIP</label>
                                <input type="text" name="nip" id="nip_edit_<?= $peserta->id_peserta; ?>" class="form-control" value="<?= htmlentities($peserta->nip); ?>" required>
                              </div>
                              
                              <div class="form-group">
                                <label for="pangkatgol_edit_<?= $peserta->id_peserta; ?>">Pangkat/Golongan</label>
                                <input type="text" name="pangkatgol" id="pangkatgol_edit_<?= $peserta->id_peserta; ?>" class="form-control" value="<?= htmlentities($peserta->pangkatgol); ?>" required>
                              </div>
                              
                              <div class="form-group">
                                <label for="jabatan_edit_<?= $peserta->id_peserta; ?>">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan_edit_<?= $peserta->id_peserta; ?>" class="form-control" value="<?= htmlentities($peserta->jabatan); ?>" required>
                              </div>
                              
                              <div class="form-group">
                                <label for="unit_kerja_edit_<?= $peserta->id_peserta; ?>">Unit Kerja</label>
                                <input type="text" name="unit_kerja" id="unit_kerja_edit_<?= $peserta->id_peserta; ?>" class="form-control" value="<?= htmlentities($peserta->unit_kerja); ?>" required>
                              </div>
                            </div>

                            <div class="modal-footer">
                              <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                  <?php $no++; } ?>
                </tbody>
              </table>
            </div> <!-- /.table-responsive -->
          </div> <!-- /.box-body -->
        </div> <!-- /.box -->
      </div> <!-- /.col -->
    </div> <!-- /.row -->
  </section>
</div>

<!-- Modal Tambah Peserta -->
<div class="modal fade" id="modalTambahPeserta" tabindex="-1" role="dialog" aria-labelledby="modalTambahPesertaLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('data/prosespesertapelatihan'); ?>" method="POST">
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTambahPesertaLabel">
            <i class="fa fa-plus" style="color:white"></i> Tambah Peserta Pelatihan
          </h4>
        </div>

        <div class="modal-body">
          <input type="hidden" name="tambah" value="tambah">
          <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">

          <div class="form-group">
            <label for="nama_peserta">Nama Peserta</label>
            <input type="text" name="nama_peserta" id="nama_peserta" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="nip">NIP</label>
            <input type="text" name="nip" id="nip" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="pangkatgol">Pangkat/Golongan</label>
            <input type="text" name="pangkatgol" id="pangkatgol" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="unit_kerja">Unit Kerja</label>
            <input type="text" name="unit_kerja" id="unit_kerja" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" role="dialog" aria-labelledby="modalImportExcelLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('data/prosespesertapelatihan'); ?>" method="POST" enctype="multipart/form-data">
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalImportExcelLabel">
            <i class="fa fa-upload" style="color:white"></i> Import Data Peserta dari Excel
          </h4>
        </div>

        <div class="modal-body">
          <input type="hidden" name="import_excel" value="import">
          <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
          
          <div class="alert alert-info">
            <h4><i class="fa fa-info-circle"></i> Format File Excel</h4>
            <p>File Excel harus memiliki format berikut (mulai dari baris 1):</p>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Kolom A</th>
                  <th>Kolom B</th>
                  <th>Kolom C</th>
                  <th>Kolom D</th>
                  <th>Kolom E</th>
                  <th>Kolom F</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Nama Peserta</td>
                  <td>Jenis Kelamin (L/P)</td>
                  <td>NIP</td>
                  <td>Pangkat/Gol</td>
                  <td>Jabatan</td>
                  <td>Unit Kerja</td>
                </tr>
              </tbody>
            </table>
            <p><strong>Catatan:</strong> Baris pertama adalah header, data dimulai dari baris ke-2.</p>
          </div>

          <div class="form-group">
            <label for="file_excel">File Excel</label>
            <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls" required>
            <p class="help-block">Format file: .xlsx atau .xls (maks. 2MB)</p>
          </div>
          
          <div class="form-group">
            <a href="<?= base_url('assets/template_import_peserta.xlsx'); ?>" class="btn btn-success btn-sm">
              <i class="fa fa-download"></i> Download Template
            </a>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Initialize tooltips for all modals
$(document).ready(function(){
    // Function to initialize tooltips
    function initTooltips() {
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover',
            placement: 'right',
            container: 'body'
        });
    }
    
    // Initialize for tambah modal
    $('#modalTambahPeserta').on('shown.bs.modal', initTooltips);
    $('#modalImportExcel').on('shown.bs.modal', initTooltips);
    
    // Initialize for all edit modals
    $('[id^="modalEditPeserta"]').on('shown.bs.modal', initTooltips);
    
    // Initialize tooltips on page load
    initTooltips();
    
    // Initialize DataTable
    $('#example1').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
      },
      "responsive": true,
      "autoWidth": false
    });
});
</script>