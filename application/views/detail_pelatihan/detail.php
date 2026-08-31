<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Helpers
function dv_get_nama_pegawai($pegawais, $id) {
  if (empty($id)) return '<span class="text-muted" title="Data belum tersedia">-</span>';
  foreach ($pegawais as $p) {
    if ((string)$p['id_pegawai'] === (string)$id) return htmlspecialchars($p['nama']);
  }
  return '<span class="text-muted" title="Pegawai tidak ditemukan">-</span>';
}

function dv_split_names($s) {
  $s = trim((string)$s);
  if ($s === '' || $s === '-') return [];
  $parts = array_map('trim', explode(',', $s));
  return array_values(array_filter($parts, function($x){ return $x !== ''; }));
}

function dv_get_nama_kegiatan($pelatihans, $id) {
  if (empty($id)) return '<span class="text-muted">-</span>';
  foreach ($pelatihans as $k) {
    if ((string)$k['id_pelatihan'] === (string)$id) return htmlspecialchars($k['nama_kegiatan']);
  }
  return 'ID Kegiatan: '.htmlspecialchars($id);
}

function dv_get_nama_peserta($rank_map, $id) {
    if (empty($id)) return '<span class="text-muted">-</span>';
    if (isset($rank_map[$id])) return htmlspecialchars($rank_map[$id]);
    // fallback jika tidak ditemukan di map
    return 'ID ' . htmlspecialchars($id);
}

function dv_fmt_num($v, $fallback = 0) {
  return (isset($v) && $v !== '') ? (int)$v : (int)$fallback;
}

function dv_fmt_money($v) {
  $num = (isset($v) && $v !== '') ? (float)$v : 0;
  return 'Rp '.number_format($num, 2, ',', '.');
}

// If controller didn't pass $is_latsar, try to infer from $jenis
$is_latsar = !empty($is_latsar) ? $is_latsar : (!empty($jenis) && $jenis === 'Latsar');
?>

<style>
  .box.box-primary { border-top: 3px solid #00a65a; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 6px; }
  .section-title { font-weight: 600; margin: 25px 0 15px; font-size: 18px; border-left: 4px solid #3c8dbc; padding-left: 10px; color: #34495e; }
  .table-detail tr:nth-child(odd) { background-color: #f9f9f9; }
  .badge-custom { font-size: 13px; background-color: #3c8dbc; color: white; padding: 5px 8px; border-radius: 4px; }
  .text-muted { color: #999; font-style: italic; }
  /* Latsar grid */
  .lts-grid { display: flex; flex-wrap: wrap; gap: 10px; }
  .lts-col { flex: 1 1 260px; min-width: 240px; }
  .lts-grid-5 .lts-col { flex: 1 1 180px; min-width: 180px; }
  .list-compact { margin: 0; padding-left: 18px; }
.list-compact li { margin: 0; }

</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-eye text-green"></i> <?= htmlspecialchars($title_web); ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-eye"></i> <?= htmlspecialchars($title_web); ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-clipboard text-green"></i>
              Detail Pelatihan: <?= (int)$detail_pelatihan->id_detail_pelatihan; ?>
              <?php if (!empty($jenis)): ?>
                <span class="badge badge-custom" style="margin-left:6px;"><?= htmlspecialchars($jenis); ?></span>
              <?php endif; ?>
            </h3>
          </div>

          <div class="box-body">
            <!-- Informasi Kegiatan -->
            <div class="section-title"><i class="fa fa-info-circle text-blue"></i> Informasi Kegiatan</div>
            <table class="table table-bordered table-detail">
              <tr>
                <th style="width:30%">Nama Kegiatan</th>
                <td><?= dv_get_nama_kegiatan($pelatihans, $detail_pelatihan->id_pelatihan); ?></td>
              </tr>
            </table>

            <!-- Tim Penyelenggara (adaptif) -->
            <div class="section-title"><i class="fa fa-users text-blue"></i> Tim Penyelenggara</div>
            <?php if ($is_latsar): ?>
              <table class="table table-bordered table-detail">
                <tr><th style="width:30%">Penanggung Jawab</th><td><?= dv_get_nama_pegawai($pegawais, $detail_pelatihan->id_penanggung_jawab); ?></td></tr>
                <tr><th>Ketua Panitia</th><td><?= htmlentities($detail_pelatihan->nama_ketua_panitia ?? '-'); ?></td></tr>
                <tr><th>Akademis</th><td><?= htmlentities($detail_pelatihan->nama_akademis ?? '-'); ?></td></tr>
                <tr><th>Administrasi</th><td><?= htmlentities($detail_pelatihan->nama_administrasi ?? '-'); ?></td></tr>
                <tr><th>PIC Smartbangkom</th><td><?= htmlentities($detail_pelatihan->nama_pic_smartbangkom ?? '-'); ?></td></tr>
              </table>
            <?php else: ?>
              <table class="table table-bordered table-detail">
                <tr><th style="width:30%">Penanggung Jawab</th>
                    <td><?= dv_get_nama_pegawai($pegawais, $detail_pelatihan->id_penanggung_jawab); ?></td></tr>

                <tr><th>Ketua Panitia</th><td><?= htmlentities($detail_pelatihan->nama_ketua_panitia ?? '-'); ?></td></tr>
                <tr><th>Akademis</th><td><?= htmlentities($detail_pelatihan->nama_akademis ?? '-'); ?></td></tr>
                <tr><th>Keuangan</th><td><?= htmlentities($detail_pelatihan->nama_keuangan ?? '-'); ?></td></tr>
                <tr><th>Administrasi</th><td><?= htmlentities($detail_pelatihan->nama_administrasi ?? '-'); ?></td></tr>
              </table>
            <?php endif; ?>

            <?php if (!$is_latsar): ?>
  <?php
    // Safe defaults if controller didn’t set them for any reason
    $wi_names       = isset($wi_names)       && is_array($wi_names)       ? $wi_names       : [];
    $pengajar_names = isset($pengajar_names) && is_array($pengajar_names) ? $pengajar_names : [];
    $wi_rapat_name  = isset($wi_rapat_name)  ? $wi_rapat_name : null;
  ?>
  <style>
    .list-compact { margin: 0; padding-left: 18px; }
    .list-compact li { margin: 0; }
  </style>

  <div class="section-title"><i class="fa fa-chalkboard-teacher text-orange"></i> Widyaiswara & Pengajar</div>
  <table class="table table-bordered table-detail">
    <tr>
      <th style="width:30%">Widyaiswara</th>
      <td>
        <?php if (!empty($wi_names)): ?>
          <ol class="list-compact">
            <?php foreach ($wi_names as $nama): ?>
              <li><?= htmlspecialchars($nama) ?></li>
            <?php endforeach; ?>
          </ol>
        <?php else: ?>
          <span class="text-muted">-</span>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <th>WI Rapat Kelulusan</th>
      <td><?= $wi_rapat_name ? htmlspecialchars($wi_rapat_name) : '<span class="text-muted">-</span>'; ?></td>
    </tr>
    <tr>
      <th>Pengajar</th>
      <td>
        <?php if (!empty($pengajar_names)): ?>
          <ol class="list-compact">
            <?php foreach ($pengajar_names as $nama): ?>
              <li><?= htmlspecialchars($nama) ?></li>
            <?php endforeach; ?>
          </ol>
        <?php else: ?>
          <span class="text-muted">-</span>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <th>Jumlah WI & Pengajar</th>
      <td>
        <?= (int)$detail_pelatihan->jumlah_wi_pengajar; ?>
      </td>
    </tr>
    <tr>
      <th>WI D2/D3 / S1 / S2 / S3</th>
      <td>
        <?= (int)$detail_pelatihan->jumlah_pendidikan_wi_d2; ?>
        /
        <?= (int)$detail_pelatihan->jumlah_pendidikan_wi_s1; ?>
        /
        <?= (int)$detail_pelatihan->jumlah_pendidikan_wi_s2; ?>
        /
        <?= (int)$detail_pelatihan->jumlah_pendidikan_wi_s3; ?>
      </td>
    </tr>
  </table>
<?php endif; ?>


            <?php if ($is_latsar): ?>
              <!-- Penilaian Latsar -->
              <div class="section-title"><i class="fa fa-check-circle text-orange"></i> Penilaian Latsar</div>
              <table class="table table-bordered table-detail">
                <tr><th style="width:30%">Sangat Memuaskan (SM)</th><td><?= dv_fmt_num($detail_pelatihan->jml_peserta_nilai_sm); ?></td></tr>
                <tr><th>Memuaskan (M)</th><td><?= dv_fmt_num($detail_pelatihan->jml_peserta_nilai_m); ?></td></tr>
                <tr><th>Cukup Memuaskan (CM)</th><td><?= dv_fmt_num($detail_pelatihan->jml_peserta_nilai_cm); ?></td></tr>
                <tr><th>Dalam Lingkup (DL)</th><td><?= dv_fmt_num($detail_pelatihan->jml_peserta_nilai_dl); ?></td></tr>
                <tr><th>Tidak Melanjutkan (TM)</th><td><?= dv_fmt_num($detail_pelatihan->jml_peserta_tm); ?></td></tr>
              </table>

              <!-- Peringkat Peserta -->
              <div class="section-title"><i class="fa fa-trophy text-yellow"></i> Peringkat Peserta</div>
              <table class="table table-bordered table-detail">
                <tr>
                  <th style="width:30%">Peringkat 1</th>
                  <td><?= dv_get_nama_peserta(isset($rank_peserta_map)?$rank_peserta_map:[], $detail_pelatihan->peserta_peringkat_1); ?></td>
                </tr>
                <tr>
                  <th>Peringkat 2</th>
                  <td><?= dv_get_nama_peserta(isset($rank_peserta_map)?$rank_peserta_map:[], $detail_pelatihan->peserta_peringkat_2); ?></td>
                </tr>
                <tr>
                  <th>Peringkat 3</th>
                  <td><?= dv_get_nama_peserta(isset($rank_peserta_map)?$rank_peserta_map:[], $detail_pelatihan->peserta_peringkat_3); ?></td>
                </tr>
              </table>
            <?php else: ?>
              <!-- Informasi Peserta (hanya non-Latsar) -->
              <div class="section-title"><i class="fa fa-users text-purple"></i> Informasi Peserta</div>
              <table class="table table-bordered table-detail">
                <tr><th style="width:30%">Total Peserta</th><td><?= dv_fmt_num($detail_pelatihan->jumlah_peserta); ?></td></tr>
                <tr><th>Lulus / Tidak Lulus</th>
                    <td><?= dv_fmt_num($detail_pelatihan->jumlah_lulus); ?> / <?= dv_fmt_num($detail_pelatihan->jumlah_tidak_lulus); ?></td>
                </tr>
                <tr><th>Peserta ASN / Non-ASN</th>
                    <td><?= dv_fmt_num($detail_pelatihan->jumlah_peserta_asn); ?> / <?= dv_fmt_num($detail_pelatihan->jumlah_peserta_non_asn); ?></td>
                </tr>
                <tr><th>Laki-laki / Perempuan</th>
                    <td><?= dv_fmt_num($detail_pelatihan->jumlah_peserta_laki); ?> / <?= dv_fmt_num($detail_pelatihan->jumlah_peserta_wanita); ?></td>
                </tr>
                <tr><th>Pendidikan Peserta (SMA / D3 / S1 / S2 / S3)</th>
                    <td>
                      <?= dv_fmt_num($detail_pelatihan->jumlah_pendidikan_peserta_sma); ?> /
                      <?= dv_fmt_num($detail_pelatihan->jumlah_pendidikan_peserta_d3); ?> /
                      <?= dv_fmt_num($detail_pelatihan->jumlah_pendidikan_peserta_s1); ?> /
                      <?= dv_fmt_num($detail_pelatihan->jumlah_pendidikan_peserta_s2); ?> /
                      <?= dv_fmt_num($detail_pelatihan->jumlah_pendidikan_peserta_s3); ?>
                    </td>
                </tr>
                <tr><th>Jabatan Peserta</th>
                    <td><?= !empty($detail_pelatihan->jabatan_peserta) ? nl2br(htmlspecialchars($detail_pelatihan->jabatan_peserta)) : '<span class="text-muted">-</span>'; ?></td>
                </tr>
              </table>
            <?php endif; ?>

            <!-- Anggaran -->
            <div class="section-title"><i class="fa fa-money text-green"></i> Anggaran</div>
            <table class="table table-bordered table-detail">
              <tr><th style="width:30%">RAB</th><td><?= dv_fmt_money($detail_pelatihan->rab); ?></td></tr>
              <tr><th>Realisasi</th><td><?= dv_fmt_money($detail_pelatihan->realisasi); ?></td></tr>
            </table>

            <!-- Metadata -->
            <div class="section-title"><i class="fa fa-clock-o text-gray"></i> Metadata</div>
            <table class="table table-bordered table-detail">
              <tr><th style="width:30%">Dibuat pada</th><td><?= !empty($detail_pelatihan->created_at) ? date('d-m-Y H:i:s', strtotime($detail_pelatihan->created_at)) : '-'; ?></td></tr>
              <tr><th>Diperbarui pada</th><td><?= !empty($detail_pelatihan->updated_at) ? date('d-m-Y H:i:s', strtotime($detail_pelatihan->updated_at)) : '-'; ?></td></tr>
            </table>

            <div class="text-right">
              <a href="<?= base_url('data/detailpelatihan' . (!empty($jenis) ? '?jenis='.urlencode($jenis) : '')); ?>" class="btn btn-danger btn-md">
                <i class="fa fa-arrow-left"></i> Kembali
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
