<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-users" style="color:green"></i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active"><i class="fa fa-user"></i> <?= $title_web; ?></li>
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
            <?php if ($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'Panitia') { ?>
              <a href="<?= base_url('data/pegawaitambah'); ?>">
                <button class="btn btn-primary">
                  <i class="fa fa-plus"></i> Tambah Pegawai
                </button>
              </a>
            <?php } ?>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Asal Satuan Kerja</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($pegawai->result_array() as $row) { ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= htmlentities($row['nama']); ?></td>
                      <td><?= htmlentities($row['NIP']); ?></td>
                      <td><?= htmlentities($row['asal_satker']); ?></td>
                      <td>
                        <?php if ($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'Panitia') { ?>
                          <a href="<?= base_url('data/pegawaiedit/' . $row['id_pegawai']); ?>">
                            <button class="btn btn-success"><i class="fa fa-edit"></i></button>
                          </a>
                          <a href="<?= base_url('data/pegawaidetail/' . $row['id_pegawai']); ?>">
                            <button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button>
                          </a>
                          <a href="<?= base_url('data/prosespegawai?id_pegawai=' . $row['id_pegawai']); ?>" onclick="return confirm('Anda yakin ingin menghapus data pegawai ini?');">
                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                          </a>
                        <?php } else { ?>
                          <a href="<?= base_url('data/pegawaidetail/' . $row['id_pegawai']); ?>">
                            <button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button>
                          </a>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php $no++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>
