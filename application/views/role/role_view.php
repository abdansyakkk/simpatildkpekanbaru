<?php if(! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i> <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-users"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>

  <section class="content">
    <?php if(!empty($this->session->flashdata())){ echo $this->session->flashdata('pesan'); } ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <?php if($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'Panitia'){ ?>
              <a href="<?= base_url('data/roletambah'); ?>">
                <button class="btn btn-primary">
                  <i class="fa fa-plus"></i> Tambah Role
                </button>
              </a>
            <?php } ?>
          </div>

          <div class="box-body">
            <br/>
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Role</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach($role->result_array() as $isi){ ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= htmlentities($isi['nama_role']); ?></td>
                      <td>
                        <?php if($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'Panitia'){ ?>
                          <a href="<?= base_url('data/roleedit/'.$isi['id_role']); ?>">
                            <button class="btn btn-success"><i class="fa fa-edit"></i></button>
                          </a>
                          <a href="<?= base_url('data/prosesrole?role_id='.$isi['id_role']); ?>" onclick="return confirm('Anda yakin ingin menghapus role ini?');">
                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                          </a>
                        <?php } else { ?>
                          <span class="text-muted">Tidak ada aksi</span>
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
