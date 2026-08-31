<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php
            $d = $this->db->query("SELECT * FROM tbl_login WHERE id_login='$idbo'")->row();
            if(isset($d->foto)){
          ?>
          <br/>
          <img src="<?php echo base_url();?>assets_style/image/<?php echo $d->foto;?>" alt="#" c
          lass="user-image" style="border:2px solid #fff;height:auto;width:100%;"/>
          <?php }else{?>
            <!--<img src="" alt="#" class="user-image" style="border:2px solid #fff;"/>-->
            <i class="fa fa-user fa-4x" style="color:#fff;"></i>
          <?php }?>
        </div>
        <div class="pull-left info" style="margin-top: 5px;">
          <p><?php echo $d->nama;?></p>
          <p><?= $d->level;?>
          </p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
		</div>
        <ul class="sidebar-menu" data-widget="tree">
			<?php if($this->session->userdata('level') == 'admin' || $this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'Panitia' || $this->session->userdata('level') == 'panitia'){?>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?php if($this->uri->uri_string() == 'dashboard'){ echo 'active';}?>">
                <a href="<?php echo base_url('dashboard');?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <?php if ($this->session->userdata('level') == 'Admin') {?>
            <li class="<?php if($this->uri->uri_string() == 'user'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'user/tambah'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'user/edit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="<?php echo base_url('user');?>" class="cursor">
                    <i class="fa fa-user"></i> <span>Data Pengguna</span></a>
			</li>
            <?php } ?>
            
            <!-- Code LDK Pekanbaru Menu Laporan PJJ-->

            <li class="treeview <?= ($this->uri->segment(1) == 'data' && $this->input->get('jenis') == 'PJJ') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-folder-open"></i>
                    <span>Pelatihan PJJ</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if($this->uri->uri_string() == 'data' && $this->input->get('jenis') == 'PJJ'){ echo 'active';}?>">
                        <a href="<?php echo base_url("data?jenis=PJJ");?>" class="cursor">
                            <span class="fa fa-file"></span> Data Pelatihan
                            
                        </a>
                    </li>
                    <li class="<?= ($this->uri->uri_string() == 'data/detailpelatihan' && $this->input->get('jenis') == 'PJJ') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data/detailpelatihan?jenis=PJJ");?>" class="cursor">
                            <span class="fa fa-list"></span> Detail Pelatihan
                            
                        </a>
                    </li>
                    <li class=" <?= ($this->uri->uri_string() == 'data/materipelatihan' && $this->input->get('jenis') == 'PJJ') ? 'active' : '' ?>
                        <?php if($this->uri->uri_string() == 'data/materipelatihantambah'){ echo 'active';}?>">
                        <a href="<?php echo base_url("data/materipelatihan?jenis=PJJ");?>" class="cursor">
                            <span class="fa fa-book"></span> Materi Pelatihan 
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Code LDK Pekanbaru Menu Laporan PDWK-->
            <li class="treeview <?= ($this->uri->segment(1) == 'data' && $this->input->get('jenis') == 'PDWK') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-folder-open"></i>
                    <span>Pelatihan PDWK</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($this->uri->uri_string() == 'data' && $this->input->get('jenis') == 'PDWK') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data?jenis=PDWK");?>" class="cursor">
                            <span class="fa fa-file"></span> Data Pelatihan
                            
                        </a>
                    </li>
                    <li class=" <?= ($this->uri->uri_string() == 'data/detailpelatihan' && $this->input->get('jenis') == 'PDWK') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data/detailpelatihan?jenis=PDWK");?>" class="cursor">
                            <span class="fa fa-list"></span> Detail Pelatihan
                            
                        </a>
                    </li>
                    <li class=" <?= ($this->uri->uri_string() == 'data/materipelatihan' && $this->input->get('jenis') == 'PDWK') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data/materipelatihan?jenis=PDWK");?>" class="cursor">
                            <span class="fa fa-book"></span> Materi Pelatihan 
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Code LDK Pekanbaru Menu Laporan Latsar CPNS-->
            <li class="treeview <?= ($this->uri->segment(1) == 'data' && $this->input->get('jenis') == 'Latsar') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-folder-open"></i>
                    <span>Pelatihan Dasar CPNS</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($this->uri->uri_string() == 'data' && $this->input->get('jenis') == 'Latsar') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data?jenis=Latsar");?>" class="cursor">
                            <span class="fa fa-file"></span> Data Pelatihan
                            
                        </a>
                    </li>
                    <li class=" <?= ($this->uri->uri_string() == 'data/pesertapelatihanjenis' && $this->input->get('jenis') == 'Latsar') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data/pesertapelatihanjenis?jenis=Latsar");?>" class="cursor">
                            <span class="fa fa-list"></span> Peserta Pelatihan
                            
                        </a>
                    </li>
                    <li class=" <?= ($this->uri->uri_string() == 'data/detailpelatihan' && $this->input->get('jenis') == 'Latsar') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data/detailpelatihan?jenis=Latsar");?>" class="cursor">
                            <span class="fa fa-list"></span> Detail Pelatihan
                            
                        </a>
                    </li>
                    <li class=" <?= ($this->uri->uri_string() == 'data/materi' && $this->input->get('jenis') == 'Latsar') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data/materi?jenis=Latsar");?>" class="cursor">
                            <span class="fa fa-book"></span> Materi
                        </a>
                    </li>
                    <li class=" <?= ($this->uri->uri_string() == 'data/pengajar' && $this->input->get('jenis') == 'Latsar') ? 'active' : '' ?>">
                        <a href="<?php echo base_url("data/pengajar?jenis=Latsar");?>" class="cursor">
                            <span class="fa fa-users"></span> Pengajar
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Code LDK Pekanbaru Menu Dokumen -->
             <li class="treeview <?php if($this->uri->uri_string() == 'data/dokumenpelatihan'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/dokumen'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/dokumendetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/dokumenedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="#">
                    <i class="fa fa-folder-open"></i>
                    <span>Lampiran Dokumen</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if($this->uri->uri_string() == 'data/dokumen'){ echo 'active';}?>
                        <?php if($this->uri->uri_string() == 'data/dokumentambah'){ echo 'active';}?>
                        <?php if($this->uri->uri_string() == 'data/dokumendetail/'.$this->uri->segment('3')){ echo 'active';}?>
                        <?php if($this->uri->uri_string() == 'data/dokumenedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                        <a href="<?php echo base_url("data/dokumen");?>" class="cursor">
                            <span class="fa fa-file"></span> Data Dokumen
                            
                        </a>
                    </li>
                    <li class=" <?php if($this->uri->uri_string() == 'data/dokumenpelatihan'){ echo 'active';}?>">
                        <a href="<?php echo base_url("data/dokumenpelatihan");?>" class="cursor">
                            <span class="fa fa-list"></span> Dokumen Pelatihan
                            
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Code LDK Pekanbaru Menu Dokumentasi Pelatihan-->
              <li class="treeview 
                <?php if($this->uri->uri_string() == 'data/dokumentasipelatihan'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="#">
                    <i class="fa fa-camera"></i>
                    <span>Dokumentasi Pelatihan</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if($this->uri->uri_string() == 'data/dokumentasipelatihan'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                            <a href="<?php echo base_url("data/dokumentasipelatihan");?>" class="cursor">
                            <span class="fa fa-tasks"></span> Kegiatan Pelatihan
                            
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Code LDK Pekanbaru Menu Peserta Pelatihan-->
              <li class="treeview 
                <?php if($this->uri->uri_string() == 'data/pesertapelatihan'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Peserta Pelatihan</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if($this->uri->uri_string() == 'data/pesertapelatihan'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                            <a href="<?php echo base_url("data/pesertapelatihan");?>" class="cursor">
                            <span class="fa fa-tasks"></span> List Peserta Pelatihan
                            
                        </a>
                    </li>
                </ul>
            </li>

                        <!-- Code LDK Pekanbaru Menu Cetak Laporan PJJ-->
              <li class="treeview 
                <?php if($this->uri->uri_string() == 'data/cetaklaporan'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="#">
                    <i class="fa fa-file"></i>
                    <span>Cetak Laporan</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if($this->uri->uri_string() == 'data/cetaklaporan'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                            <a href="<?php echo base_url("data/cetaklaporan");?>" class="cursor">
                            <span class="fa fa-tasks"></span> Laporan Pelatihan
                            
                        </a>
                    </li>
                    <li class=" <?php if($this->uri->uri_string() == 'data/cetaklampiranlaporan'){ echo 'active';}?>">
                        <a href="<?php echo base_url("data/cetaklampiranlaporan");?>" class="cursor">
                            <span class="fa fa-list"></span> Lampiran Dokumen
                            
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Code LDK Pekanbaru Menu Cetak Laporan PDWK-->
            <!-- <li class="treeview 
                <?php if($this->uri->uri_string() == 'data/cetaklaporanpdwk'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="#">
                    <i class="fa fa-file"></i>
                    <span>Cetak Laporan PDWK</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if($this->uri->uri_string() == 'data/cetaklaporanpdwk'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                            <a href="<?php echo base_url("data/cetaklaporanpdwk");?>" class="cursor">
                            <span class="fa fa-tasks"></span> Laporan Pelatihan PDWK
                            
                        </a>
                    </li>
                    <li class=" <?php if($this->uri->uri_string() == 'data/cetaklampiranlaporanpdwk'){ echo 'active';}?>">
                        <a href="<?php echo base_url("data/cetaklampiranlaporanpdwk");?>" class="cursor">
                            <span class="fa fa-list"></span> Lampiran Dokumen PDWK
                            
                        </a>
                    </li>
                </ul>
            </li> -->
            
            <!-- Code LDK Pekanbaru Menu Cetak Laporan Latsar-->
            

            <!-- <li class="<?php if($this->uri->uri_string() == 'data/generateLaporanLatsar'){ echo 'active';}?>">
                <a href="<?= base_url('data/generateLaporanLatsar'); ?>" >
                    <i class="fa fa-file"></i>
                    <span>Cetak Laporan Latsar</span>
                    </span>
                </a>
            </li> -->


            <!-- Code LDK Pekanbaru Menu Pegawai-->
            <li class="treeview 
                <?php if($this->uri->uri_string() == 'data/pegawai'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Pegawai</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if($this->uri->uri_string() == 'data/pegawai'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaitambah'){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaidetail/'.$this->uri->segment('3')){ echo 'active';}?>
                    <?php if($this->uri->uri_string() == 'data/pegawaiedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                            <a href="<?php echo base_url("data/pegawai");?>" class="cursor">
                            <span class="fa fa-users"></span> List Pegawai
                            
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Code LDK Menu Jabatan -->

                <li class="treeview
				<?php if($this->uri->uri_string() == 'data/role'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/roletambah'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/roledetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/roleedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="#">
                    <i class="fa fa-graduation-cap"></i>
                    <span>Jabatan</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
				    <li class="<?php if($this->uri->uri_string() == 'data/role'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/roletambah'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/roledetail/'.$this->uri->segment('3')){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/roleedit/'.$this->uri->segment('3')){ echo 'active';}?>">
                        <a href="<?php echo base_url("data/role");?>" class="cursor">
                            <span class="fa fa-list"></span> List Jabatan
                            
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Code LDK Menu Jenis Pelatihan -->

            <li class="<?php if($this->uri->uri_string() == 'data/jenispelatihan'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'data/tambahjenispelatihan'){ echo 'active';}?>
                <?php if($this->uri->uri_string() == 'data/editjenispelatihan/'.$this->uri->segment('3')){ echo 'active';}?>">
                <a href="<?php echo base_url('data/jenispelatihan');?>" class="cursor">
                    <i class="fa fa-book"></i> <span>Jenis Pelatihan</span></a>
			</li>
            

            <!-- ====================================================================================================================== -->
			<?php }?>
			<?php if($this->session->userdata('level') == 'Anggota'){?>
				<li class="<?php if($this->uri->uri_string() == 'transaksi'){ echo 'active';}?>">
					<a href="<?php echo base_url("transaksi");?>" class="cursor">
						<i class="fa fa-upload"></i> <span>Data Peminjaman </span>
					</a>
				</li>
				<li class="<?php if($this->uri->uri_string() == 'transaksi/kembali'){ echo 'active';}?>">
					<a href="<?php echo base_url("transaksi/kembali");?>" class="cursor">
						<i class="fa fa-upload"></i> <span>Data Pengambilan</span>
					</a>
				</li>
				<li class="<?php if($this->uri->uri_string() == 'data'){ echo 'active';}?>
				<?php if($this->uri->uri_string() == 'data/bukudetail/'.$this->uri->segment('3')){ echo 'active';}?>">
					<a href="<?php echo base_url("data");?>" class="cursor">
						<i class="fa fa-search"></i>  <span>Cari Buku</span>
					</a>
				</li>
				<li class="<?php if($this->uri->uri_string() == 'user/edit/'.$this->uri->segment('3')){ echo 'active';}?>">
					<a href="<?php echo base_url('user/edit/'.$this->session->userdata('ses_id'));?>" class="cursor">
						<i class="fa fa-user"></i>  <span>Data Anggota</span>
					</a>
				</li>
				<li class="">
					<a href="<?php echo base_url('user/detail/'.$this->session->userdata('ses_id'));?>" target="_blank" class="cursor">
						<i class="fa fa-print"></i> <span>Cetak kartu Anggota</span>
					</a>
				</li>
			<?php }?>
        </ul>
        <div class="clearfix"></div>
        <br/>
        <br/>
    </section>
    <!-- /.sidebar -->
  </aside>
