<style>
/* Flex grid for Latsar sections */
.lts-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}
.lts-col {
  flex: 1 1 240px;          /* responsive min width */
  min-width: 240px;
}
.lts-grid-5 .lts-col {
  flex: 1 1 180px;          /* tighter for 5 numeric fields in a row on wide screens */
  min-width: 180px;
}
.lts-num { text-align: right; } /* numbers right-aligned for readability */
.select2-container { width: 100% !important; } /* ensure select2 fills the column */
</style>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-plus text-success"></i> <?= $title_web; ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <form action="<?= base_url('data/prosesdetailpelatihan'); ?>" method="POST"><?php echo '<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">'; ?>
      <input type="hidden" name="id_jenis_pelatihan" value="<?= (int)$id_jenis ?>">
      <div class="row">
        <div class="col-md-12">

          <!-- Section 1: Informasi Kegiatan -->
          <div class="box box-primary">
            <div class="box-header with-border"><h4 class="box-title">Informasi Kegiatan</h4></div>
            <div class="box-body row">
              <div class="form-group col-md-6">
                <label>Nama Kegiatan <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pilih kegiatan pelatihan yang akan ditambahkan detailnya, jika pelatihan tidak tersedia silahkan tambahkan pelatihan pada menu Data Pelatihan terlebih dahulu"></i></label>
                <select class="form-control select2" name="id_pelatihan" id="id_pelatihan" required>
                  <option disabled selected>-- Pilih Kegiatan --</option>
                  <?php foreach($pelatihans as $isi): ?>
                    <option value="<?= $isi['id_pelatihan']; ?>"><?= $isi['nama_kegiatan']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label>Penanggung Jawab <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pilih panitia yang bertanggung jawab atas kegiatan ini"></i></label>
                <select class="form-control select2" name="id_penanggung_jawab" required>
                  <option disabled selected>-- Pilih Pegawai --</option>
                  <?php foreach($pegawais as $isi): ?>
                    <option value="<?= $isi['id_pegawai']; ?>"><?= $isi['nama']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- Section 2: Struktur Tim Kegiatan (Single block, conditional content) -->
          <div class="box box-info">
            <div class="box-header with-border"><h4 class="box-title">Struktur Tim Kegiatan</h4></div>
            <div class="box-body">
              <?php if (!empty($is_latsar) && $is_latsar): ?>
                <?php
                  // LATSAR: versi ringkas + PIC Smartbangkom
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
                        <option disabled selected>-- Pilih Pegawai --</option>
                        <?php foreach($panitia as $isi): ?>
                          <option value="<?= $isi['id_pegawai']; ?>"><?= $isi['nama']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <!-- PJJ/PDWK: Struktur Tim + Widyaiswara & Pengajar (pakai tabel baru) -->
                <div class="row">
                  <!-- Struktur Tim Kegiatan (tetap single-select) -->
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
                      <label><?= $data['label'] ?> <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="<?= $data['desc'] ?>"></i></label>
                      <select class="form-control select2" name="<?= $name; ?>">
                        <option disabled selected>-- Pilih Pegawai --</option>
                        <?php foreach($panitia as $isi): ?>
                          <option value="<?= $isi['id_pegawai']; ?>"><?= $isi['nama']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  <?php endforeach; ?>
                </div>

                <!-- Widyaiswara & Pengajar -->
                <div class="row">
                  <!-- Widyaiswara (multi) -->
                  <div class="form-group col-md-4">
                    <label>Widyaiswara (multi)
                      <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pilih satu atau lebih Widyaiswara. Data disimpan ke tbl_pelatihan_pengajar dengan tipe_peran = 'Widyaiswara'."></i>
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
                      <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Opsional: Widyaiswara yang hadir pada rapat kelulusan. Disimpan sebagai 'Widyaiswara Rapat Kelulusan'."></i>
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
                      <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Pilih satu atau lebih Pengajar. Data disimpan ke tbl_pelatihan_pengajar dengan tipe_peran = 'Pengajar'."></i>
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
                  <input type="number" name="jml_peserta_nilai_sm" class="form-control lts-num" min="0" value="0">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Memuaskan <span class="label label-default">M</span></label>
                  <input type="number" name="jml_peserta_nilai_m" class="form-control lts-num" min="0" value="0">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Cukup Memuaskan <span class="label label-default">CM</span></label>
                  <input type="number" name="jml_peserta_nilai_cm" class="form-control lts-num" min="0" value="0">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Dalam Lingkup <span class="label label-default">DL</span></label>
                  <input type="number" name="jml_peserta_nilai_dl" class="form-control lts-num" min="0" value="0">
                </div>
                <div class="form-group lts-col">
                  <label class="control-label">Tidak Melanjutkan <span class="label label-default">TM</span></label>
                  <input type="number" name="jml_peserta_tm" class="form-control lts-num" min="0" value="0">
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
                      <!-- opsi akan diisi via AJAX setelah Nama Kegiatan dipilih -->
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
                <div class="form-group col-md-4">
              <label>Jumlah WI & Pengajar</label>
              <input type="number" name="jumlah_wi_pengajar" id="jumlah_wi_pengajar"
                    class="form-control" min="0" value="0" readonly>
            </div>

                <div class="form-group col-md-4">
                  <label>WI D2/D3</label>
                  <input type="number" name="jumlah_pendidikan_wi_d2" class="form-control" min="0" value="0">
                </div>
                <div class="form-group col-md-4">
                  <label>WI S1</label>
                  <input type="number" name="jumlah_pendidikan_wi_s1" class="form-control" min="0" value="0">
                </div>
                <div class="form-group col-md-4">
                  <label>WI S2</label>
                  <input type="number" name="jumlah_pendidikan_wi_s2" class="form-control" min="0" value="0">
                </div>
                <div class="form-group col-md-4">
                  <label>WI S3</label>
                  <input type="number" name="jumlah_pendidikan_wi_s3" class="form-control" min="0" value="0">
                </div>
              </div>
            </div>

            <div class="box box-success">
              <div class="box-header with-border"><h4 class="box-title">Data Peserta</h4></div>
              <div class="box-body row">
                <?php
                  $peserta_fields = [
                    'jumlah_peserta' => 'Jumlah Peserta',
                    'jumlah_lulus' => 'Jumlah Lulus',
                    'jumlah_tidak_lulus' => 'Jumlah Tidak Lulus',
                    'jumlah_peserta_asn' => 'Peserta ASN',
                    'jumlah_peserta_non_asn' => 'Peserta Non-ASN',
                    'jumlah_peserta_laki' => 'Peserta Laki-laki',
                    'jumlah_peserta_wanita' => 'Peserta Perempuan',
                    'jumlah_pendidikan_peserta_sma' => 'Pendidikan Peserta SMA',
                    'jumlah_pendidikan_peserta_d3' => 'Pendidikan Peserta D3',
                    'jumlah_pendidikan_peserta_s1' => 'Pendidikan Peserta S1',
                    'jumlah_pendidikan_peserta_s2' => 'Pendidikan Peserta S2',
                    'jumlah_pendidikan_peserta_s3' => 'Pendidikan Peserta S3',
                  ];
                ?>
                <?php foreach ($peserta_fields as $name => $label): ?>
                  <div class="form-group col-md-4">
                    <label><?= $label ?></label>
                    <input type="number" name="<?= $name; ?>" class="form-control" value="0" min="0">
                  </div>
                <?php endforeach; ?>
                <div class="form-group col-md-3">
                  <label>Jabatan Peserta <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Jabatan/jenis pekerjaan peserta"></i></label>
                  <textarea name="jabatan_peserta" class="form-control" rows="1"></textarea>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Section Anggaran (tetap untuk semua jenis) -->
          <div class="box box-danger">
            <div class="box-header with-border"><h4 class="box-title">Informasi Anggaran</h4></div>
            <div class="box-body row">
              <div class="form-group col-md-6">
                <label>RAB (Rp) <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Rencana Anggaran Biaya kegiatan"></i></label>
                <input type="number" name="rab" class="form-control" value="0" step="0.01" min="0">
              </div>
              <div class="form-group col-md-6">
                <label>Realisasi (Rp) <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Realisasi anggaran yang digunakan"></i></label>
                <input type="number" name="realisasi" class="form-control" value="0" step="0.01" min="0">
              </div>
            </div>
          </div>

          <!-- Submit Button -->
           <div class="box-footer text-right">
          <input type="hidden" name="tambah" value="tambah">
          <!-- Tambahkan baris ini untuk membawa konteks ?jenis ke controller -->
          <input type="hidden" name="jenis" value="<?= isset($jenis) ? htmlspecialchars($jenis) : '' ; ?>">
          
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="<?= base_url('data/detailpelatihan' . (!empty($jenis) ? '?jenis='.urlencode($jenis) : '')); ?>" class="btn btn-danger">Kembali</a>
        </div>

        </div>
      </div>
    </form>
  </section>
</div>

<script>
$(function(){
  $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover', placement: 'right', container: 'body' });

  var isLatsar = <?= !empty($is_latsar) && $is_latsar ? 'true' : 'false'; ?>;

  // === INIT Select2 pada semua select ===
  $('.select2').select2({ width: '100%' });

  // === Khusus PJJ/PDWK: cegah duplikasi orang yang sama di 3 select (WI multi, WI rapat, Pengajar multi) ===
  if (!isLatsar) {
    var $wiMulti       = $('#wi_ids');
    var $wiRapat       = $('#wi_rapat_kelulusan');
    var $pengajarMulti = $('#pengajar_ids');
    var $jumlahAuto    = $('#jumlah_wi_pengajar');

    function updateJumlahWIPengajar() {
      var countWI       = ($wiMulti.val()       || []).length;
      var countPengajar = ($pengajarMulti.val() || []).length;
      var total = countWI + countPengajar; // Tidak menghitung WI rapat
      $jumlahAuto.val(total);
    }

    // Hanya disable konflik antar WI multi <-> Pengajar multi
    function enforceUniquePeople() {
      var selectedWI       = ($wiMulti.val() || []).map(String);
      var selectedPengajar = ($pengajarMulti.val() || []).map(String);

      function disableConflicts($sel, conflicts) {
        // Reset dulu semua opsi agar bersih dari state sebelumnya
        $sel.find('option').prop('disabled', false);

        // Disable opsi yang konflik KECUALI yang sedang terpilih di select tsb
        var selectedHere = ($sel.val() || []).map(String);
        $sel.find('option').each(function() {
          var val = $(this).attr('value');
          if (!val) return;
          var isSelectedHere = selectedHere.includes(val);
          var conflict       = conflicts.includes(val);
          if (conflict && !isSelectedHere) {
            $(this).prop('disabled', true);
          }
        });
        $sel.trigger('change.select2');
      }

      // Terapkan konflik dua arah WI <-> Pengajar
      disableConflicts($wiMulti,       selectedPengajar);
      disableConflicts($pengajarMulti, selectedWI);

      // Penting: Seluruh opsi WI Rapat selalu aktif (tidak pernah didisable)
      $wiRapat.find('option').prop('disabled', false);
      $wiRapat.trigger('change.select2');

      updateJumlahWIPengajar();
    }

    // Initial & on change
    enforceUniquePeople(); // hitung & sinkron awal
    $wiMulti.on('change', enforceUniquePeople);
    $pengajarMulti.on('change', enforceUniquePeople);

    // Perubahan WI Rapat tidak memengaruhi jumlah & tidak men-disable apa pun
    $wiRapat.on('change', function() {
      // tetap pastikan semua opsi available
      $wiRapat.find('option').prop('disabled', false);
      $wiRapat.trigger('change.select2');
    });
}


  // === LATSAR: ranking peserta (kode Anda tetap) ===
  if (isLatsar) {
    var $idPel = $('#id_pelatihan');
    var $rankSelects = $('.peserta-ranking');

    function populateRankingSelects(options) {
      $rankSelects.each(function(){
        var $sel = $(this);
        var current = $sel.val();
        $sel.prop('disabled', false).empty()
            .append('<option disabled selected>-- Pilih Peserta --</option>');
        options.forEach(function(row) {
          var opt = $('<option/>', { value: row.id_peserta, text: row.nama_peserta });
          $sel.append(opt);
        });
        if (current) { $sel.val(current).trigger('change.select2'); }
        else { $sel.trigger('change.select2'); }
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

    $idPel.on('change', function(){
      var id = $(this).val();
      if (!id) return;
      $.getJSON('<?= base_url('data/get_peserta_by_pelatihan'); ?>', { id_pelatihan: id }, function(res){
        populateRankingSelects(Array.isArray(res) ? res : []);
        enforceUniqueRanks();
      }).fail(function(){
        populateRankingSelects([]);
      });
    });

    $(document).on('change', '.peserta-ranking', function(){
      enforceUniqueRanks();
    });

    // init select2 untuk ranking juga
    $('.peserta-ranking').select2({ width: '100%' });
  }
});
</script>

