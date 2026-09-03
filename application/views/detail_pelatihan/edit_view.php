<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>
/* Flex grid for Latsar sections */
.lts-grid { display: flex; flex-wrap: wrap; gap: 12px; }
.lts-col { flex: 1 1 240px; min-width: 240px; }
.lts-grid-5 .lts-col { flex: 1 1 180px; min-width: 180px; }
.lts-num { text-align: right; }
.select2-container { width: 100% !important; }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-edit text-success"></i> <?= $title_web; ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <form action="<?= base_url('data/prosesdetailpelatihan'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
      <input type="hidden" name="id_jenis_pelatihan" value="<?= (int)$id_jenis; ?>">
      <div class="row">
        <div class="col-md-12">

          <!-- Section 1: Informasi Kegiatan -->
          <div class="box box-primary">
            <div class="box-header with-border"><h4 class="box-title">Informasi Kegiatan</h4></div>
            <div class="box-body row">
              <div class="form-group col-md-6">
                <label>Nama Kegiatan
                  <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pilih kegiatan pelatihan yang akan diedit"></i>
                </label>
                <select class="form-control select2" name="id_pelatihan" id="id_pelatihan" required>
                  <option disabled <?= is_null($detail_pelatihan->id_pelatihan) ? 'selected' : ''; ?>>-- Pilih Kegiatan --</option>
                  <?php foreach($pelatihans as $isi): ?>
                    <option value="<?= $isi['id_pelatihan']; ?>"
                      <?= ($isi['id_pelatihan'] == $detail_pelatihan->id_pelatihan) ? 'selected' : ''; ?>>
                      <?= $isi['nama_kegiatan']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label>Penanggung Jawab
                  <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pilih panitia yang bertanggung jawab atas kegiatan ini"></i>
                </label>
                <select class="form-control select2" name="id_penanggung_jawab" required>
                  <option disabled <?= is_null($detail_pelatihan->id_penanggung_jawab) ? 'selected' : ''; ?>>-- Pilih Pegawai --</option>
                  <?php foreach($pegawais as $isi): ?>
                    <option value="<?= $isi['id_pegawai']; ?>"
                      <?= ($isi['id_pegawai'] == $detail_pelatihan->id_penanggung_jawab) ? 'selected' : ''; ?>>
                      <?= $isi['nama']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- Section 2: Struktur Tim Kegiatan (adaptif) -->
          <div class="box box-info">
            <div class="box-header with-border"><h4 class="box-title">Struktur Tim Kegiatan</h4></div>
            <div class="box-body">
              <?php if (!empty($is_latsar) && $is_latsar): ?>
                <?php
                  // LATSAR: ringkas + PIC Smartbangkom
                  $positions = [
                    'id_ketua_panitia' => ['label' => 'Ketua Panitia', 'desc' => 'Pilih ketua panitia pelaksana kegiatan'],
                    'id_akademis'      => ['label' => 'Akademis', 'desc' => 'Pilih penanggung jawab akademik kegiatan'],
                    'id_administrasi'  => ['label' => 'Administrasi', 'desc' => 'Pilih penanggung jawab administrasi kegiatan'],
                    'pic_smartbangkom' => ['label' => 'PIC Smartbangkom', 'desc' => 'Pilih penanggung jawab Smartbangkom'],
                  ];
                ?>
                <div class="lts-grid">
                  <?php foreach ($positions as $name => $data): ?>
                    <div class="form-group lts-col">
                      <label class="control-label">
                        <?= $data['label'] ?>
                        <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="<?= $data['desc'] ?>"></i>
                      </label>
                      <select class="form-control select2" name="<?= $name; ?>">
                        <option disabled <?= is_null($detail_pelatihan->$name) ? 'selected' : ''; ?>>-- Pilih Pegawai --</option>
                        <?php foreach($panitia as $isi): ?>
                          <option value="<?= $isi['id_pegawai']; ?>"
                            <?= ($isi['id_pegawai'] == $detail_pelatihan->$name) ? 'selected' : ''; ?>>
                            <?= $isi['nama']; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  <?php endforeach; ?>
                </div>
                <?php else: ?>
                <!-- PJJ/PDWK: Struktur Tim + Widyaiswara & Pengajar (pakai tabel baru) -->
                <div class="row">
                  <?php
                    $positions = [
                      'id_ketua_panitia' => ['label' => 'Ketua Panitia', 'desc' => 'Pilih ketua panitia pelaksana kegiatan'],
                      'id_akademis'      => ['label' => 'Akademis', 'desc' => 'Pilih penanggung jawab akademik kegiatan'],
                      'id_keuangan'      => ['label' => 'Keuangan', 'desc' => 'Pilih penanggung jawab keuangan kegiatan'],
                      'id_administrasi'  => ['label' => 'Administrasi', 'desc' => 'Pilih penanggung jawab administrasi kegiatan'],
                    ];
                  ?>
                  <?php foreach ($positions as $name => $data): ?>
                    <div class="form-group col-md-3">
                      <label><?= $data['label'] ?>
                        <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="<?= $data['desc'] ?>"></i>
                      </label>
                      <select class="form-control select2" name="<?= $name; ?>">
                        <option disabled <?= is_null($detail_pelatihan->$name) ? 'selected' : ''; ?>>-- Pilih Pegawai --</option>
                        <?php foreach($panitia as $isi): ?>
                          <option value="<?= $isi['id_pegawai']; ?>"
                            <?= ($isi['id_pegawai'] == $detail_pelatihan->$name) ? 'selected' : ''; ?>>
                            <?= $isi['nama']; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  <?php endforeach; ?>
                </div>

                <div class="row">
                  <!-- Widyaiswara (multi) -->
                  <div class="form-group col-md-4">
                    <label>Widyaiswara (multi)
                      <i class="fa fa-info-circle text-blue" data-toggle="tooltip"
                        title="Pilih satu atau lebih Widyaiswara. Tersimpan ke tbl_pelatihan_pengajar (tipe_peran = 'Widyaiswara')."></i>
                    </label>
                    <select class="form-control select2" name="wi_ids[]" id="wi_ids" multiple>
                      <?php foreach($pegawais as $pg): ?>
                        <option value="<?= $pg['id_pegawai']; ?>"
                          <?= in_array((int)$pg['id_pegawai'], $wi_selected ?? []) ? 'selected' : '' ?>>
                          <?= $pg['nama']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Tekan Ctrl/⌘ untuk memilih banyak (atau gunakan Select2).</small>
                  </div>

                  <!-- WI Rapat Kelulusan (single) -->
                  <div class="form-group col-md-4">
                    <label>WI Rapat Kelulusan
                      <i class="fa fa-info-circle text-blue" data-toggle="tooltip"
                        title="Opsional: Widyaiswara yang hadir pada rapat kelulusan (tipe_peran = 'Widyaiswara Rapat Kelulusan')."></i>
                    </label>
                    <select class="form-control select2" name="wi_rapat_kelulusan" id="wi_rapat_kelulusan">
                      <option value="">-- Pilih Pegawai (opsional) --</option>
                      <?php foreach($pegawais as $pg): ?>
                        <option value="<?= $pg['id_pegawai']; ?>"
                          <?= isset($wi_rapat_selected) && (int)$wi_rapat_selected === (int)$pg['id_pegawai'] ? 'selected' : '' ?>>
                          <?= $pg['nama']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Pengajar (multi) -->
                  <div class="form-group col-md-4">
                    <label>Pengajar (multi)
                      <i class="fa fa-info-circle text-blue" data-toggle="tooltip"
                        title="Pilih satu atau lebih Pengajar. Tersimpan ke tbl_pelatihan_pengajar (tipe_peran = 'Pengajar')."></i>
                    </label>
                    <select class="form-control select2" name="pengajar_ids[]" id="pengajar_ids" multiple>
                      <?php foreach($pegawais as $pg): ?>
                        <option value="<?= $pg['id_pegawai']; ?>"
                          <?= in_array((int)$pg['id_pegawai'], $pengajar_selected ?? []) ? 'selected' : '' ?>>
                          <?= $pg['nama']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              <?php endif; ?>

            </div>
          </div>

          <?php if (!empty($is_latsar) && $is_latsar): ?>
            <!-- Section 3: Penilaian Latsar -->
            <div class="box box-warning">
              <div class="box-header with-border"><h4 class="box-title">Penilaian Latsar</h4></div>
              <div class="box-body lts-grid lts-grid-5">
                <div class="form-group lts-col">
                  <label class="control-label">Sangat Memuaskan <span class="label label-default">SM</span></label>
                  <input type="number" name="jml_peserta_nilai_sm" class="form-control lts-num" min="0"
                         value="<?= (int)$detail_pelatihan->jml_peserta_nilai_sm; ?>">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Memuaskan <span class="label label-default">M</span></label>
                  <input type="number" name="jml_peserta_nilai_m" class="form-control lts-num" min="0"
                         value="<?= (int)$detail_pelatihan->jml_peserta_nilai_m; ?>">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Cukup Memuaskan <span class="label label-default">CM</span></label>
                  <input type="number" name="jml_peserta_nilai_cm" class="form-control lts-num" min="0"
                         value="<?= (int)$detail_pelatihan->jml_peserta_nilai_cm; ?>">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Dalam Lingkup <span class="label label-default">DL</span></label>
                  <input type="number" name="jml_peserta_nilai_dl" class="form-control lts-num" min="0"
                         value="<?= (int)$detail_pelatihan->jml_peserta_nilai_dl; ?>">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Tidak Melanjutkan <span class="label label-default">TM</span></label>
                  <input type="number" name="jml_peserta_tm" class="form-control lts-num" min="0"
                         value="<?= (int)$detail_pelatihan->jml_peserta_tm; ?>">
                </div>
              </div>
            </div>

            <!-- Section 4: Peringkat Peserta -->
            <div class="box box-success">
              <div class="box-header with-border"><h4 class="box-title">Peringkat Peserta</h4></div>
              <div class="box-body lts-grid">
                <?php
                  $rankFields = [
                    'peserta_peringkat_1' => 'Peringkat 1',
                    'peserta_peringkat_2' => 'Peringkat 2',
                    'peserta_peringkat_3' => 'Peringkat 3',
                  ];
                ?>
                <?php foreach ($rankFields as $name => $label): ?>
                  <div class="form-group lts-col">
                    <label class="control-label"><?= $label; ?></label>
                    <select class="form-control select2 peserta-ranking" name="<?= $name; ?>" id="<?= $name; ?>" disabled>
                      <option disabled selected>-- Pilih Peserta --</option>
                      <!-- opsi akan diisi via AJAX lalu dipra-isi dengan nilai existing -->
                    </select>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

          <?php else: ?>

            <!-- Default Section 3 & 4 (Statistik & Data Peserta) -->
            <div class="box box-warning">
              <div class="box-header with-border"><h4 class="box-title">Statistik Widyaiswara & Pengajar</h4></div>
              <div class="box-body row">
                <?php
                  // Daftar field statistik (tetap)
                  $stat_fields = [
                    'jumlah_wi_pengajar'       => 'Jumlah WI & Pengajar',
                    'jumlah_pendidikan_wi_d2'  => 'WI D2/D3',
                    'jumlah_pendidikan_wi_s1'  => 'WI S1',
                    'jumlah_pendidikan_wi_s2'  => 'WI S2',
                    'jumlah_pendidikan_wi_s3'  => 'WI S3',
                  ];

                  // Hitung prefill khusus untuk "Jumlah WI & Pengajar"
                  $init_wi_pengajar = (int)(
                    (!empty($wi_selected)       ? count($wi_selected)       : 0)
                    + (!empty($pengajar_selected) ? count($pengajar_selected) : 0)
                  );
                  if ($init_wi_pengajar <= 0) {
                    // fallback ke nilai existing di DB jika prefill kosong
                    $init_wi_pengajar = (int)$detail_pelatihan->jumlah_wi_pengajar;
                  }
                ?>

                <?php foreach ($stat_fields as $name => $label): ?>
                  <?php if ($name === 'jumlah_wi_pengajar'): ?>
                    <!-- KHUSUS: readonly + prefill -->
                    <div class="form-group col-md-4">
                      <label><?= $label ?></label>
                      <input type="number"
                            name="jumlah_wi_pengajar"
                            id="jumlah_wi_pengajar"
                            class="form-control"
                            min="0"
                            value="<?= $init_wi_pengajar; ?>"
                            readonly>
                      <small class="text-muted">Terisi otomatis dari pilihan Widyaiswara & Pengajar.</small>
                    </div>
                  <?php else: ?>
                    <!-- Field statistik lain (seperti semula) -->
                    <div class="form-group col-md-4">
                      <label><?= $label ?></label>
                      <input type="number"
                            name="<?= $name ?>"
                            class="form-control"
                            min="0"
                            value="<?= (int)$detail_pelatihan->$name; ?>">
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="box box-success">
              <div class="box-header with-border"><h4 class="box-title">Data Peserta</h4></div>
              <div class="box-body row">
                <?php
                  $peserta_fields = [
                    'jumlah_peserta'                 => 'Jumlah Peserta',
                    'jumlah_lulus'                   => 'Jumlah Lulus',
                    'jumlah_tidak_lulus'             => 'Jumlah Tidak Lulus',
                    'jumlah_peserta_asn'             => 'Peserta ASN',
                    'jumlah_peserta_non_asn'         => 'Peserta Non-ASN',
                    'jumlah_peserta_laki'            => 'Peserta Laki-laki',
                    'jumlah_peserta_wanita'          => 'Peserta Perempuan',
                    'jumlah_pendidikan_peserta_sma'  => 'Pendidikan Peserta SMA',
                    'jumlah_pendidikan_peserta_d3'   => 'Pendidikan Peserta D3',
                    'jumlah_pendidikan_peserta_s1'   => 'Pendidikan Peserta S1',
                    'jumlah_pendidikan_peserta_s2'   => 'Pendidikan Peserta S2',
                    'jumlah_pendidikan_peserta_s3'   => 'Pendidikan Peserta S3',
                  ];
                ?>
                <?php foreach ($peserta_fields as $name => $label): ?>
                  <div class="form-group col-md-4">
                    <label><?= $label ?></label>
                    <input type="number" name="<?= $name ?>" class="form-control" min="0"
                          value="<?= (int)$detail_pelatihan->$name; ?>">
                  </div>
                <?php endforeach; ?>
                <div class="form-group col-md-3">
                  <label>Jabatan Peserta
                    <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jabatan/jenis pekerjaan peserta"></i>
                  </label>
                  <textarea name="jabatan_peserta" class="form-control" rows="1"><?= htmlspecialchars($detail_pelatihan->jabatan_peserta); ?></textarea>
                </div>
              </div>
            </div>

          <?php endif; ?>

                      <!-- Section Anggaran (semua jenis) -->
                      <div class="box box-danger">
                        <div class="box-header with-border"><h4 class="box-title">Informasi Anggaran</h4></div>
                        <div class="box-body row">
                          <div class="form-group col-md-6">
                            <label>RAB (Rp)
                              <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Rencana Anggaran Biaya kegiatan"></i>
                            </label>
                            <input type="number" name="rab" class="form-control" step="0.01" min="0"
                                  value="<?= (float)$detail_pelatihan->rab; ?>">
                          </div>
                          <div class="form-group col-md-6">
                            <label>Realisasi (Rp)
                              <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Realisasi anggaran yang digunakan"></i>
                            </label>
                            <input type="number" name="realisasi" class="form-control" step="0.01" min="0"
                                  value="<?= (float)$detail_pelatihan->realisasi; ?>">
                          </div>
                        </div>
                      </div>

                      <!-- Submit -->
                      <div class="box-footer text-right">
                        <input type="hidden" name="edit" value="<?= (int)$detail_pelatihan->id_detail_pelatihan; ?>">
                        <!-- jaga konteks tab -->
                        <input type="hidden" name="jenis" value="<?= isset($jenis) ? htmlspecialchars($jenis) : ''; ?>">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="<?= base_url('data/detailpelatihan' . (!empty($jenis) ? '?jenis='.urlencode($jenis) : '')); ?>" class="btn btn-danger">Kembali</a>
                      </div>

                    </div>
                  </div>
                </form>
              </section>
            </div>

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover', placement: 'right', container: 'body' });
  $('.select2').select2({ width: '100%' });

  var isLatsar = <?= !empty($is_latsar) && $is_latsar ? 'true' : 'false'; ?>;

  // =========================
  // PJJ / PDWK (non-Latsar)
  // =========================
  if (!isLatsar) {
    var $wiMulti       = $('#wi_ids');
    var $wiRapat       = $('#wi_rapat_kelulusan');
    var $pengajarMulti = $('#pengajar_ids');
    var $jumlahAuto    = $('#jumlah_wi_pengajar');
    var $idPel         = $('#id_pelatihan');

    function updateJumlahWIPengajar() {
      var countWI       = ($wiMulti.val()       || []).length;
      var countPengajar = ($pengajarMulti.val() || []).length;
      $jumlahAuto.val(countWI + countPengajar); // tidak menghitung WI rapat
    }

    // Reset -> disable konflik hanya antar WI <-> Pengajar (dua arah)
    function disableConflicts($sel, conflicts) {
      // 1) re-enable semua opsi agar tidak ada sisa state
      $sel.find('option').prop('disabled', false);

      // 2) disable yang konflik KECUALI yang sedang terpilih di select tsb
      var selectedHere = ($sel.val() || []).map(String);
      $sel.find('option').each(function(){
        var val = $(this).attr('value');
        if (!val) return;
        var isSelectedHere = selectedHere.includes(val);
        var conflict = conflicts.includes(val);
        if (conflict && !isSelectedHere) {
          $(this).prop('disabled', true);
        }
      });

      $sel.trigger('change.select2');
    }

    function enforceUniquePeople() {
      var selectedWI       = ($wiMulti.val() || []).map(String);
      var selectedPengajar = ($pengajarMulti.val() || []).map(String);

      // Terapkan konflik dua arah hanya WI <-> Pengajar
      disableConflicts($wiMulti,       selectedPengajar);
      disableConflicts($pengajarMulti, selectedWI);

      // Penting: WI Rapat selalu full list & selalu enabled (tidak dibatasi sama sekali)
      $wiRapat.find('option').prop('disabled', false);
      $wiRapat.trigger('change.select2');

      updateJumlahWIPengajar();
    }

    // Inisialisasi & sinkron awal
    enforceUniquePeople();
    setTimeout(updateJumlahWIPengajar, 0);

    // Event perubahan
    $wiMulti.on('change', enforceUniquePeople);
    $pengajarMulti.on('change', enforceUniquePeople);

    // Perubahan WI Rapat tidak memengaruhi apa pun; tetap pastikan semua opsi aktif
    $wiRapat.on('change', function(){
      $wiRapat.find('option').prop('disabled', false);
      $wiRapat.trigger('change.select2');
    });

    // Jika ganti pelatihan, kosongkan pilihan multi & reset jumlah; WI rapat bebas sesuai kebutuhan Anda
    $idPel.on('change', function(){
      $wiMulti.val(null).trigger('change.select2');
      $pengajarMulti.val(null).trigger('change.select2');
      // opsional: kosongkan WI rapat (tetap menampilkan seluruh pegawai)
      $wiRapat.val('').trigger('change.select2');
      enforceUniquePeople();
    });
  }

  // ==========
  // LATSAR
  // ==========
  if (isLatsar) {
    var $idPel = $('#id_pelatihan');
    var $rankSelects = $('.peserta-ranking');

    // nilai existing untuk preselect
    var curRank = {
      'peserta_peringkat_1': '<?= (int)$detail_pelatihan->peserta_peringkat_1; ?>',
      'peserta_peringkat_2': '<?= (int)$detail_pelatihan->peserta_peringkat_2; ?>',
      'peserta_peringkat_3': '<?= (int)$detail_pelatihan->peserta_peringkat_3; ?>'
    };

    function populateRankingSelects(options) {
      $rankSelects.each(function(){
        var $sel = $(this);
        var name = $sel.attr('name');
        var selectedVal = curRank[name] || '';

        $sel.prop('disabled', false).empty()
            .append('<option disabled selected>-- Pilih Peserta --</option>');

        options.forEach(function(row) {
          $sel.append($('<option/>', { value: row.id_peserta, text: row.nama_peserta }));
        });

        if (selectedVal && !$sel.find('option[value="'+selectedVal+'"]').length) {
          $sel.append($('<option/>', { value: selectedVal, text: '[non-list] ID '+selectedVal }));
        }
        if (selectedVal) { $sel.val(selectedVal); }
        $sel.trigger('change.select2');
      });
    }

    function enforceUniqueRanks() {
      var chosen = {};
      $rankSelects.each(function(){
        var v = $(this).val();
        if (v) chosen[v] = true;
      });
      $rankSelects.each(function(){
        var $sel = $(this);
        var myVal = $sel.val();
        $sel.find('option').each(function(){
          var ov = $(this).attr('value');
          if (!ov) return;
          var shouldDisable = chosen[ov] && ov !== myVal;
          $(this).prop('disabled', shouldDisable);
        });
        $sel.trigger('change.select2');
      });
    }

    function fetchAndPopulate(idPel) {
      if (!idPel) return;
      $.getJSON('<?= base_url('data/get_peserta_by_pelatihan'); ?>', { id_pelatihan: idPel }, function(res){
        populateRankingSelects(Array.isArray(res) ? res : []);
        enforceUniqueRanks();
      }).fail(function(){
        populateRankingSelects([]);
      });
    }

    // init
    $rankSelects.select2({ width: '100%' });
    fetchAndPopulate($idPel.val());

    // on change kegiatan
    $idPel.on('change', function(){
      curRank = { 'peserta_peringkat_1':'', 'peserta_peringkat_2':'', 'peserta_peringkat_3':'' };
      fetchAndPopulate($(this).val());
    });

    $(document).on('change', '.peserta-ranking', function(){
      enforceUniqueRanks();
    });
  }
});
</script>

