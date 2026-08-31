<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Frequently Asked Questions (FAQ)
            <small>Panduan Penggunaan Sistem</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">FAQ Sistem</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pertanyaan Umum</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="panel-group" id="accordion">
                            <!-- FAQ Item 1 -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true">
                                            <i class="fa fa-question-circle text-blue"></i> Bagaimana cara menambahkan pelatihan?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse1" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <p>Klik menu <strong>Pelatihan PJJ</strong>, kemudian klik submenu <strong>Data Pelatihan</strong>. Selanjutnya akan tampil list pelatihan PJJ, kemudian klik tombol <span class="label label-primary">Tambah Pelatihan</span>. Pengguna dapat menambah pelatihan sesuai pelatihan yang sudah diselenggarakan.</p>
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> Pastikan data pelatihan yang dimasukkan sudah valid dan sesuai dengan pelatihan yang telah dilaksanakan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- FAQ Item 2 -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                                            <i class="fa fa-question-circle text-blue"></i> Bagaimana cara menambah informasi detail pelatihan dan materi pelatihan?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p>Sebelum menambahkan detail pelatihan dan materi pelatihan, admin wajib menambahkan data pelatihan PJJ terkait terlebih dahulu. Informasi detail pelatihan dan materi pelatihan akan terhubung ke data pelatihan.</p>
                                        <ol>
                                            <li>Pastikan pelatihan sudah dibuat di menu Data Pelatihan</li>
                                            <li>Klik menu <strong>Pelatihan PJJ</strong> > <strong>Detail Pelatihan</strong></li>
                                            <li>Pilih pelatihan yang akan ditambahkan detail</li>
                                            <li>Klik tombol <span class="label label-primary">Tambah Detail</span></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- FAQ Item 3 -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                                            <i class="fa fa-question-circle text-blue"></i> Jika belum tersedia data panitia/widyaiswara, bagaimana cara menambahkan data tersebut?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse3" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p>Pengguna dapat mengakses menu <strong>Pegawai</strong> terlebih dahulu dan menambahkan data pegawai yang ingin ditambahkan.</p>
                                        <div class="callout callout-success">
                                            <h4><i class="fa fa-lightbulb-o"></i> Tips</h4>
                                            <p>Pastikan data pegawai yang dimasukkan sudah lengkap termasuk peran atau jabatan mereka (contoh : panitia/widyaiswara) agar mudah dalam proses pengisian data laporan pelatihan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- FAQ Item 4 -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                                            <i class="fa fa-question-circle text-blue"></i> Bagaimana cara menambah lampiran pelatihan?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse4" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ol>
                                            <li>Klik Menu <strong>Lampiran Dokumen</strong></li>
                                            <li>Klik submenu <strong>Dokumen Pelatihan</strong></li>
                                            <li>Pilih pelatihan yang ingin ditambahkan lampiran dokumen</li>
                                            <li>Klik <span class="label label-warning">Tambah Dokumen</span>, maka akan muncul form upload dokumen</li>
                                            <li>Pengguna dapat memilih jenis lampiran dan mengupload file dokumen dalam format PDF</li>
                                        </ol>
                                        <div class="alert alert-warning">
                                            <i class="fa fa-warning"></i> Pastikan file yang diupload berformat PDF dan ukuran tidak melebihi 2 MB.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- FAQ Item 5 -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                                            <i class="fa fa-question-circle text-blue"></i> Bagaimana cara menambah foto dokumentasi pelatihan?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse5" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ol>
                                            <li>Klik Menu <strong>Dokumentasi Pelatihan</strong></li>
                                            <li>Klik submenu <strong>Kegiatan Pelatihan</strong> maka akan muncul list pelatihan PJJ yang ditambahkan sebelumnya</li>
                                            <li>Klik tombol <span class="label label-warning">Tambah Kegiatan</span> pada Pelatihan PJJ yang diinginkan</li>
                                            <li>Pada halaman Kegiatan Pelatihan PJJ terkait, pengguna dapat menambahkan jadwal kegiatan pelatihan berdasarkan sesi, hari dan nama kegiatan beserta narasumbernya</li>
                                            <li>Jika pengguna berhasil menambahkan kegiatan pelatihan, selanjutnya dapat menambahkan foto dokumentasi pada tombol <button class="btn btn-primary btn-xs"><i class="fa fa-upload"></i> Foto</button> dan pilih foto yang diinginkan sesuai kebutuhan</li>
                                            <li>Jika sudah berhasil mengupload foto, pengguna dapat melihat foto dengan klik tombol <button class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Lihat Foto</button>, maka akan muncul galeri foto kegiatan bersangkutan</li>
                                        </ol>
                                        <div class="callout callout-info">
                                            <h4><i class="fa fa-camera"></i> Dokumentasi</h4>
                                            <p>Foto dokumentasi yang baik adalah foto yang jelas menampilkan kegiatan pelatihan dan peserta yang terlibat.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-center">
                        <p class="text-muted">Jika Anda memiliki pertanyaan lain yang belum tercantum di atas, silakan hubungi administrator sistem.</p>
                        <a href="#" class="btn btn-default"><i class="fa fa-envelope"></i> Hubungi Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>