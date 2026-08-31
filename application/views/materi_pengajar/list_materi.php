<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
// Ambil flag untuk auto-open modal/accordion setelah aksi
$open_modal = $this->session->flashdata('open_modal');
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-book" style="color:green"></i> <?= isset($title_web) ? $title_web : 'Data Materi (Agenda & Topik)'; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-book"></i> <?= isset($title_web) ? $title_web : 'Data Materi (Agenda & Topik)'; ?></li>
    </ol>
  </section>

  <section class="content">
    <?php if (!empty($this->session->flashdata())) { echo $this->session->flashdata('pesan'); } ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahAgenda">
                <i class="fa fa-plus"></i> Tambah Agenda
              </button>
            <?php } ?>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped" width="100%">
                <thead>
                  <tr>
                    <th style="width:5%">No</th>
                    <th>Nama Agenda</th>
                    <th style="width:10%" class="text-center"># Topik</th>
                    <th style="width:12%" class="text-center">JP Sync</th>
                    <th style="width:12%" class="text-center">JP Async</th>
                    <th style="width:24%" class="text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach (($agenda ? $agenda->result_array() : []) as $ag) { ?>
                  <tr>
                    <td><?= $no; ?></td>
                    <td><?= htmlentities($ag['agenda_title']); ?></td>
                    <td class="text-center"><?= (int)$ag['total_topics']; ?></td>
                    <td class="text-center"><?= (int)$ag['total_jp_sync']; ?></td>
                    <td class="text-center"><?= (int)$ag['total_jp_async']; ?></td>
                    <td class="text-right">
                      <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#collapseTopik<?= (int)$ag['agenda_id']; ?>">
                        <i class="fa fa-list"></i> Topik
                      </button>
                      <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditAgenda<?= (int)$ag['agenda_id']; ?>" title="Edit Agenda">
                          <i class="fa fa-edit"></i>
                        </button>
                        <a href="<?= base_url('data/prosesmateri?delete_agenda=' . (int)$ag['agenda_id']); ?>" onclick="return confirm('Hapus agenda ini beserta seluruh topik?');" class="btn btn-danger btn-sm" title="Hapus Agenda">
                          <i class="fa fa-trash"></i>
                        </a>
                      <?php } ?>
                    </td>
                  </tr>

                  <!-- ROW DETAIL: DAFTAR TOPIK PER AGENDA -->
                  <tr class="collapse-row">
                    <td colspan="6" class="p-0">
                      <div id="collapseTopik<?= (int)$ag['agenda_id']; ?>" class="panel-collapse collapse">
                        <div class="well well-sm m-0" style="margin:0;">

                          <div class="clearfix" style="margin-bottom:10px;">
                            <h4 class="pull-left" style="margin:5px 0;">
                              <i class="fa fa-list"></i> Topik untuk: <strong><?= htmlentities($ag['agenda_title']); ?></strong>
                            </h4>
                            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                              <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalTambahTopik<?= (int)$ag['agenda_id']; ?>">
                                <i class="fa fa-plus"></i> Tambah Topik
                              </button>
                            <?php } ?>
                          </div>

                          <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="margin-bottom:0;">
                              <thead>
                                <tr>
                                  <th style="width:8%" class="text-center">No</th>
                                  <th>Judul Topik</th>
                                  <th style="width:12%" class="text-center">JP Sync</th>
                                  <th style="width:12%" class="text-center">JP Async</th>
                                  <th style="width:18%" class="text-right">Aksi</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                  $topics = isset($topics_by_agenda[(int)$ag['agenda_id']]) ? $topics_by_agenda[(int)$ag['agenda_id']] : [];
                                  if (empty($topics)) {
                                ?>
                                  <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada topik.</td>
                                  </tr>
                                <?php } else { foreach ($topics as $tp) { ?>
                                  <tr>
                                    <td class="text-center"><?= (int)$tp['topic_no']; ?></td>
                                    <td><?= htmlentities($tp['topic_title']); ?></td>
                                    <td class="text-center"><?= (int)$tp['jp_sync']; ?></td>
                                    <td class="text-center"><?= (int)$tp['jp_async']; ?></td>
                                    <td class="text-right">
                                      <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalEditTopik<?= (int)$tp['topic_id']; ?>" title="Edit Topik">
                                          <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <a href="<?= base_url('data/prosesmateri?delete_topik=' . (int)$tp['topic_id']); ?>" onclick="return confirm('Hapus topik ini?');" class="btn btn-danger btn-xs" title="Hapus Topik">
                                          <i class="fa fa-trash"></i> Hapus
                                        </a>
                                      <?php } ?>
                                    </td>
                                  </tr>

                                  <!-- MODAL: EDIT TOPIK PER ITEM -->
                                  <div class="modal fade" id="modalEditTopik<?= (int)$tp['topic_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditTopikLabel<?= (int)$tp['topic_id']; ?>">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <form action="<?= base_url('data/prosesmateri'); ?>" method="POST">
                                          <div class="modal-header bg-green">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="modalEditTopikLabel<?= (int)$tp['topic_id']; ?>">
                                              <i class="fa fa-edit" style="color:white;"></i> Edit Topik
                                            </h4>
                                          </div>

                                          <div class="modal-body">
                                            <div class="row">
                                              <div class="col-sm-4">
                                                <div class="form-group">
                                                  <label>No Topik</label>
                                                  <input type="number" class="form-control" name="topic_no" value="<?= (int)$tp['topic_no']; ?>" required>
                                                </div>
                                              </div>
                                              <div class="col-sm-8">
                                                <div class="form-group">
                                                  <label>Judul Topik</label>
                                                  <input type="text" class="form-control" name="topic_title" value="<?= htmlentities($tp['topic_title']); ?>" required>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="row">
                                              <div class="col-sm-6">
                                                <div class="form-group">
                                                  <label>JP Sync</label>
                                                  <input type="number" class="form-control" name="jp_sync" value="<?= (int)$tp['jp_sync']; ?>" min="0" required>
                                                </div>
                                              </div>
                                              <div class="col-sm-6">
                                                <div class="form-group">
                                                  <label>JP Async</label>
                                                  <input type="number" class="form-control" name="jp_async" value="<?= (int)$tp['jp_async']; ?>" min="0" required>
                                                </div>
                                              </div>
                                            </div>

                                            <input type="hidden" name="agenda_id" value="<?= (int)$tp['agenda_id']; ?>">
                                            <input type="hidden" name="edit_topik" value="<?= (int)$tp['topic_id']; ?>">
                                          </div>

                                          <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <!-- /MODAL EDIT TOPIK -->
                                <?php } } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <!-- MODAL: EDIT AGENDA PER ITEM -->
                  <div class="modal fade" id="modalEditAgenda<?= (int)$ag['agenda_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditAgendaLabel<?= (int)$ag['agenda_id']; ?>">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="<?= base_url('data/prosesmateri'); ?>" method="POST">
                          <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalEditAgendaLabel<?= (int)$ag['agenda_id']; ?>">
                              <i class="fa fa-edit" style="color:white;"></i> Edit Agenda
                            </h4>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label>Nama Agenda</label>
                              <input type="text" class="form-control" name="agenda_title" value="<?= htmlentities($ag['agenda_title']); ?>" required>
                            </div>
                            <input type="hidden" name="edit_agenda" value="<?= (int)$ag['agenda_id']; ?>">
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- /MODAL EDIT AGENDA -->

                  <!-- MODAL: TAMBAH TOPIK PER AGENDA -->
                  <div class="modal fade" id="modalTambahTopik<?= (int)$ag['agenda_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTambahTopikLabel<?= (int)$ag['agenda_id']; ?>">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="<?= base_url('data/prosesmateri'); ?>" method="POST">
                          <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalTambahTopikLabel<?= (int)$ag['agenda_id']; ?>">
                              <i class="fa fa-plus" style="color:white;"></i> Tambah Topik (<?= htmlentities($ag['agenda_title']); ?>)
                            </h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>No Topik</label>
                                  <input type="number" class="form-control" name="topic_no" min="1" required>
                                </div>
                              </div>
                              <div class="col-sm-8">
                                <div class="form-group">
                                  <label>Judul Topik</label>
                                  <input type="text" class="form-control" name="topic_title" placeholder="Judul Topik" required>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>JP Sync</label>
                                  <input type="number" class="form-control" name="jp_sync" value="0" min="0" required>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>JP Async</label>
                                  <input type="number" class="form-control" name="jp_async" value="0" min="0" required>
                                </div>
                              </div>
                            </div>
                            <input type="hidden" name="agenda_id" value="<?= (int)$ag['agenda_id']; ?>">
                            <input type="hidden" name="tambah_topik" value="1">
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- /MODAL TAMBAH TOPIK -->

                <?php $no++; } // endforeach agenda ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- MODAL: TAMBAH AGENDA (GLOBAL) -->
<div class="modal fade" id="modalTambahAgenda" tabindex="-1" role="dialog" aria-labelledby="modalTambahAgendaLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('data/prosesmateri'); ?>" method="POST">
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalTambahAgendaLabel">
            <i class="fa fa-plus" style="color:white;"></i> Tambah Agenda
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Agenda</label>
            <input type="text" class="form-control" name="agenda_title" placeholder="Nama Agenda" required>
          </div>
          <input type="hidden" name="tambah_agenda" value="1">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /MODAL TAMBAH AGENDA -->

<style>
  .collapse-row > td { background: #fafafa; }
  .well.m-0 { margin: 0 !important; }
  .mb-10 { margin-bottom: 10px; }
  .p-0 { padding: 0 !important; }
</style>

<script>
$(document).ready(function(){
  // Tooltip umum
  $('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover',
    placement: 'right',
    container: 'body'
  });

  // Auto open collapse/modal berdasarkan flash open_modal
  try {
    var open = <?php echo json_encode($open_modal ?: null); ?>;
    if (open && open.type === 'topik' && open.agenda_id) {
      var agid = open.agenda_id;
      var $col = $('#collapseTopik' + agid);
      $col.collapse('show');
      // Scroll into view agar user langsung melihat panel topik terkait
      setTimeout(function(){
        if ($col.length) {
          $('html, body').animate({ scrollTop: $col.offset().top - 120 }, 350);
        }
      }, 250);
      // Jika ingin langsung buka modal tambah topik, uncomment baris di bawah:
      // $('#modalTambahTopik' + agid).modal('show');
    }
  } catch(e) {}
});
</script>
