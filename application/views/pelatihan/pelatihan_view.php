<?php if(! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-file-text"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>

  <section class="content">
    <?php if(!empty($this->session->flashdata())){ echo $this->session->flashdata('pesan'); } ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin'){
                $current_jenis = $this->input->get('jenis');
                $tambah_url = base_url('data/pelatihantambah');

                if ($current_jenis) {
                  $tambah_url .= '?jenis=' . $current_jenis;
                }
              ?>
              <a href="<?= $tambah_url?>"><button class="btn btn-primary">
                <i class="fa fa-plus"> </i> Tambah Pelatihan</button></a>
            <?php } ?>
          </div>

          <div class="box-body">
            <br/>
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pelatihan</th>
                    <th>Jenis Pelatihan</th>
                    <th>Provinsi</th>
                    <th>Kab/Kota</th>
                    <th>Tempat</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                 
                  <?php $no = 1; foreach($pelatihan->result_array() as $isi){ ?>
                  <tr>
                    <td><?= $no; ?></td>
                    <td><?= htmlentities($isi['nama_pelatihan']); ?></td>
                    <td><?= htmlentities($isi['nama_jenis_pelatihan'] ?? 'Belum ada jenis') ?></td>
                    <td><?= htmlentities($isi['provinsi']); ?></td>
                    <td><?= htmlentities($isi['kab_kota']); ?></td>
                    <td><?= htmlentities($isi['tempat']); ?></td>
                    <td><?= htmlentities($isi['tanggal_mulai_pelatihan']); ?></td>
                    <td><?= htmlentities($isi['tanggal_selesai_pelatihan']); ?></td>
                    <td><?= htmlentities($isi['tahun']); ?></td>
                    <td>
                      <?php if($this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'Admin'){ ?>
                        <a href="<?= base_url('data/pelatihanedit/'.$isi['id_pelatihan']); ?>"><button class="btn btn-success"><i class="fa fa-edit"></i></button></a>
                        <a href="<?= base_url('data/pelatihandetail/'.$isi['id_pelatihan']); ?>"><button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button></a>
                        <a href="<?= base_url('data/prosespelatihan?id_pelatihan='.$isi['id_pelatihan']); ?>" onclick="return confirm('Anda yakin pelatihan ini akan dihapus?');">
                          <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </a>
                      <?php } else { ?>
                        <a href="<?= base_url('data/pelatihandetail/'.$isi['id_pelatihan']); ?>">
                          <button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button></a>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php $no++; } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
