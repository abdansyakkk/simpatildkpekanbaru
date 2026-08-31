<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="modalTambahDokumen" tabindex="-1" role="dialog" aria-labelledby="modalTambahDokumenLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('data/prosesdokumen'); ?>" method="POST">
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTambahDokumenLabel">
            <i class="fa fa-plus" style="color:white"></i> <?= $title_web; ?>
          </h4>
        </div>
        <div class="modal-body">
          
          <div class="form-group">
            <label for="nama_dokumen">Nama Dokumen</label>
            <input type="text" class="form-control" name="nama_dokumen" id="nama_dokumen" placeholder="Contoh: Surat Izin, SK, dll" required>
          </div>

          <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
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
