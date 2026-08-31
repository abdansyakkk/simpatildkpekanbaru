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
            <?php if($this->session->userdata('level') == 'Petugas'){?>
              <a href="<?= base_url('data/tambahJenisPelatihan'); ?>"><button class="btn btn-primary">
                <i class="fa fa-plus"> </i> Tambah Jenis Pelatihan</button></a>
            <?php } ?>
          </div>

          <div class="box-body">
            <br/>
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Jenis Pelatihan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(isset($jenis_pelatihan) && !empty($jenis_pelatihan)) : ?>
                    <?php $no = 1; foreach($jenis_pelatihan as $isi){ ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= htmlentities($isi['nama_jenis_pelatihan']); ?></td>
                      <td>

                          <?php if($this->session->userdata('level') == 'Petugas'){ ?>
                              <!-- <a href="<?= base_url('data/jenispelatihanedit/'.$isi['id_jenis_pelatihan']); ?>"><button class="btn btn-success"><i class="fa fa-edit"></i></button></a> -->
                              <!-- <a href="<?= base_url('data/jenispelatihandetail/'.$isi['id_jenis_pelatihan']); ?>"><button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button></a> -->
                              <a href="<?= base_url('data/hapusjenispelatihan/'.$isi['id_jenis_pelatihan']); ?>" onclick="return confirm('Anda yakin pelatihan ini akan dihapus?');">
                                  <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                              </a>
                          <?php } else { ?>
                          <a href="<?= base_url('data/pelatihandetail/'.$isi['id_jenis_pelatihan']); ?>">
                              <button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button></a>
                          <?php } ?>
                      </td>
                    </tr>
                    <?php $no++; } ?>
                  <?php else : ?>
                    <tr><td colspan="3">Data Tidak Tersedia</td></tr>
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
