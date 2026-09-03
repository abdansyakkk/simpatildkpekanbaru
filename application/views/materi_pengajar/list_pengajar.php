<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$open_modal = $this->session->flashdata('open_modal');
$id_pelatihan = isset($pelatihan['id_pelatihan']) ? (int)$pelatihan['id_pelatihan'] : 0;
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-users" style="color:green"></i> <?= isset($title_web) ? $title_web : 'Data Pengajar Pelatihan Dasar CPNS'; ?>
      <small class="text-muted" style="margin-left:6px;">
        <?php if (!empty($pelatihan)) { ?>
          (ID: <?= (int)$pelatihan['id_pelatihan']; ?><?= !empty($pelatihan['nama_pelatihan']) ? ', ' . htmlentities($pelatihan['nama_pelatihan']) : '' ; ?>)
        <?php } ?>
      </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?= base_url('data/pengajar'); ?>"><i class="fa fa-list"></i> Daftar Pelatihan</a></li>
      <li class="active"><i class="fa fa-users"></i> <?= isset($title_web) ? $title_web : 'Pengajar'; ?></li>
    </ol>
  </section>

  <section class="content">
    <?php if (!empty($this->session->flashdata())) { echo $this->session->flashdata('pesan'); } ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i> Agenda & Penugasan Pengajar</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped" width="100%">
                <thead>
                  <tr>
                    <th style="width:5%">No</th>
                    <th>Nama Agenda</th>
                    <th style="width:9%" class="text-center"># Topik</th>
                    <th style="width:11%" class="text-center">JP Sync</th>
                    <th style="width:11%" class="text-center">JP Async</th>
                    <th style="width:22%">Main Teacher</th>
                    <th style="width:21%" class="text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach (($agenda ? $agenda->result_array() : []) as $ag) { 
                    $aid = (int)$ag['agenda_id'];
                    $pa  = isset($pa_by_agenda[$aid]) ? $pa_by_agenda[$aid] : null;
                    $main_name = $pa && !empty($pa['main_teacher_name']) ? $pa['main_teacher_name'] : '<span class="text-muted">(belum ditetapkan)</span>';
                ?>
                  <tr>
                    <td><?= $no; ?></td>
                    <td><?= htmlentities($ag['agenda_title']); ?></td>
                    <td class="text-center"><?= (int)$ag['total_topics']; ?></td>
                    <td class="text-center"><?= (int)$ag['total_jp_sync']; ?></td>
                    <td class="text-center"><?= (int)$ag['total_jp_async']; ?></td>
                    <td>
                      <i class="fa fa-user"></i> <?= $main_name; ?>
                    </td>
                    <td class="text-right">
                      <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#collapseAgenda<?= $aid; ?>">
                        <i class="fa fa-list"></i> Topik & Grup
                      </button>
                      <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalSetMain<?= $aid; ?>" title="Set/Update Main Teacher">
                          <i class="fa fa-user-plus"></i>
                        </button>
                        <?php if ($pa && !empty($pa['main_teacher_id'])) { ?>
                          <a href="<?= base_url('data/prosespengajar?clear_main_teacher=1&id_pelatihan=' . $id_pelatihan . '&agenda_id=' . $aid); ?>" class="btn btn-warning btn-sm" onclick="return confirm('Hapus main teacher untuk agenda ini?');" title="Clear Main Teacher">
                            <i class="fa fa-user-times"></i>
                          </a>
                        <?php } ?>
                      <?php } ?>
                    </td>
                  </tr>

                  <!-- DETAIL COLLAPSE: TOPIK & GRUP -->
                  <tr class="collapse-row">
                    <td colspan="7" class="p-0">
                      <div id="collapseAgenda<?= $aid; ?>" class="panel-collapse collapse">
                        <div class="well well-sm m-0" style="margin:0;">

                          <!-- TOPIK -->
                          <div class="clearfix mb-10">
                            <h4 class="pull-left" style="margin:5px 0;">
                              <i class="fa fa-list"></i> Topik pada: <strong><?= htmlentities($ag['agenda_title']); ?></strong>
                            </h4>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="margin-bottom:10px;">
                              <thead>
                                <tr>
                                  <th style="width:8%" class="text-center">No</th>
                                  <th>Judul Topik</th>
                                  <th style="width:12%" class="text-center">JP Sync</th>
                                  <th style="width:12%" class="text-center">JP Async</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php $topics = isset($topics_by_agenda[$aid]) ? $topics_by_agenda[$aid] : []; ?>
                                <?php if (empty($topics)) { ?>
                                  <tr><td colspan="4" class="text-center text-muted">Belum ada topik.</td></tr>
                                <?php } else { foreach ($topics as $tp) { ?>
                                  <tr>
                                    <td class="text-center"><?= (int)$tp['topic_no']; ?></td>
                                    <td><?= htmlentities($tp['topic_title']); ?></td>
                                    <td class="text-center"><?= (int)$tp['jp_sync']; ?></td>
                                    <td class="text-center"><?= (int)$tp['jp_async']; ?></td>
                                  </tr>
                                <?php } } ?>
                              </tbody>
                            </table>
                          </div>

                          <!-- GRUP PENGAJAR -->
                          <div class="clearfix mb-10">
                            <h4 class="pull-left" style="margin:5px 0;">
                              <i class="fa fa-users"></i> Grup Pengajar
                            </h4>
                            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                              <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalTambahGrup<?= $aid; ?>">
                                <i class="fa fa-plus"></i> Tambah Grup
                              </button>
                            <?php } ?>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="margin-bottom:0;">
                              <thead>
                                <tr>
                                  <th style="width:10%" class="text-center">Grup</th>
                                  <th>Nama Pengajar</th>
                                  <th style="width:22%" class="text-right">Aksi</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php $grs = isset($groups_by_agenda[$aid]) ? $groups_by_agenda[$aid] : []; ?>
                                <?php if (empty($grs)) { ?>
                                  <tr><td colspan="3" class="text-center text-muted">Belum ada grup pengajar.</td></tr>
                                <?php } else { foreach ($grs as $gr) { ?>
                                  <tr>
                                    <td class="text-center"><?= (int)$gr['group_no']; ?></td>
                                    <td><i class="fa fa-user"></i> <?= htmlentities($gr['teacher_name'] ?: '-'); ?></td>
                                    <td class="text-right">
                                      <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalEditGrup<?= (int)$gr['agenda_group_id']; ?>">
                                          <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <a href="<?= base_url('data/prosespengajar?delete_grup_agenda='.(int)$gr['agenda_group_id'].'&id_pelatihan='.$id_pelatihan); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Hapus grup ini?');">
                                          <i class="fa fa-trash"></i> Hapus
                                        </a>
                                      <?php } ?>
                                    </td>
                                  </tr>

                                  <!-- MODAL: EDIT GRUP -->
                                  <div class="modal fade" id="modalEditGrup<?= (int)$gr['agenda_group_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditGrupLabel<?= (int)$gr['agenda_group_id']; ?>">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <form action="<?= base_url('data/prosespengajar'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
                                          <div class="modal-header bg-green">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="modalEditGrupLabel<?= (int)$gr['agenda_group_id']; ?>">
                                              <i class="fa fa-edit" style="color:white;"></i> Edit Grup Pengajar
                                            </h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="row">
                                              <div class="col-sm-4">
                                                <div class="form-group">
                                                  <label>No Grup</label>
                                                  <input type="number" class="form-control" name="group_no" value="<?= (int)$gr['group_no']; ?>" min="1" required>
                                                </div>
                                              </div>
                                              <div class="col-sm-8">
                                                <div class="form-group">
                                                  <label>Pengajar</label>
                                                  <select name="teacher_id" class="form-control" required>
                                                    <option value="">- pilih pengajar -</option>
                                                    <?php foreach (($pegawai ?? []) as $pg) { ?>
                                                      <option value="<?= (int)$pg['id_pegawai']; ?>" <?= ((int)$pg['id_pegawai'] === (int)$gr['teacher_id']) ? 'selected' : ''; ?>>
                                                        <?= htmlentities($pg['nama']); ?>
                                                      </option>
                                                    <?php } ?>
                                                  </select>
                                                </div>
                                              </div>
                                            </div>
                                            <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                                            <input type="hidden" name="edit_grup" value="<?= (int)$gr['agenda_group_id']; ?>">
                                          </div>
                                          <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <!-- /MODAL EDIT GRUP -->
                                <?php } } ?>
                              </tbody>
                            </table>
                          </div>

                        </div>
                      </div>
                    </td>
                  </tr>

                  <!-- MODAL: SET MAIN TEACHER PER AGENDA -->
                  <div class="modal fade" id="modalSetMain<?= $aid; ?>" tabindex="-1" role="dialog" aria-labelledby="modalSetMainLabel<?= $aid; ?>">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="<?= base_url('data/prosespengajar'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
                          <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalSetMainLabel<?= $aid; ?>">
                              <i class="fa fa-user-plus" style="color:white;"></i> Set/Update Main Teacher (<?= htmlentities($ag['agenda_title']); ?>)
                            </h4>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label>Pilih Main Teacher</label>
                              <select name="main_teacher_id" class="form-control">
                                <option value="">- belum ditetapkan -</option>
                                <?php $currentMain = $pa ? (int)$pa['main_teacher_id'] : 0; ?>
                                <?php foreach (($pegawai ?? []) as $pg) { ?>
                                  <option value="<?= (int)$pg['id_pegawai']; ?>" <?= ((int)$pg['id_pegawai'] === $currentMain) ? 'selected' : ''; ?>>
                                    <?= htmlentities($pg['nama']); ?>
                                  </option>
                                <?php } ?>
                              </select>
                            </div>
                            <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                            <input type="hidden" name="agenda_id" value="<?= $aid; ?>">
                            <input type="hidden" name="set_main_teacher" value="1">
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- /MODAL SET MAIN TEACHER -->

                  <!-- MODAL: TAMBAH GRUP PER AGENDA -->
                  <div class="modal fade" id="modalTambahGrup<?= $aid; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTambahGrupLabel<?= $aid; ?>">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="<?= base_url('data/prosespengajar'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
                          <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalTambahGrupLabel<?= $aid; ?>">
                              <i class="fa fa-plus" style="color:white;"></i> Tambah Grup Pengajar (<?= htmlentities($ag['agenda_title']); ?>)
                            </h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>No Grup</label>
                                  <input type="number" class="form-control" name="group_no" min="1" required>
                                </div>
                              </div>
                              <div class="col-sm-8">
                                <div class="form-group">
                                  <label>Pengajar</label>
                                  <select name="teacher_id" class="form-control" required>
                                    <option value="">- pilih pengajar -</option>
                                    <?php foreach (($pegawai ?? []) as $pg) { ?>
                                      <option value="<?= (int)$pg['id_pegawai']; ?>"><?= htmlentities($pg['nama']); ?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <input type="hidden" name="id_pelatihan" value="<?= $id_pelatihan; ?>">
                            <input type="hidden" name="agenda_id" value="<?= $aid; ?>">
                            <input type="hidden" name="tambah_grup" value="1">
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- /MODAL TAMBAH GRUP -->

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

<style>
  .collapse-row > td { background: #fafafa; }
  .well.m-0 { margin: 0 !important; }
  .mb-10 { margin-bottom: 10px; }
  .p-0 { padding: 0 !important; }
</style>

<script>
$(document).ready(function(){
  // Tooltips umum
  $('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover',
    placement: 'right',
    container: 'body'
  });

  // Auto expand collapse berdasarkan flashdata
  try {
    var open = <?php echo json_encode($open_modal ?: null); ?>;
    if (open && open.agenda_id) {
      var agid = open.agenda_id;
      var $col = $('#collapseAgenda' + agid);
      $col.collapse('show');
      setTimeout(function(){
        if ($col.length) {
          $('html, body').animate({ scrollTop: $col.offset().top - 120 }, 350);
        }
      }, 250);
      // Jika ingin otomatis buka modal tertentu, Anda bisa tambahkan logika berikut
      // if (open.type === 'main') { $('#modalSetMain' + agid).modal('show'); }
      // if (open.type === 'grup') { $('#modalTambahGrup' + agid).modal('show'); }
    }
  } catch(e) {}
});
</script>
