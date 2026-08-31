<?php
$id_pelatihan    = isset($id_pelatihan) ? $id_pelatihan : 0;
$materi_pengajar = isset($materi_pengajar) ? $materi_pengajar : [];
$pegawai         = isset($pegawai) ? $pegawai : [];
$open_modal      = $this->session->flashdata('open_modal'); // ['type'=>'topik'|'grup','agenda_id'=>int]

// --- helper kecil untuk select pegawai (dipakai berkali-kali)
function options_pegawai($pegawai, $selected = null) {
  $html = '<option value="">-- Pilih --</option>';
  foreach ($pegawai as $p) {
    $sel = ($selected && (int)$selected === (int)$p->id_pegawai) ? ' selected' : '';
    $html .= '<option value="'.(int)$p->id_pegawai.'"'.$sel.'>'.htmlentities($p->nama).'</option>';
  }
  return $html;
}

// --- siapkan buffer modal agar tidak berada di dalam <table>
$MODALS = [];
?>
<style>
/* ===== Tampilan ringkas & stabil ===== */
.table-materi thead th, .table-materi tbody td { vertical-align: middle !important; }
.table-materi .col-no      { width:60px; text-align:center; }
.table-materi .col-agenda  { min-width:320px; }
.table-materi .col-teacher { min-width:220px; }
.table-materi .col-metric  { width:110px; text-align:center; }
.table-materi .col-actions { min-width:360px; white-space:nowrap; }
.badge-pill { display:inline-block; min-width:26px; padding:5px 8px; border-radius:999px; color:#fff; font-weight:600; }
.badge-blue   { background:#3c8dbc; }  /* AdminLTE primary */
.badge-green  { background:#00a65a; }
.badge-purple { background:#605ca8; }
.btn-group-sm > .btn { margin-right:4px; }
.btn-group-sm > .btn:last-child { margin-right:0; }
.modal .table th, .modal .table td { vertical-align: middle !important; }
.modal .form-inline .form-group { margin-right:10px; margin-bottom:8px; }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-book" style="color:green"></i> <?= $title_web; ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?= base_url('data/kegiatanpelatihan'); ?>"><i class="fa fa-calendar"></i> Kegiatan Pelatihan</a></li>
      <li class="active"><i class="fa fa-file-text"></i> <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <?php if (!empty($this->session->flashdata())) echo $this->session->flashdata('pesan'); ?>

    <div class="box box-primary">
      <div class="box-header with-border">
        <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahAgenda">
            <i class="fa fa-plus"></i> Tambah Agenda
          </button>
        <?php } ?>
      </div>

      <div class="box-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped table-materi" width="100%">
            <thead>
              <tr>
                <th class="col-no">No</th>
                <th class="col-agenda">Agenda</th>
                <th class="col-teacher">Main Teacher</th>
                <th class="col-metric">#Topik</th>
                <th class="col-metric">Total JP</th>
                <th class="col-metric">#Pengajar</th>
                <th class="col-actions">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php $no=1; foreach ($materi_pengajar as $ag): ?>
              <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td><?= htmlentities($ag->agenda_title); ?></td>
                <td><?= htmlentities($ag->main_teacher_name ?? '—'); ?></td>
                <td><span class="badge-pill badge-blue"><?= (int)($ag->jumlah_topik ?? 0); ?></span></td>
                <td><span class="badge-pill badge-green"><?= (int)($ag->total_jp ?? 0); ?></span></td>
                <td><span class="badge-pill badge-purple"><?= (int)($ag->jumlah_pengajar_kelompok ?? 0); ?></span></td>
                <td>
                  <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                      <button class="btn btn-success" data-toggle="modal" data-target="#modalEditAgenda<?= $ag->agenda_id; ?>"><i class="fa fa-edit"></i> Edit</button>
                      <button class="btn btn-info"    data-toggle="modal" data-target="#modalTopik<?= $ag->agenda_id; ?>"><i class="fa fa-list"></i> Topik</button>
                      <button class="btn btn-warning" data-toggle="modal" data-target="#modalGrup<?= $ag->agenda_id; ?>"><i class="fa fa-users"></i> Grup</button>
                      <a class="btn btn-danger"
                         href="<?= base_url('data/prosesmateripengajar?delete_agenda='.$ag->agenda_id.'&id_pelatihan='.$id_pelatihan); ?>"
                         onclick="return confirm('Hapus agenda beserta Topik & Grup?');"><i class="fa fa-trash"></i></a>
                    </div>
                  <?php } else { echo '<em>-</em>'; } ?>
                </td>
              </tr>
              <?php
              /* =========================
                 KUMPULKAN MODAL AGENDA
                 ========================= */
              ob_start(); ?>
              <!-- Edit Agenda -->
              <div class="modal fade" id="modalEditAgenda<?= $ag->agenda_id; ?>" tabindex="-1" role="dialog" aria-labelledby="lblEditAgenda<?= $ag->agenda_id; ?>">
                <div class="modal-dialog" role="document"><div class="modal-content">
                  <form action="<?= base_url('data/prosesmateripengajar'); ?>" method="POST">
                    <div class="modal-header bg-green">
                      <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                      <h4 class="modal-title" id="lblEditAgenda<?= $ag->agenda_id; ?>"><i class="fa fa-edit" style="color:#fff"></i> Edit Agenda</h4>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="edit_agenda" value="<?= $ag->agenda_id; ?>">
                      <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                      <div class="form-group">
                        <label>Judul Agenda</label>
                        <input type="text" name="agenda_title" class="form-control" value="<?= htmlentities($ag->agenda_title); ?>" required>
                      </div>
                      <div class="form-group">
                        <label>Main Teacher</label>
                        <select name="main_teacher_id" class="form-control"><?= options_pegawai($pegawai, $ag->main_teacher_id); ?></select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                  </form>
                </div></div>
              </div>

              <!-- Kelola Topik -->
              <div class="modal fade" id="modalTopik<?= $ag->agenda_id; ?>" tabindex="-1" role="dialog" aria-labelledby="lblTopik<?= $ag->agenda_id; ?>">
                <div class="modal-dialog modal-lg" role="document"><div class="modal-content">
                  <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title" id="lblTopik<?= $ag->agenda_id; ?>"><i class="fa fa-list" style="color:#fff"></i> Topik — <?= htmlentities($ag->agenda_title); ?></h4>
                  </div>
                  <div class="modal-body">
                    <form action="<?= base_url('data/prosesmateripengajar'); ?>" method="POST" class="form-inline" style="margin-bottom:10px;">
                      <input type="hidden" name="tambah_topik" value="1">
                      <input type="hidden" name="agenda_id" value="<?= $ag->agenda_id; ?>">
                      <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                      <div class="form-group"><input type="number" min="1" name="topic_no" class="form-control" placeholder="No" required></div>
                      <div class="form-group" style="min-width:320px;"><input type="text" name="topic_title" class="form-control" style="width:100%;" placeholder="Judul Topik" required></div>
                      <div class="form-group"><input type="number" min="0" name="jp_async" class="form-control" placeholder="JP Async" value="0"></div>
                      <div class="form-group"><input type="number" min="0" name="jp_sync"  class="form-control" placeholder="JP Sync"  value="0"></div>
                      <button class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
                    </form>

                    <div class="table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr><th style="width:60px; text-align:center;">No</th><th style="width:90px;">Topic No</th><th>Judul Topik</th><th style="width:120px; text-align:center;">JP Async</th><th style="width:120px; text-align:center;">JP Sync</th><th style="width:180px;">Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php
                          $topiks = $this->db->order_by('topic_no','ASC')->get_where('tbl_topik', ['agenda_id'=>$ag->agenda_id])->result();
                          if ($topiks) { $i=1; foreach ($topiks as $t) { ?>
                          <tr>
                            <td class="text-center"><?= $i++; ?></td>
                            <td><?= (int)$t->topic_no; ?></td>
                            <td><?= htmlentities($t->topic_title); ?></td>
                            <td class="text-center"><?= (int)$t->jp_async; ?></td>
                            <td class="text-center"><?= (int)$t->jp_sync; ?></td>
                            <td class="text-nowrap">
                              <div class="btn-group btn-group-sm">
                                <button class="btn btn-success" data-toggle="modal" data-target="#modalEditTopik<?= $t->topic_id; ?>"><i class="fa fa-edit"></i> Edit</button>
                                <a class="btn btn-danger" href="<?= base_url('data/prosesmateripengajar?delete_topik='.$t->topic_id.'&agenda_id='.$ag->agenda_id.'&id_pelatihan='.$id_pelatihan); ?>" onclick="return confirm('Hapus topik ini?');"><i class="fa fa-trash"></i></a>
                              </div>
                            </td>
                          </tr>
                          <!-- Edit Topik -->
                          <div class="modal fade" id="modalEditTopik<?= $t->topic_id; ?>" tabindex="-1" role="dialog" aria-labelledby="lblEditTopik<?= $t->topic_id; ?>">
                            <div class="modal-dialog" role="document"><div class="modal-content">
                              <form action="<?= base_url('data/prosesmateripengajar'); ?>" method="POST">
                                <div class="modal-header bg-green">
                                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                  <h4 class="modal-title" id="lblEditTopik<?= $t->topic_id; ?>"><i class="fa fa-edit" style="color:#fff"></i> Edit Topik</h4>
                                </div>
                                <div class="modal-body">
                                  <input type="hidden" name="edit_topik" value="<?= $t->topic_id; ?>">
                                  <input type="hidden" name="agenda_id" value="<?= $ag->agenda_id; ?>">
                                  <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                                  <div class="form-group"><label>Topic No</label><input type="number" min="1" name="topic_no" class="form-control" value="<?= (int)$t->topic_no; ?>" required></div>
                                  <div class="form-group"><label>Judul Topik</label><input type="text" name="topic_title" class="form-control" value="<?= htmlentities($t->topic_title); ?>" required></div>
                                  <div class="form-group"><label>JP Async</label><input type="number" min="0" name="jp_async" class="form-control" value="<?= (int)$t->jp_async; ?>"></div>
                                  <div class="form-group"><label>JP Sync</label><input type="number" min="0" name="jp_sync" class="form-control" value="<?= (int)$t->jp_sync; ?>"></div>
                                </div>
                                <div class="modal-footer">
                                  <button class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                </div>
                              </form>
                            </div></div>
                          </div>
                          <?php } } else { echo '<tr><td colspan="6" class="text-center">Belum ada topik.</td></tr>'; } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
                </div></div>
              </div>

              <!-- Kelola Grup Pengajar -->
              <div class="modal fade" id="modalGrup<?= $ag->agenda_id; ?>" tabindex="-1" role="dialog" aria-labelledby="lblGrup<?= $ag->agenda_id; ?>">
                <div class="modal-dialog modal-lg" role="document"><div class="modal-content">
                  <div class="modal-header bg-warning">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title" id="lblGrup<?= $ag->agenda_id; ?>"><i class="fa fa-users" style="color:#fff"></i> Grup Pengajar — <?= htmlentities($ag->agenda_title); ?></h4>
                  </div>
                  <div class="modal-body">
                    <form action="<?= base_url('data/prosesmateripengajar'); ?>" method="POST" class="form-inline" style="margin-bottom:10px;">
                      <input type="hidden" name="tambah_grup" value="1">
                      <input type="hidden" name="agenda_id" value="<?= $ag->agenda_id; ?>">
                      <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                      <div class="form-group"><input type="number" min="1" name="group_no" class="form-control" placeholder="Grup No" required></div>
                      <div class="form-group">
                        <select name="teacher_id" class="form-control" required><?= options_pegawai($pegawai); ?></select>
                      </div>
                      <button class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
                    </form>

                    <div class="table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead><tr><th style="width:60px; text-align:center;">No</th><th style="width:110px;">Grup No</th><th>Teacher</th><th style="width:180px;">Aksi</th></tr></thead>
                        <tbody>
                        <?php
                          $grups = $this->db->select('ga.*, p.nama AS teacher_name')
                                            ->from('tbl_grup_agenda ga')->join('tbl_pegawai p','p.id_pegawai=ga.teacher_id','left')
                                            ->where('ga.agenda_id', $ag->agenda_id)->order_by('ga.group_no','ASC')->get()->result();
                          if ($grups) { $j=1; foreach ($grups as $g) { ?>
                          <tr>
                            <td class="text-center"><?= $j++; ?></td>
                            <td><?= (int)$g->group_no; ?></td>
                            <td><?= htmlentities($g->teacher_name ?? '—'); ?></td>
                            <td class="text-nowrap">
                              <div class="btn-group btn-group-sm">
                                <button class="btn btn-success" data-toggle="modal" data-target="#modalEditGrup<?= $g->agenda_group_id; ?>"><i class="fa fa-edit"></i> Edit</button>
                                <a class="btn btn-danger" href="<?= base_url('data/prosesmateripengajar?delete_grup_agenda='.$g->agenda_group_id.'&agenda_id='.$ag->agenda_id.'&id_pelatihan='.$id_pelatihan); ?>" onclick="return confirm('Hapus data grup ini?');"><i class="fa fa-trash"></i></a>
                              </div>
                            </td>
                          </tr>
                          <!-- Edit Grup -->
                          <div class="modal fade" id="modalEditGrup<?= $g->agenda_group_id; ?>" tabindex="-1" role="dialog" aria-labelledby="lblEditGrup<?= $g->agenda_group_id; ?>">
                            <div class="modal-dialog" role="document"><div class="modal-content">
                              <form action="<?= base_url('data/prosesmateripengajar'); ?>" method="POST">
                                <div class="modal-header bg-green">
                                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                  <h4 class="modal-title" id="lblEditGrup<?= $g->agenda_group_id; ?>"><i class="fa fa-edit" style="color:#fff"></i> Edit Grup Pengajar</h4>
                                </div>
                                <div class="modal-body">
                                  <input type="hidden" name="edit_grup" value="<?= $g->agenda_group_id; ?>">
                                  <input type="hidden" name="agenda_id" value="<?= $ag->agenda_id; ?>">
                                  <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                                  <div class="form-group"><label>Grup No</label><input type="number" min="1" name="group_no" class="form-control" value="<?= (int)$g->group_no; ?>" required></div>
                                  <div class="form-group"><label>Teacher</label><select name="teacher_id" class="form-control" required><?= options_pegawai($pegawai, $g->teacher_id); ?></select></div>
                                </div>
                                <div class="modal-footer">
                                  <button class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                </div>
                              </form>
                            </div></div>
                          </div>
                          <?php } } else { echo '<tr><td colspan="4" class="text-center">Belum ada data grup pengajar.</td></tr>'; } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
                </div></div>
              </div>
              <?php
              $MODALS[] = ob_get_clean();
              ?>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Tambah Agenda (global, di luar table) -->
<div class="modal fade" id="modalTambahAgenda" tabindex="-1" role="dialog" aria-labelledby="lblTambahAgenda">
  <div class="modal-dialog" role="document"><div class="modal-content">
    <form action="<?= base_url('data/prosesmateripengajar'); ?>" method="POST">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title" id="lblTambahAgenda"><i class="fa fa-plus" style="color:#fff"></i> Tambah Agenda</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="tambah_agenda" value="1">
        <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
        <div class="form-group"><label>Judul Agenda</label><input type="text" name="agenda_title" class="form-control" required></div>
        <div class="form-group"><label>Main Teacher</label><select name="main_teacher_id" class="form-control"><?= options_pegawai($pegawai); ?></select></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </form>
  </div></div>
</div>

<!-- CETAK SEMUA MODAL TERKUMPUL DI SINI -->
<?php
  if (!empty($MODALS)) echo implode("\n", $MODALS);
?>

<script>
$(function(){
  // tooltips (kalau dibutuhkan)
  function initTT(){ $('[data-toggle="tooltip"]').tooltip({container:'body'}); }
  $('#modalTambahAgenda').on('shown.bs.modal', initTT);
  $(document).on('shown.bs.modal', '.modal', initTT);

  // Auto buka modal setelah aksi (flashdata)
  <?php if (!empty($open_modal) && is_array($open_modal)):
        $type = $open_modal['type'] ?? '';
        $aid  = (int)($open_modal['agenda_id'] ?? 0);
        if ($type && $aid): ?>
    var modalId = (<?= json_encode($type) ?> === 'topik') ? '#modalTopik<?= $aid ?>' : '#modalGrup<?= $aid ?>';
    $(modalId).modal('show');
  <?php endif; endif; ?>
});
</script>
