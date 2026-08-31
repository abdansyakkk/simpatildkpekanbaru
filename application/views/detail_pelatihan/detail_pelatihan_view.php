<!-- <?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"></i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-file-text"></i>&nbsp; <?= $title_web; ?></li>
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
            <?php if ($this->session->userdata('level') == 'Petugas') { ?>
              <a href="<?= base_url('data/detailpelatihantambah'); ?>">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Detail</button>
              </a>
            <?php } ?>
          </div>

          <div class="box-body">
            <br/>
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Penanggung Jawab</th>
                    <th>Ketua Panitia</th>
                    <th>Jabatan Peserta</th>
                    <th>Jumlah Peserta</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($detail_pelatihan)) : ?>
                    <?php $no = 1; foreach ($detail_pelatihan as $row) : ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlentities($row->nama_kegiatan ?? '-') ?></td>
                        <td><?= htmlentities($row->nama_penanggung_jawab ?? '-') ?></td>
                        <td><?= htmlentities($row->nama_ketua_panitia ?? '-') ?></td>
                        <td><?= htmlentities($row->jabatan_peserta ?? '-') ?></td>
                        <td><?= (int)$row->jumlah_peserta ?> Orang</td>
                        <td>
                          <?php if($this->session->userdata('level') == 'Petugas'){ ?>
                            <a href="<?= base_url('data/detailpelatihanedit/'.$row->id_detail_pelatihan); ?>">
                              <button class="btn btn-success btn-sm"><i class="fa fa-edit"></i></button>
                            </a>
                            <a href="<?= base_url('data/detailpelatihandetail/' . $row->id_detail_pelatihan); ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-search"></i> Check Detail
                            <a href="<?= base_url('data/prosesdetailpelatihan?id_detail_pelatihan='.$row->id_detail_pelatihan); ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?');">
                              <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </a>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="7" class="text-center">Data detail pelatihan tidak ditemukan.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div> -->

<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"></i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-file-text"></i>&nbsp; <?= $title_web; ?></li>
    </ol>
  </section>

  <section class="content">
    <?php if (!empty($this->session->flashdata())) { echo $this->session->flashdata('pesan'); } ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">

            <!-- Filter Tabs -->
            <!-- <ul class="nav nav-pills" style="margin-bottom:10px;">
              <?php
                $jenis = isset($jenis) ? $jenis : null;
                function act($j,$jenis){ return ($jenis===$j) ? 'class="active"' : ''; }
              ?>
              <li <?= $jenis===null ? 'class="active"' : ''?>><a href="<?= base_url('data/detailpelatihan'); ?>">Semua</a></li>
              <li <?= act('PJJ',$jenis) ?>><a href="<?= base_url('data/detailpelatihan?jenis=PJJ'); ?>">PJJ</a></li>
              <li <?= act('PDWK',$jenis) ?>><a href="<?= base_url('data/detailpelatihan?jenis=PDWK'); ?>">PDWK</a></li>
              <li <?= act('Latsar',$jenis) ?>><a href="<?= base_url('data/detailpelatihan?jenis=Latsar'); ?>">Latsar</a></li>
            </ul> -->

            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
              <a href="<?= base_url('data/detailpelatihantambah'.(!empty($jenis)?'?jenis='.urlencode($jenis):'')); ?>">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Detail</button>
              </a>
            <?php } ?>
          </div>

          <div class="box-body">
            <br/>
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Penanggung Jawab</th>
                    <th>Ketua Panitia</th>
                    <th>Jabatan Peserta</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($detail_pelatihan)) : ?>
                    <?php $no = 1; foreach ($detail_pelatihan as $row) : ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlentities($row->nama_kegiatan ?? '-') ?></td>
                        <td><?= htmlentities($row->nama_penanggung_jawab ?? '-') ?></td>
                        <td><?= htmlentities($row->nama_ketua_panitia ?? '-') ?></td>
                        <td><?= nl2br(htmlentities($row->jabatan_peserta ?? '-')) ?></td>
                        <td>
                          <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin'){ ?>
                            <a href="<?= base_url('data/detailpelatihanedit/'.$row->id_detail_pelatihan.(!empty($jenis)?'?jenis='.urlencode($jenis):'')); ?>" class="btn btn-success btn-sm">
                              <i class="fa fa-edit"></i>
                            </a>
                            <a href="<?= base_url('data/detailpelatihandetail/'.$row->id_detail_pelatihan.(!empty($jenis)?'?jenis='.urlencode($jenis):'')); ?>" class="btn btn-info btn-sm">
                              <i class="fa fa-search"></i> Check Detail
                            </a>
                            <a href="<?= base_url('data/prosesdetailpelatihan?id_detail_pelatihan='.$row->id_detail_pelatihan.(!empty($jenis)?'&jenis='.urlencode($jenis):'')); ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?');" class="btn btn-danger btn-sm">
                              <i class="fa fa-trash"></i>
                            </a>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <?php
                      $baseCols = 7; // No..Aksi
                      $extra = (!empty($is_latsar) && $is_latsar) ? 8 : 0;
                      $colspan = $baseCols + $extra;
                    ?>
                    <tr>
                      <td colspan="<?= $colspan; ?>" class="text-center">Data detail pelatihan tidak ditemukan.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>
