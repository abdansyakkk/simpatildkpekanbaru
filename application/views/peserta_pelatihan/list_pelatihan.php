<?php if(! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-users-text"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>

  <section class="content">
    <?php if(!empty($this->session->flashdata())){ echo $this->session->flashdata('pesan'); } ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jenis Pelatihan:</label>                  
                  <select class="form-control" id="filterJenis">
                    <option value="">Semua Jenis</option>
                    <option value="1">PJJ</option>
                    <option value="2">PDWK</option>
                    <option value="3">Latsar</option>
                  </select>
                </div>
              </div>
            </div>
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
                    <th>Kab/Kota</th>
                    <th>Tempat</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach($pelatihan->result_array() as $isi){ 
                    echo "<!-- DEBUG: " . print_r($isi, true) . " -->";?>

                  <tr data-jenis="<?= $isi['id_jenis_pelatihan'] ?>">
                    <td><?= $no; ?></td>
                    <td><?= htmlentities($isi['nama_pelatihan']); ?></td>
                    <td>
                      <?= ($isi['id_jenis_pelatihan'] == 1) ? 'PJJ' : (($isi['id_jenis_pelatihan'] == 2) ? 'PDWK' : (($isi['id_jenis_pelatihan'] == 3) ? 'Latsar' : 'UNKNOWN (' . $isi['id_jenis_pelatihan'] . ')')) ?>
                    </td>
                    <td><?= htmlentities($isi['kab_kota']); ?></td>
                    <td><?= htmlentities($isi['tempat']); ?></td>
                    <td><?= htmlentities($isi['tanggal_mulai_pelatihan']); ?></td>
                    <td><?= htmlentities($isi['tanggal_selesai_pelatihan']); ?></td>
                    <td><?= htmlentities($isi['tahun']); ?></td>
                    <td>
                      <a href="<?= base_url('data/listpesertapelatihan/'.$isi['id_pelatihan']); ?>">
                      <button class="btn btn-warning">
                          <i class="fa fa-users"></i> Tambah Peserta
                      </button>
                      </a>
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

<script>
$(document).ready(function() {
    // Inisialisasi tooltip
    $('[data-toggle="tooltip"]').tooltip();
    
    // Filter berdasarkan jenis pelatihan
    $('#filterJenis').change(function() {
        var jenis = $(this).val();
        
        if (jenis === '') {
            // Tampilkan semua baris
            $('tbody tr').show();
        } else {
            // Sembunyikan semua baris terlebih dahulu
            $('tbody tr').hide();
            
            // Tampilkan hanya yang sesuai filter
            $('tbody tr[data-jenis="' + jenis + '"]').show();
        }
        
        // Perbarui nomor urut
        updateRowNumbers();
    });
    
    // Fungsi untuk memperbarui nomor urut
    function updateRowNumbers() {
        var visibleRows = $('tbody tr:visible');
        visibleRows.each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Tombol cetak individu dengan parameter filter
    // $('.btn-cetak-individu').click(function(e) {
    //     e.preventDefault();
    //     var id = $(this).data('id');
    //     var jenis = $('#filterJenis').val();
        
    //     // Jika ada filter jenis yang dipilih, tambahkan parameter
    //     if (jenis !== '') {
    //         var url = '<?= base_url('data/generateLaporan/') ?>' + id + '?jenis=' + jenis;
    //     } else {
    //         var url = '<?= base_url('data/generateLaporan/') ?>' + id;
    //     }
        
    //     window.open(url, '_blank');
    // });
});
</script>