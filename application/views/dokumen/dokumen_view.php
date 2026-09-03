<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-file-text" style="color:green"> </i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-file"></i>&nbsp; <?= $title_web; ?></li>
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
            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
            <!-- Trigger Button -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahDokumen">
              <i class="fa fa-plus"></i> Tambah Dokumen
            </button>
            <?php } ?>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped" width="100%">
                <thead>
                  <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Dokumen</th>
                    <th>Deskripsi</th>
                    <th class="text-right" style="width: 20%;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($dokumen->result_array() as $isi) { ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= htmlentities($isi['nama_dokumen']); ?></td>
                      <td><?= htmlentities($isi['deskripsi']); ?></td>
                      <td class="text-right">
                        <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                          <!-- Tombol Edit yang memicu modal dengan ID unik -->
                          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditDokumen<?= $isi['id_dokumen']; ?>" title="Edit Dokumen">
                            <i class="fa fa-edit"></i>
                          </button>
                          <a href="<?= base_url('data/dokumendetail/' . $isi['id_dokumen']); ?>" class="btn btn-primary btn-sm" title="Lihat Detail">
                            <i class="fa fa-sign-in"></i> Detail
                          </a>
                          <a href="<?= base_url('data/prosesdokumen?id_dokumen=' . $isi['id_dokumen']); ?>" onclick="return confirm('Anda yakin ingin menghapus dokumen ini?');" class="btn btn-danger btn-sm" title="Hapus Dokumen">
                            <i class="fa fa-trash"></i>
                          </a>
                        <?php } else { ?>
                          <a href="<?= base_url('data/dokumendetail/' . $isi['id_dokumen']); ?>" class="btn btn-primary btn-sm" title="Lihat Detail">
                            <i class="fa fa-sign-in"></i> Detail
                          </a>
                        <?php } ?>
                      </td>
                    </tr>
                    <!-- Modal Edit Dokumen -->
                    <div class="modal fade" id="modalEditDokumen<?= $isi['id_dokumen']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditDokumenLabel<?= $isi['id_dokumen']; ?>">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="<?= base_url('data/prosesdokumen'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
                          <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="modalEditDokumenLabel<?= $isi['id_dokumen']; ?>">
                              <i class="fa fa-edit" style="color:white;"></i> Edit Dokumen
                            </h4>
                          </div>

                          <div class="modal-body">
                            <div class="form-group">
                              <label for="nama_dokumen_<?= $isi['id_dokumen']; ?>">
                                Nama Dokumen <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Nama resmi dokumen"></i>
                              </label>
                              <input type="text" name="nama_dokumen" class="form-control" id="nama_dokumen_<?= $isi['id_dokumen']; ?>" value="<?= htmlentities($isi['nama_dokumen']); ?>" required>
                            </div>

                            <div class="form-group">
                              <label for="deskripsi_<?= $isi['id_dokumen']; ?>">
                                Deskripsi <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Detail informasi tentang dokumen"></i>
                              </label>
                              <textarea name="deskripsi" class="form-control" id="deskripsi_<?= $isi['id_dokumen']; ?>" rows="4"><?= htmlentities($isi['deskripsi']); ?></textarea>
                            </div>

                            <input type="hidden" name="edit" value="<?= $isi['id_dokumen']; ?>">
                          </div>

                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
</div>

                  <?php $no++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<!-- Modal Tambah Dokumen (with tooltips) -->
<div class="modal fade" id="modalTambahDokumen" tabindex="-1" role="dialog" aria-labelledby="modalTambahDokumenLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('data/prosesdokumen'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTambahDokumenLabel">
            <i class="fa fa-plus" style="color:white"></i> <?= $title_web; ?>
          </h4>
        </div>
        <div class="modal-body">
          
          <div class="form-group">
            <label for="nama_dokumen">
              Nama Dokumen <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Masukkan nama dokumen (contoh: Surat Izin, SK, Nota Dinas)"></i>
            </label>
            <input type="text" class="form-control" name="nama_dokumen" id="nama_dokumen" placeholder="Contoh: Surat Izin, SK, dll" required>
          </div>

          <div class="form-group">
            <label for="deskripsi">
              Deskripsi <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Penjelasan singkat tentang dokumen (maksimal 255 karakter)"></i>
            </label>
            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="4" placeholder="Deskripsi singkat tentang dokumen..."></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <input type="hidden" name="tambah" value="tambah">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
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
    $('#modalTambahDokumen').on('shown.bs.modal', initTooltips);
    
    // Initialize for all edit modals
    $('[id^="modalEditDokumen"]').on('shown.bs.modal', initTooltips);
    
    // Initialize tooltips on page load
    initTooltips();
});
</script>

