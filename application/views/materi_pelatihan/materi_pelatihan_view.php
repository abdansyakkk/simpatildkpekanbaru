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
    <?php if (!empty($this->session->flashdata())) {
      echo $this->session->flashdata('pesan');
    } ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin') { ?>
              <a href="<?= base_url('data/materipelatihantambah'); ?>">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Materi Pelatihan</button>
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
                    <th>Jumlah JP</th>
                    <th>Jumlah JP Dasar</th>
                    <th>Jumlah JP Inti</th>
                    <th>Jumlah JP Penunjang</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($materi_pelatihan)) : ?>
                    <?php $no = 1; foreach ($materi_pelatihan as $row) : ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlentities($row->nama_kegiatan ?? '-') ?></td>
                        <td><?= (int)$row->jumlah_jp ?> JP</td>
                        <td><?= (int)$row->jp_kel_dasar ?> JP</td>
                        <td><?= (int)$row->jp_kel_inti ?> JP</td>
                        <td><?= (int)$row->jp_kel_penunjang ?> JP</td>
                        <td>
                          <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin'){ ?>
                            <a href="<?= base_url('data/materipelatihanedit/'.$row->id_materi_pelatihan); ?>">
                              <button class="btn btn-success btn-sm"><i class="fa fa-edit"></i></button>
                            </a>
                            <a href="<?= base_url('data/materipelatihandetail/' . $row->id_materi_pelatihan); ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-search"></i> Check Detail
                            <a href="<?= base_url('data/prosesmateripelatihan?id_materi_pelatihan='.$row->id_materi_pelatihan); ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?');">
                              <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </a>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="7" class="text-center">Data materi pelatihan tidak ditemukan.</td>
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