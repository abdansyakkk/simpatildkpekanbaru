<?php
require 'vendor/autoload.php';
defined('BASEPATH') OR exit('No direct script access allowed');


    // Inisialisasi PHPWord
    require_once FCPATH . 'vendor/autoload.php';
    use PhpOffice\PhpWord\PhpWord;
    use PhpOffice\PhpWord\IOFactory;
    use PhpOffice\PhpWord\SimpleType\Jc;


class Data extends CI_Controller {
	function __construct(){
	 	parent::__construct();

		$this->load->library('wordGenerator');
		$this->load->library('wordGenerator_pdwk');
		$this->load->library('wordgenerator_latsar');
		$this->load->model('M_Admin');
		$this->load->library('form_validation');
		$this->load->helper('date');
        $this->load->library('upload');                 // <-- add this line
		
		//validasi jika user belum login
		$this->data['CI'] =& get_instance();
		$this->load->helper(array('form', 'url', 'excel'));
		$this->load->model('M_Admin');
			if($this->session->userdata('masuk_perpus') != TRUE){
					$url=base_url('login');
					redirect($url);
			}
	}

	// public function index()
	// {
	// 	$this->data['idbo'] = $this->session->userdata('ses_id');
	// 	$this->data['buku'] =  $this->db->query("SELECT * FROM tbl_buku ORDER BY id_buku DESC");
    //     $this->data['title_web'] = 'Data Buku';
    //     $this->load->view('header_view',$this->data);
    //     $this->load->view('sidebar_view',$this->data);
    //     $this->load->view('buku/buku_view',$this->data);
    //     $this->load->view('footer_view',$this->data);
	// }

	// 1) Controller::index()  — (opsional: sedikit dirapikan, logic sama)
public function index()
{
    $this->data['idbo'] = $this->session->userdata('ses_id');
    $panitia_id = $this->session->userdata('id_login');
    $level = $this->session->userdata('level');

    $jenis = $this->input->get('jenis', TRUE); // 'PJJ' | 'PDWK' | 'Latsar'
    $id_jenis = null;

    if ($jenis === 'PJJ') {
        $id_jenis = 1;
        $this->data['title_web'] = 'Data Pelatihan PJJ';
    } elseif ($jenis === 'PDWK') {
        $id_jenis = 2;
        $this->data['title_web'] = 'Data Pelatihan PDWK';
    } elseif ($jenis === 'Latsar') {
        $id_jenis = 3;
        $this->data['title_web'] = 'Data Pelatihan Dasar CPNS';
    } else {
        // fallback (opsional)
        $this->data['title_web'] = 'Data Pelatihan';
    }

    $this->load->model('M_Admin');

    if ($level === 'admin' || $level === 'Admin') {
        $this->data['pelatihan'] = $this->M_Admin->get_pelatihan_by_jenis($id_jenis);
    } else {
        $this->data['pelatihan'] = $this->M_Admin->get_pelatihan_by_panitia($id_jenis, $panitia_id);
    }

    if ($this->session->userdata('level') == 'Panitia') {
        $this->data['level'] = 'Admin';
    } else {
        $this->data['level'] = $this->session->userdata('level');
    }

    // $this->data['pelatihan'] = $this->M_Admin->get_pelatihan_by_jenis($id_jenis);
    $this->data['jenis_pelatihan'] = $jenis;

    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('pelatihan/pelatihan_view', $this->data);
    $this->load->view('footer_view', $this->data);
}


	
	
	// Code LDK Pekanbaru Pelatihan
		public function pelatihantambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		// Ambil parameter jenis dari URL
		$jenis = $this->input->get('jenis');
		
		// Set default jenis pelatihan
		$this->data['default_jenis'] = '';
		
		// Jika ada parameter jenis, set default
		if ($jenis == 'PJJ') {
			$this->data['default_jenis'] = 1; // ID untuk PJJ
		} elseif ($jenis == 'PDWK') {
			$this->data['default_jenis'] = 2; // ID untuk PDWK
		} elseif ($jenis == 'Latsar') {
			$this->data['default_jenis'] = 3; // ID untuk Latsar
		}

		$this->data['jenis_pelatihan_options'] = $this->db->get('tbl_jenis_pelatihan')->result();
		$this->data['pegawais'] =  $this->db->query("SELECT * FROM tbl_pegawai ORDER BY id_pegawai DESC")->result_array();
		$this->data['roles'] =  $this->db->query("SELECT * FROM tbl_role ORDER BY id_role DESC")->result_array();

        $this->data['title_web'] = 'Tambah Pelatihan';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('pelatihan/pelatihan_tambah_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function pelatihanedit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_pelatihan','id_pelatihan',$this->uri->segment('3'));
		

		if($count > 0)
		{
			$this->data['pelatihan'] = $this->M_Admin->get_tableid_edit('tbl_pelatihan','id_pelatihan',$this->uri->segment('3'));

			//Ambil data jenis pelatihan
			$this->data['jenis_pelatihan'] = $this->M_Admin->get_tableid_edit(
				'tbl_jenis_pelatihan', 
				'id_jenis_pelatihan', 
				$this->data['pelatihan']->id_jenis_pelatihan
			);

			// ✅ Tambahkan ini
			$this->data['pegawais'] = $this->db->query("SELECT * FROM tbl_pegawai ORDER BY id_pegawai DESC")->result_array();
			$this->data['roles'] = $this->db->query("SELECT * FROM tbl_role ORDER BY id_role DESC")->result_array();

		} else {
			echo '<script>alert("DATA PELATIHAN TIDAK DITEMUKAN");window.location="'.base_url('data').'"</script>';
		}

		$this->data['title_web'] = 'Edit Data Pelatihan';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('pelatihan/pelatihan_edit_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}


	public function pelatihandetail()
	{
    $this->data['idbo'] = $this->session->userdata('ses_id');

    // Cek apakah data pelatihan dengan ID yang dimaksud ada
    $count = $this->M_Admin->CountTableId('tbl_pelatihan', 'id_pelatihan', $this->uri->segment('3'));

    if ($count > 0) {
        // Ambil data pelatihan
        $this->data['pelatihan'] = $this->M_Admin->get_tableid_edit('tbl_pelatihan', 'id_pelatihan', $this->uri->segment('3'));

		//Ambil data jenis pelatihan
		$this->data['jenis_pelatihan'] = $this->M_Admin->get_tableid_edit(
			'tbl_jenis_pelatihan', 
			'id_jenis_pelatihan', 
			$this->data['pelatihan']->id_jenis_pelatihan
		);

        // Ambil data pegawai & role sebagai referensi detail pejabat pembuka/penutup
        $this->data['pegawais'] = $this->db->query("SELECT * FROM tbl_pegawai ORDER BY id_pegawai DESC")->result_array();
        $this->data['roles'] = $this->db->query("SELECT * FROM tbl_role ORDER BY id_role DESC")->result_array();

    } else {
        // Jika tidak ditemukan, tampilkan notifikasi dan redirect
        echo '<script>alert("DATA PELATIHAN TIDAK DITEMUKAN");window.location="' . base_url('data') . '"</script>';
        return;
    }

    $this->data['title_web'] = 'Detail Data Pelatihan';
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('pelatihan/pelatihan_detail', $this->data); // Pastikan file ini ada
    $this->load->view('footer_view', $this->data);
	}

	public function prosespelatihan()
{
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        redirect(base_url('login'));
    }

    // util kecil untuk map id -> query string 'jenis'
    $mapJenis = function ($id) {
        switch ((int)$id) {
            case 1: return 'PJJ';
            case 2: return 'PDWK';
            case 3: return 'Latsar';
            default: return 'PDWK';
        }
    };

    // // === DELETE PELATIHAN ===
    // $get_id_pelatihan = $this->input->get('id_pelatihan', TRUE);
    // if (!empty($get_id_pelatihan)) {
    //     $id_pelatihan = (int)$get_id_pelatihan;

    //     // Ambil data untuk memastikan ada & mengetahui jenis utk redirect
    //     $pelatihan = $this->M_Admin->get_tableid_edit('tbl_pelatihan', 'id_pelatihan', $id_pelatihan);
    //     if (!$pelatihan) {
    //         $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
    //             <p>Data pelatihan tidak ditemukan.</p></div></div>');
    //         return redirect(base_url('data?jenis=PDWK'));
    //     }

    //     $this->db->set('deleted_at', date('Y-m-d H:i:s'));
    //     $this->db->where('id_pelatihan', $id_pelatihan);
    //     $this->db->update('tbl_pelatihan');

    //     $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-warning">
    //         <p>Berhasil Hapus Data Pelatihan!</p></div></div>');

    //     // redirect berdasarkan jenis dari record yang dihapus
    //     $redirJenis = isset($pelatihan->id_jenis_pelatihan) ? $mapJenis($pelatihan->id_jenis_pelatihan) : 'PDWK';
    //     return redirect(base_url('data?jenis=' . $redirJenis));
    // }

    // === DELETE PELATIHAN (HARD DELETE) ===
$get_id_pelatihan = $this->input->get('id_pelatihan', TRUE);

if (!empty($get_id_pelatihan)) {
    $id_pelatihan = (int)$get_id_pelatihan;

    // Ambil data untuk memastikan ada & mengetahui jenis utk redirect
    $pelatihan = $this->M_Admin->get_tableid_edit('tbl_pelatihan', 'id_pelatihan', $id_pelatihan);
    
    if (!$pelatihan) {
        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
            <p>Data pelatihan tidak ditemukan.</p></div></div>');
        return redirect(base_url('data?jenis=PDWK'));
    }

    // HARD DELETE
    $this->db->where('id_pelatihan', $id_pelatihan);
    $this->db->delete('tbl_pelatihan');

    $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-warning">
        <p>Berhasil Hapus Data Pelatihan Secara Permanen!</p></div></div>');

    // redirect berdasarkan jenis dari record yang dihapus
    $redirJenis = isset($pelatihan->id_jenis_pelatihan) 
        ? $mapJenis($pelatihan->id_jenis_pelatihan) 
        : 'PDWK';

    return redirect(base_url('data?jenis=' . $redirJenis));
}

    // === TAMBAH PELATIHAN ===
    if (!empty($this->input->post('tambah'))) {
        $post = $this->input->post(NULL, TRUE); // XSS filter aktif

        // Validasi id_jenis_pelatihan ke tabel referensi
        $id_jenis = isset($post['id_jenis_pelatihan']) ? (int)$post['id_jenis_pelatihan'] : 0;
        $jenis_row = $this->db->select('id_jenis_pelatihan')
            ->from('tbl_jenis_pelatihan')
            ->where('id_jenis_pelatihan', $id_jenis)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->get()->row();

        if (!$jenis_row) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
                <p>Jenis pelatihan tidak valid. Pilih PJJ, PDWK, atau Latsar.</p></div>');
            return redirect(base_url('data/pelatihantambah'));
        }

        // (opsional) Validasi tanggal dasar
        $tgl_mulai   = $post['tanggal_mulai']   ?? NULL;
        $tgl_selesai = $post['tanggal_selesai'] ?? NULL;
        if ($tgl_mulai && $tgl_selesai && strtotime($tgl_mulai) > strtotime($tgl_selesai)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
                <p>Tanggal mulai pelatihan tidak boleh lebih besar dari tanggal selesai.</p></div>');
            return redirect(base_url('data/pelatihantambah'));
        }

        $data = array(
            'id_jenis_pelatihan'        => $id_jenis,
            'nama_kegiatan'             => isset($post['nama_kegiatan']) ? trim($post['nama_kegiatan']) : '',
            'nama_pelatihan'            => isset($post['nama_pelatihan']) ? trim($post['nama_pelatihan']) : '',
            'provinsi'                  => isset($post['provinsi']) ? trim($post['provinsi']) : '',
            'kab_kota'                  => isset($post['kab_kota']) ? trim($post['kab_kota']) : '',
            'tempat'                    => isset($post['tempat']) ? trim($post['tempat']) : '',
            'alamat'                    => isset($post['alamat']) ? trim($post['alamat']) : '',
            'tanggal_mulai_pelatihan'   => $tgl_mulai,
            'tanggal_selesai_pelatihan' => $tgl_selesai,
            'bulan_ttd_lap'             => isset($post['bulan_ttd']) ? trim($post['bulan_ttd']) : '',
            'tahun'                     => isset($post['tahun']) ? trim($post['tahun']) : '',
            'hari_tanggal_pembukaan'    => $post['hari_tanggal_pembukaan'] ?? NULL,
            'waktu_pembukaan'           => $post['waktu_pembukaan'] ?? NULL,
            'id_pejabat_pembuka'        => isset($post['id_pejabat_pembuka']) ? (int)$post['id_pejabat_pembuka'] : NULL,
            'id_role_pembuka'           => isset($post['id_role_pembuka']) ? (int)$post['id_role_pembuka'] : NULL,
            'hari_tanggal_penutupan'    => $post['hari_tanggal_penutupan'] ?? NULL,
            'waktu_penutupan'           => $post['waktu_penutupan'] ?? NULL,
            'id_pejabat_penutup'        => isset($post['id_pejabat_penutup']) ? (int)$post['id_pejabat_penutup'] : NULL,
            'id_role_penutup'           => isset($post['id_role_penutup']) ? (int)$post['id_role_penutup'] : NULL,
            'created_at'                => date('Y-m-d H:i:s'),
            'updated_at'                => date('Y-m-d H:i:s'),
        );

        $this->db->insert('tbl_pelatihan', $data);

        $id_pelatihan = $this->db->insert_id();

        $panitia_id = $this->session->userdata('id_login');

        $this->db->insert('tbl_panitia_pelatihan',[
            'pelatihan_id' => $id_pelatihan,
            'panitia_id' => $panitia_id,
            'peran' => 'Panitia'
        ]);

        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
            <p>Tambah Data Pelatihan Berhasil!</p></div></div>');

        return redirect(base_url('data?jenis=' . $mapJenis($id_jenis)));
    }

    // === EDIT PELATIHAN ===
    if (!empty($this->input->post('edit'))) {
        $post = $this->input->post(NULL, TRUE);

        $id_edit = (int)$post['edit'];

        // Validasi id_jenis_pelatihan ke tabel referensi
        $id_jenis = isset($post['id_jenis_pelatihan']) ? (int)$post['id_jenis_pelatihan'] : 0;
        $jenis_row = $this->db->select('id_jenis_pelatihan')
            ->from('tbl_jenis_pelatihan')
            ->where('id_jenis_pelatihan', $id_jenis)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->get()->row();

        if (!$jenis_row) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
                <p>Jenis pelatihan tidak valid. Pilih PJJ, PDWK, atau Latsar.</p></div>');
            return redirect(base_url('data/pelatihanedit/'.$id_edit));
        }

        // (opsional) Validasi tanggal dasar
        $tgl_mulai   = $post['tanggal_mulai']   ?? NULL;
        $tgl_selesai = $post['tanggal_selesai'] ?? NULL;
        if ($tgl_mulai && $tgl_selesai && strtotime($tgl_mulai) > strtotime($tgl_selesai)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
                <p>Tanggal mulai pelatihan tidak boleh lebih besar dari tanggal selesai.</p></div>');
            return redirect(base_url('data/pelatihanedit/'.$id_edit));
        }

        $data = array(
            'id_jenis_pelatihan'        => $id_jenis,
            'nama_kegiatan'             => isset($post['nama_kegiatan']) ? trim($post['nama_kegiatan']) : '',
            'nama_pelatihan'            => isset($post['nama_pelatihan']) ? trim($post['nama_pelatihan']) : '',
            'provinsi'                  => isset($post['provinsi']) ? trim($post['provinsi']) : '',
            'kab_kota'                  => isset($post['kab_kota']) ? trim($post['kab_kota']) : '',
            'tempat'                    => isset($post['tempat']) ? trim($post['tempat']) : '',
            'tanggal_mulai_pelatihan'   => $tgl_mulai,
            'tanggal_selesai_pelatihan' => $tgl_selesai,
            'bulan_ttd_lap'             => isset($post['bulan_ttd']) ? trim($post['bulan_ttd']) : '',
            'tahun'                     => isset($post['tahun']) ? trim($post['tahun']) : '',
            'hari_tanggal_pembukaan'    => $post['hari_tanggal_pembukaan'] ?? NULL,
            'waktu_pembukaan'           => $post['waktu_pembukaan'] ?? NULL,
            'id_pejabat_pembuka'        => isset($post['id_pejabat_pembuka']) ? (int)$post['id_pejabat_pembuka'] : NULL,
            'id_role_pembuka'           => isset($post['id_role_pembuka']) ? (int)$post['id_role_pembuka'] : NULL,
            'hari_tanggal_penutupan'    => $post['hari_tanggal_penutupan'] ?? NULL,
            'waktu_penutupan'           => $post['waktu_penutupan'] ?? NULL,
            'id_pejabat_penutup'        => isset($post['id_pejabat_penutup']) ? (int)$post['id_pejabat_penutup'] : NULL,
            'id_role_penutup'           => isset($post['id_role_penutup']) ? (int)$post['id_role_penutup'] : NULL,
            'updated_at'                => date('Y-m-d H:i:s'),
        );

        $this->db->where('id_pelatihan', $id_edit);
        $this->db->update('tbl_pelatihan', $data);

        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
            <p>Edit Data Pelatihan Berhasil!</p></div></div>');

        // redirect ke list sesuai jenis yang baru disimpan
        return redirect(base_url('data?jenis=' . $mapJenis($id_jenis)));
    }

    // Default fallback
    redirect(base_url('data?jenis=PDWK'));
}

	// public function prosespelatihan()
	// {
	// 	if ($this->session->userdata('masuk_perpus') != TRUE) {
	// 		$url = base_url('login');
	// 		redirect($url);
	// 	}

	// 	// === DELETE PELATIHAN ===
	// 	if (!empty($this->input->get('id_pelatihan'))) {
	// 		$id_pelatihan = htmlentities($this->input->get('id_pelatihan')); // ✅ Tambahkan baris ini
	// 		$pelatihan = $this->M_Admin->get_tableid_edit(
	// 			'tbl_pelatihan',
	// 			'id_pelatihan',
	// 			$id_pelatihan
	// 		);

	// 		$this->db->set('deleted_at', date('Y-m-d H:i:s'));
	// 		$this->db->where('id_pelatihan', $id_pelatihan); // ✅ Sekarang variabel terdefinisi
	// 		$this->db->update('tbl_pelatihan');


	// 		$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-warning">
	// 			<p> Berhasil Hapus Data Pelatihan!</p>
	// 		</div></div>');
	// 		redirect(base_url('data?jenis=PDWK'));
	// 	}

	// 	// === TAMBAH PELATIHAN ===
	// 	if (!empty($this->input->post('tambah'))) {
	// 		$post = $this->input->post();

	// 	// 	if (!isset($post['id_pejabat_pembuka']) || !isset($post['id_role_pembuka']) || 
	// 	// !isset($post['id_pejabat_penutup']) || !isset($post['id_role_penutup'])) {
	// 	// 	$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data pejabat dan jabatan belum lengkap.</div>');
	// 	// 	redirect(base_url('data/pelatihantambah'));
	// 	// }

	// 		$data = array(
	// 			'id_jenis_pelatihan' => htmlentities($post['id_jenis_pelatihan']),
	// 			'nama_kegiatan' => htmlentities($post['nama_kegiatan']),
	// 			'nama_pelatihan' => htmlentities($post['nama_pelatihan']),
	// 			'provinsi' => htmlentities($post['provinsi']),
	// 			'kab_kota' => htmlentities($post['kab_kota']),
	// 			'tempat' => htmlentities($post['tempat']),
	// 			'alamat' => htmlentities($post['alamat']),
	// 			'tanggal_mulai_pelatihan' => $post['tanggal_mulai'],
	// 			'tanggal_selesai_pelatihan' => $post['tanggal_selesai'],
	// 			'bulan_ttd_lap' => htmlentities($post['bulan_ttd']),
	// 			'tahun' => htmlentities($post['tahun']),
	// 			'hari_tanggal_pembukaan' => $post['hari_tanggal_pembukaan'],
	// 			'waktu_pembukaan' => $post['waktu_pembukaan'],
	// 			'id_pejabat_pembuka' => htmlentities($post['id_pejabat_pembuka']),
	// 			'id_role_pembuka' => htmlentities($post['id_role_pembuka']),
	// 			'hari_tanggal_penutupan' => $post['hari_tanggal_penutupan'],
	// 			'waktu_penutupan' => $post['waktu_penutupan'],
	// 			'id_pejabat_penutup' => htmlentities($post['id_pejabat_penutup']),
	// 			'id_role_penutup' => htmlentities($post['id_role_penutup']),
	// 			'created_at' => date('Y-m-d H:i:s'),
	// 			'updated_at' => date('Y-m-d H:i:s'),
	// 		);

	// 		$this->db->insert('tbl_pelatihan', $data);

	// 		$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
	// 			<p>Tambah Data Pelatihan Berhasil!</p>
	// 		</div></div>');
	// 		$jenis = $this->input->post('id_jenis_pelatihan') == 1 ? 'PJJ' : 'PDWK';
	// 		redirect(base_url('data?jenis=' . $jenis));
	// 	}

	// 	// === EDIT PELATIHAN ===
	// 	if (!empty($this->input->post('edit'))) {
	// 		$post = $this->input->post();

	// 		$data = array(
	// 			'id_jenis_pelatihan' => htmlentities($post['id_jenis_pelatihan']),
	// 			'nama_kegiatan' => htmlentities($post['nama_kegiatan']),
	// 			'nama_pelatihan' => htmlentities($post['nama_pelatihan']),
	// 			'provinsi' => htmlentities($post['provinsi']),
	// 			'kab_kota' => htmlentities($post['kab_kota']),
	// 			'tempat' => htmlentities($post['tempat']),
	// 			'tanggal_mulai_pelatihan' => $post['tanggal_mulai'],
	// 			'tanggal_selesai_pelatihan' => $post['tanggal_selesai'],
	// 			'bulan_ttd_lap' => htmlentities($post['bulan_ttd']),
	// 			'tahun' => htmlentities($post['tahun']),
	// 			'hari_tanggal_pembukaan' => $post['hari_tanggal_pembukaan'],
	// 			'waktu_pembukaan' => $post['waktu_pembukaan'],
	// 			'id_pejabat_pembuka' => htmlentities($post['id_pejabat_pembuka']),
	// 			'id_role_pembuka' => htmlentities($post['id_role_pembuka']),
	// 			'hari_tanggal_penutupan' => $post['hari_tanggal_penutupan'],
	// 			'waktu_penutupan' => $post['waktu_penutupan'],
	// 			'id_pejabat_penutup' => htmlentities($post['id_pejabat_penutup']),
	// 			'id_role_penutup' => htmlentities($post['id_role_penutup']),
	// 			'updated_at' => date('Y-m-d H:i:s'),
	// 		);

	// 		$this->db->where('id_pelatihan', htmlentities($post['edit']));
	// 		$this->db->update('tbl_pelatihan', $data);

	// 		$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
	// 			<p>Edit Data Pelatihan Berhasil!</p>
	// 		</div></div>');
	// 		redirect(base_url('data'));
	// 		// redirect(base_url('data/pelatihanedit/' . $post['edit']));
	// 	}
	// }

	// Code LDK Pekanbaru Detail Pelatihan

	// public function detailpelatihan()
	// {
	// 	// Ambil ID user yang login
	// 	$this->data['idbo'] = $this->session->userdata('ses_id');

	// 	$jenis = $this->input->get('jenis');

	// 	$id_jenis = null;
	// 	if ($jenis == 'PJJ'){
	// 		$id_jenis = 1;
	// 		$this->data['title_web'] = 'Data Pelatihan PJJ';
	// 	} elseif ($jenis == 'PDWK'){
	// 		$id_jenis = 2;
	// 		$this->data['title_web'] = 'Data Pelatihan PDWK';
	// 	}
		
		

	// 	// Ambil semua data detail pelatihan + join dengan nama pelatihan dan pegawai
	// 	$this->data['detail_pelatihan'] = $this->db->query("
	// 		SELECT 
	// 			dp.*, 
	// 			p.nama_kegiatan,
	// 			pj.nama AS nama_penanggung_jawab,
	// 			kp.nama AS nama_ketua_panitia
	// 		FROM tbl_detail_pelatihan dp
	// 		LEFT JOIN tbl_pelatihan p ON dp.id_pelatihan = p.id_pelatihan
	// 		LEFT JOIN tbl_pegawai pj ON dp.id_penanggung_jawab = pj.id_pegawai
	// 		LEFT JOIN tbl_pegawai kp ON dp.id_ketua_panitia = kp.id_pegawai
	// 		WHERE dp.deleted_at IS NULL
	// 		ORDER BY dp.id_detail_pelatihan DESC
	// 	")->result();

	// 	$this->data['pelatihan'] = $this->M_Admin->get_pelatihan_by_jenis($id_jenis);

	// 	// Jika ada parameter ID, ambil data spesifik untuk diedit
	// 	if (!empty($this->input->get('id'))) {
	// 		$id = $this->input->get('id');
	// 		$count = $this->M_Admin->CountTableId('tbl_detail_pelatihan', 'id_detail_pelatihan', $id);

	// 		if ($count > 0) {
	// 			$this->data['detail_pelatihans'] = $this->db->query("
	// 				SELECT 
	// 					dp.*, 
	// 					p.nama_kegiatan,
	// 					pj.nama AS nama_penanggung_jawab,
	// 					kp.nama AS nama_ketua_panitia
	// 				FROM tbl_detail_pelatihan dp
	// 				LEFT JOIN tbl_pelatihan p ON dp.id_pelatihan = p.id_pelatihan
	// 				LEFT JOIN tbl_pegawai pj ON dp.id_penanggung_jawab = pj.id_pegawai
	// 				LEFT JOIN tbl_pegawai kp ON dp.id_ketua_panitia = kp.id_pegawai
	// 				WHERE dp.id_detail_pelatihan = '$id'
	// 			")->row();
	// 		} else {
	// 			echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="' . base_url('data/detailpelatihan') . '"</script>';
	// 		}
	// 	}

	// 	// Set judul dan tampilkan view
	// 	$this->data['title_web'] = 'Data Detail Pelatihan';
	// 	$this->load->view('header_view', $this->data);
	// 	$this->load->view('sidebar_view', $this->data);
	// 	$this->load->view('detail_pelatihan/detail_pelatihan_view', $this->data);
	// 	$this->load->view('footer_view', $this->data);
	// }

	public function detailpelatihan()
{
    $this->data['idbo'] = $this->session->userdata('ses_id');
    $panitia_id = $this->session->userdata('id_login');
    $level = $this->session->userdata('level');

    // Ambil parameter jenis pelatihan dari URL
    $jenis = $this->input->get('jenis', TRUE);
    $id_jenis = null;

    if ($jenis === 'PJJ') {
        $id_jenis = 1;
        $this->data['title_web'] = 'Detail Pelatihan PJJ';
    } elseif ($jenis === 'PDWK') {
        $id_jenis = 2;
        $this->data['title_web'] = 'Detail Pelatihan PDWK';
    } elseif ($jenis === 'Latsar') {
        $id_jenis = 3;
        $this->data['title_web'] = 'Detail Pelatihan Dasar CPNS';
    } else {
        $this->data['title_web'] = 'Detail Pelatihan (Semua Jenis)';
    }

    $this->data['jenis'] = $jenis;
    // $this->data['id_jenis'] = $id_jenis;
    $this->data['is_latsar'] = ($id_jenis == 3);

    // 🔽 Query utama: tampilkan semua detail pelatihan dengan data panitia dari tbl_panitia_pelatihan + tbl_login
    $this->db->select('
        dp.*,
        p.nama_pelatihan AS nama_kegiatan,
        p.id_jenis_pelatihan,
        pj.nama AS nama_penanggung_jawab,
        kp_user.nama AS nama_ketua_panitia,
        ak_user.nama AS nama_akademis,
        ke_user.nama AS nama_keuangan,
        ad_user.nama AS nama_administrasi
    ');
        
    $this->db->from('tbl_detail_pelatihan dp');
    $this->db->join('tbl_pelatihan p', 'p.id_pelatihan = dp.id_pelatihan', 'left');
    $this->db->join('tbl_pegawai pj', 'pj.id_pegawai = dp.id_penanggung_jawab', 'left');
    
    $this->db->join('tbl_panitia_pelatihan pp_ketua', 'pp_ketua.id = dp.id_ketua_panitia', 'left');
    $this->db->join('tbl_login kp_user', 'kp_user.id_login = pp_ketua.panitia_id', 'left');
    $this->db->join('tbl_panitia_pelatihan pp_akademis', 'pp_akademis.id = dp.id_akademis', 'left');
    $this->db->join('tbl_login ak_user', 'ak_user.id_login = pp_akademis.panitia_id', 'left');
    $this->db->join('tbl_panitia_pelatihan pp_keuangan', 'pp_keuangan.id = dp.id_keuangan', 'left');
    $this->db->join('tbl_login ke_user', 'ke_user.id_login = pp_keuangan.panitia_id', 'left');
    $this->db->join('tbl_panitia_pelatihan pp_administrasi', 'pp_administrasi.id = dp.id_administrasi', 'left');
    $this->db->join('tbl_login ad_user', 'ad_user.id_login = pp_administrasi.panitia_id', 'left');

    // 🔸 Jika LATSAR → tambahkan nama peserta peringkat
    if ($id_jenis == 3) {
        $this->db->select('p1.nama_peserta AS nama_peringkat_1, p2.nama_peserta AS nama_peringkat_2, p3.nama_peserta AS nama_peringkat_3');
        $this->db->join('tbl_peserta_pelatihan p1', 'p1.id_peserta = dp.peserta_peringkat_1', 'left');
        $this->db->join('tbl_peserta_pelatihan p2', 'p2.id_peserta = dp.peserta_peringkat_2', 'left');
        $this->db->join('tbl_peserta_pelatihan p3', 'p3.id_peserta = dp.peserta_peringkat_3', 'left');
    }

    // 🔸 Filter berdasarkan jenis pelatihan
    if ($level !== 'admin' && $level !== 'Admin'){
        $this->db->where("p.id_pelatihan IN (SELECT pelatihan_id FROM tbl_panitia_pelatihan WHERE panitia_id = $panitia_id)", NULL, FALSE);
    }
    
    if (!is_null($id_jenis)) {
        $this->db->where('p.id_jenis_pelatihan', (int)$id_jenis);
    }

    $this->db->where('dp.deleted_at IS NULL', NULL, FALSE);
    $this->db->group_by('dp.id_detail_pelatihan');
    $this->db->order_by('dp.id_detail_pelatihan', 'DESC');
    
    $this->data['detail_pelatihan'] = $this->db->get()->result();
    // if ($level === 'admin' || $level === 'Admin') {
    //     $this->data['detail_pelatihan'] = $this->M_Admin->get_pelatihan_by_jenis($id_jenis)->result();
    // } else {
    //     $this->data['detail_pelatihan'] = $this->M_Admin->get_pelatihan_by_panitia($id_jenis, $panitia_id)->result();
    // }

    // if ($this->session->userdata('level') == 'Panitia') {
    //     $this->data['level'] = 'Admin';
    // } else {
    //     $this->data['level'] = $this->session->userdata('level');
    // }
    // $this->data['detail_pelatihan'] = $this->db->get()->result();


    // === Jika ada parameter ID (untuk tampilan satu detail) ===
    $id_param = $this->input->get('id', TRUE);
    if (!empty($id_param)) {
        $found = array_filter($result, function($item) use ($id_param) {
            return $item->id_detail_pelatihan == $id_param;
        });
        
        $this->data['detail_pelatihans'] = !empty($found) ? array_values($found)[0] : null;

        if(!$this->data['detail_pelatihans']){
             $this->session->set_flashdata('pesan', 'Detail tidak ditemukan');
             redirect('data/detailpelatihan?jenis='.$jenis);
        }
    }

    // 🔹 Load view
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('detail_pelatihan/detail_pelatihan_view', $this->data);
    $this->load->view('footer_view', $this->data);
}


	
	

private function insertPanitia($pelatihan_id, $login_id, $peran)
{
    if (empty($login_id)) return null;

    // Cek apakah kombinasi sudah ada
    $existing = $this->db->get_where('tbl_panitia_pelatihan', [
        'panitia_id'     => $login_id,
        'pelatihan_id' => $pelatihan_id,
        'peran'        => $peran
    ])->row();

    if ($existing) {
        // Kalau sudah ada, langsung return ID-nya
        return $existing->id;
    }

    // Kalau belum ada, buat baru
    $this->db->insert('tbl_panitia_pelatihan', [
        'panitia_id'     => $login_id,
        'pelatihan_id' => $pelatihan_id,
        'peran'        => $peran
    ]);

    return $this->db->insert_id();
}


public function prosesdetailpelatihan()
{
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        return redirect(base_url('login'));
    }

    $post = $this->input->post(NULL, TRUE);
    // echo "<pre>";
    // print_r($post);
    // echo "</pre>";
    // exit;

    $jenis_ctx = $this->input->get('jenis', TRUE) ?? $this->input->post('jenis', TRUE);
    $redir_url = base_url('data/detailpelatihan' . ($jenis_ctx ? '?jenis=' . urlencode($jenis_ctx) : ''));
    
    $getS = fn($k, $d = NULL) => ($post[$k] ?? '') !== '' ? trim($post[$k]) : $d;
    $getI = fn($k, $d = NULL) => ($post[$k] ?? '') !== '' ? (int)($post[$k]) : $d;
    $getF = fn($k, $d = NULL) => ($post[$k] ?? '') !== '' ? (float)($post[$k]) : $d;
    $getA = fn($k) => array_filter(array_map('intval', (array)($post[$k] ?? [])));

    // // === SOFT DELETE DETAIL PELATIHAN ===
    // if ($this->input->get('id_detail_pelatihan')) {
    //     $id = (int) $this->input->get('id_detail_pelatihan');
    //     $row = $this->M_Admin->get_tableid_edit('tbl_detail_pelatihan', 'id_detail_pelatihan', $id);

    //     if ($row) {
    //         $now = date('Y-m-d H:i:s');
    //         // soft-delete detail
    //         $this->db->set('deleted_at', $now)
    //                  ->where('id_detail_pelatihan', $id)
    //                  ->update('tbl_detail_pelatihan');

    //         // ikut soft-delete mapping pengajar utk pelatihan ini
    //         $this->db->set('deleted_at', $now)
    //                  ->where('id_pelatihan', (int)$row->id_pelatihan)
    //                  ->update('tbl_pelatihan_pengajar');

    //         $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-warning"><p>Berhasil Hapus (Soft Delete) Data Detail Pelatihan!</p></div></div>');
    //     } else {
    //         $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger"><p>Data Detail Pelatihan tidak ditemukan!</p></div></div>');
    //     }
    //     return redirect($redir_url);
    // }

    // === HARD DELETE DETAIL PELATIHAN ===
if ($this->input->get('id_detail_pelatihan')) {

    $id = (int) $this->input->get('id_detail_pelatihan');
    $row = $this->M_Admin->get_tableid_edit(
        'tbl_detail_pelatihan', 
        'id_detail_pelatihan', 
        $id
    );

    if ($row) {

        $this->db->trans_start(); // mulai transaction

        // 1. Hapus mapping pengajar terlebih dahulu (child table)
        $this->db->where('id_pelatihan', (int)$row->id_pelatihan)
                 ->delete('tbl_pelatihan_pengajar');

        // 2. Hapus detail pelatihan
        $this->db->where('id_detail_pelatihan', $id)
                 ->delete('tbl_detail_pelatihan');

        $this->db->trans_complete(); // commit / rollback otomatis

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata(
                'pesan',
                '<div id="notifikasi"><div class="alert alert-danger">
                <p>Gagal menghapus data secara permanen!</p></div></div>'
            );
        } else {
            $this->session->set_flashdata(
                'pesan',
                '<div id="notifikasi"><div class="alert alert-warning">
                <p>Berhasil Hapus Data Detail Pelatihan Secara Permanen!</p></div></div>'
            );
        }

    } else {
        $this->session->set_flashdata(
            'pesan',
            '<div id="notifikasi"><div class="alert alert-danger">
            <p>Data Detail Pelatihan tidak ditemukan!</p></div></div>'
        );
    }

    return redirect($redir_url);
}

    // /**
    //  * Internal: sinkronisasi tabel baru (soft-delete + insert-batch dedup)
    //  */
    // $sync_pengajar = function($id_pelatihan, $wi_ids, $wi_rapat_id, $pengajar_ids) {
    //     $now = date('Y-m-d H:i:s');

    //     // soft-delete existing
    //     $this->db->set('deleted_at', $now)
    //         ->where('id_pelatihan', (int)$id_pelatihan)
    //         ->where('deleted_at IS NULL', null, false)
    //         ->update('tbl_pelatihan_pengajar');

    //     $this->db->where('id_pelatihan', (int)$id_pelatihan)
    //         ->where('deleted_at IS NOT NULL', null, false)
    //         ->delete('tbl_pelatihan_pengajar');

    //     // build rows
    //     $rows = [];
    //     foreach ($wi_ids as $idp) {
    //         $rows[] = [
    //             'id_pelatihan' => (int)$id_pelatihan,
    //             'id_pegawai'   => (int)$idp,
    //             'tipe_peran'   => 'Widyaiswara',
    //             'created_at'   => $now,
    //             'updated_at'   => $now,
    //             'deleted_at'   => NULL,
    //         ];
    //     }
    //     $wi_rapat_id = (int)$wi_rapat_id;
    //     if ($wi_rapat_id > 0) {
    //         $rows[] = [
    //             'id_pelatihan' => (int)$id_pelatihan,
    //             'id_pegawai'   => $wi_rapat_id,
    //             'tipe_peran'   => 'Widyaiswara Rapat Kelulusan',
    //             'created_at'   => $now,
    //             'updated_at'   => $now,
    //             'deleted_at'   => NULL,
    //         ];
    //     }
    //     foreach ($pengajar_ids as $idp) {
    //         $rows[] = [
    //             'id_pelatihan' => (int)$id_pelatihan,
    //             'id_pegawai'   => (int)$idp,
    //             'tipe_peran'   => 'Pengajar',
    //             'created_at'   => $now,
    //             'updated_at'   => $now,
    //             'deleted_at'   => NULL,
    //         ];
    //     }

    //     // dedup 3 kolom
    //     if (!empty($rows)) {
    //         $seen = [];
    //         $insert = [];
    //         foreach ($rows as $r) {
    //             $k = $r['id_pelatihan'].'|'.$r['id_pegawai'].'|'.$r['tipe_peran'];
    //             if (!isset($seen[$k])) {
    //                 $seen[$k] = true;
    //                 $insert[] = $r;
    //             }
    //         }
    //         if (!empty($insert)) {
    //             $this->db->insert_batch('tbl_pelatihan_pengajar', $insert);
    //         }
    //     }
    // };

    $sync_pengajar = function($id_pelatihan, $wi_ids, $wi_rapat_id, $pengajar_ids) {
        $now = date('Y-m-d H:i:s');
        $id_pelatihan = (int)$id_pelatihan;

        // 1️⃣ Hapus data lama (supaya tidak bentrok UNIQUE KEY)
        $this->db->where('id_pelatihan', $id_pelatihan)->delete('tbl_pelatihan_pengajar');

        // 2️⃣ Siapkan array untuk batch insert
        $rows = [];

        // Tambahkan Widyaiswara
        foreach ((array)$wi_ids as $idp) {
            if ($idp > 0) {
                $rows[] = [
                    'id_pelatihan' => $id_pelatihan,
                    'id_pegawai'   => (int)$idp,
                    'tipe_peran'   => 'Widyaiswara',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                    'deleted_at'   => NULL,
                ];
            }
        }

        // Tambahkan WI Rapat Kelulusan
        $wi_rapat_id = (int)$wi_rapat_id;
        if ($wi_rapat_id > 0) {
            $rows[] = [
                'id_pelatihan' => $id_pelatihan,
                'id_pegawai'   => $wi_rapat_id,
                'tipe_peran'   => 'Widyaiswara Rapat Kelulusan',
                'created_at'   => $now,
                'updated_at'   => $now,
                'deleted_at'   => NULL,
            ];
        }

        // Tambahkan Pengajar
        foreach ((array)$pengajar_ids as $idp) {
            if ($idp > 0) {
                $rows[] = [
                    'id_pelatihan' => $id_pelatihan,
                    'id_pegawai'   => (int)$idp,
                    'tipe_peran'   => 'Pengajar',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                    'deleted_at'   => NULL,
                ];
            }
        }

        // 3️⃣ Hilangkan duplikat (antisipasi multi-select sama)
        if (!empty($rows)) {
            $seen = [];
            $insert = [];
            foreach ($rows as $r) {
                $key = $r['id_pelatihan'].'|'.$r['id_pegawai'].'|'.$r['tipe_peran'];
                if (!isset($seen[$key])) {
                    $seen[$key] = true;
                    $insert[] = $r;
                }
            }

            // 4️⃣ Insert batch
            if (!empty($insert)) {
                $this->db->insert_batch('tbl_pelatihan_pengajar', $insert);
            }
        }
    };


    

    // === TAMBAH ===
    if (!empty($post['tambah'])) {
        $id_pelatihan       = $getI('id_pelatihan');
        $pel = $this->db->where('id_pelatihan', $id_pelatihan)
                ->get('tbl_pelatihan')
                ->row();

        $id_jenis_pelatihan = $pel ? (int)$pel->id_jenis_pelatihan : null;

        $roles = [
            'id_ketua_panitia' => 'Ketua Panitia',
            'id_akademis'      => 'Akademis',
            'id_keuangan'      => 'Keuangan',
            'id_administrasi'  => 'Administrasi',
            'pic_smartbangkom' => 'PIC Smartbangkom'
        ];

        $panitia_ids = [];

        foreach ($roles as $field => $role_name) {
            $login_id = $getI($field);
            if ($login_id) {
                $this->db->insert('tbl_panitia_pelatihan', [
                    'pelatihan_id' => $id_pelatihan,
                    'panitia_id'   => $login_id,
                    'peran'        => $role_name
                ]);
                $panitia_ids[$field] = $this->db->insert_id();
            } else {
                $panitia_ids[$field] = NULL;
            }
        }
        
        $data = array_merge([
            'id_pelatihan'                  => $id_pelatihan,
            'id_jenis_pelatihan'            => $id_jenis_pelatihan,
            'id_penanggung_jawab'           => $getI('id_penanggung_jawab'),
            'jumlah_wi_pengajar'            => $getI('jumlah_wi_pengajar'),
            'jumlah_pendidikan_wi_d2'       => $getI('jumlah_pendidikan_wi_d2'),
            'jumlah_pendidikan_wi_s1'       => $getI('jumlah_pendidikan_wi_s1'),
            'jumlah_pendidikan_wi_s2'       => $getI('jumlah_pendidikan_wi_s2'),
            'jumlah_pendidikan_wi_s3'       => $getI('jumlah_pendidikan_wi_s3'),
            'jumlah_peserta'                => $getI('jumlah_peserta'),
            'jumlah_lulus'                  => $getI('jumlah_lulus'),
            'jumlah_tidak_lulus'            => $getI('jumlah_tidak_lulus'),
            'jabatan_peserta'               => $getS('jabatan_peserta'),
            'jumlah_peserta_asn'            => $getI('jumlah_peserta_asn'),
            'jumlah_peserta_non_asn'        => $getI('jumlah_peserta_non_asn'),
            'jumlah_peserta_laki'           => $getI('jumlah_peserta_laki'),
            'jumlah_peserta_wanita'         => $getI('jumlah_peserta_wanita'),
            'jumlah_pendidikan_peserta_sma' => $getI('jumlah_pendidikan_peserta_sma'),
            'jumlah_pendidikan_peserta_d3'  => $getI('jumlah_pendidikan_peserta_d3'),
            'jumlah_pendidikan_peserta_s1'  => $getI('jumlah_pendidikan_peserta_s1'),
            'jumlah_pendidikan_peserta_s2'  => $getI('jumlah_pendidikan_peserta_s2'),
            'jumlah_pendidikan_peserta_s3'  => $getI('jumlah_pendidikan_peserta_s3'),
            'rab'                           => $getF('rab'),
            'realisasi'                     => $getF('realisasi'),
            'jml_peserta_nilai_sm'          => $getI('jml_peserta_nilai_sm'),
            'jml_peserta_nilai_m'           => $getI('jml_peserta_nilai_m'),
            'jml_peserta_nilai_cm'          => $getI('jml_peserta_nilai_cm'),
            'jml_peserta_nilai_dl'          => $getI('jml_peserta_nilai_dl'),
            'jml_peserta_tm'                => $getI('jml_peserta_tm'),
            'peserta_peringkat_1'           => $getI('peserta_peringkat_1'),
            'peserta_peringkat_2'           => $getI('peserta_peringkat_2'),
            'peserta_peringkat_3'           => $getI('peserta_peringkat_3'),
            'created_at'                    => date('Y-m-d H:i:s'),
            'updated_at'                    => date('Y-m-d H:i:s'),
            'deleted_at'                    => NULL
        ], $panitia_ids);

        // Simpan detail
        $this->db->insert('tbl_detail_pelatihan', $data);
        $id_detail_baru = $this->db->insert_id();

        // Sinkronisasi tabel baru utk PJJ/PDWK
        if (in_array((int)$id_jenis_pelatihan, [1, 2], true)) {
            $wi_ids       = $getA('wi_ids');
            $pengajar_ids = $getA('pengajar_ids');
            $wi_rapat     = $getI('wi_rapat_kelulusan');

            $sync_pengajar($id_pelatihan, $wi_ids, $wi_rapat, $pengajar_ids);

            $update_legacy = [];

            foreach (array_slice((array)$wi_ids, 0, 4) as $i => $id_wi) {
                $update_legacy['id_wi_' . ($i + 1)] = $id_wi;
            }

            if (!empty($wi_rapat)) {
                $update_legacy['id_wi_rapat_kelulusan'] = $wi_rapat;
            }

            foreach (array_slice((array)$pengajar_ids, 0, 3) as $i => $id_peng) {
                $update_legacy['id_pengajar_' . ($i + 1)] = $id_peng;
            }

            if (!empty($update_legacy)) {
                $this->db->where('id_detail_pelatihan', $id_detail)
                        ->update('tbl_detail_pelatihan', $update_legacy);
            }
        }
        $this->session->set_flashdata('pesan',
            '<div id="notifikasi"><div class="alert alert-success"><p>Tambah Detail Pelatihan Sukses!</p></div></div>');
        return redirect($redir_url);
    }


    // === EDIT ===
    if (!empty($post['edit'])) {
        $id_detail           = $getI('edit');
        $id_pelatihan        = $getI('id_pelatihan');
        $pel = $this->db->where('id_pelatihan', $id_pelatihan)
                ->get('tbl_pelatihan')
                ->row();

        $id_jenis_pelatihan = $pel ? (int)$pel->id_jenis_pelatihan : null;

        $roles = [
            'id_ketua_panitia' => 'Ketua Panitia',
            'id_akademis' => 'Akademis',
            'id_keuangan' => 'Keuangan',
            'id_administrasi' => 'Administrasi',
            'pic_smartbangkom' => 'PIC Smartbangkom'
        ];

        $data_panitia = [];

        foreach ($roles as $field => $peran) {
            $login_id = $getI($field);

            if (!empty($login_id)) {
                $existing = $this->db
                    ->where('pelatihan_id', $id_pelatihan)
                    ->where('panitia_id', $login_id)
                    ->where('peran', $peran)
                    ->get('tbl_panitia_pelatihan')
                    ->row();

                if ($existing) {
                    $data_panitia[$field] = $existing->id;
                } else {
                    $this->db->insert('tbl_panitia_pelatihan', [
                        'pelatihan_id' => $id_pelatihan,
                        'panitia_id' => $login_id,
                        'peran' => $peran
                    ]);
                    $data_panitia[$field] = $this->db->insert_id();
                }
            } else {
                $data_panitia[$field] = null;
            }
        }

        $data_update = array_merge([
            'id_pelatihan'          => $id_pelatihan,
            'id_jenis_pelatihan'    => $id_jenis_pelatihan,
            'id_penanggung_jawab'   => $getI('id_penanggung_jawab'),

            'jumlah_wi_pengajar'            => $getI('jumlah_wi_pengajar'),
            'jumlah_pendidikan_wi_d2'       => $getI('jumlah_pendidikan_wi_d2'),
            'jumlah_pendidikan_wi_s1'       => $getI('jumlah_pendidikan_wi_s1'),
            'jumlah_pendidikan_wi_s2'       => $getI('jumlah_pendidikan_wi_s2'),
            'jumlah_pendidikan_wi_s3'       => $getI('jumlah_pendidikan_wi_s3'),

            'jumlah_peserta'                => $getI('jumlah_peserta'),
            'jumlah_lulus'                  => $getI('jumlah_lulus'),
            'jumlah_tidak_lulus'            => $getI('jumlah_tidak_lulus'),
            'jabatan_peserta'               => $getS('jabatan_peserta'),

            'jumlah_peserta_asn'            => $getI('jumlah_peserta_asn'),
            'jumlah_peserta_non_asn'        => $getI('jumlah_peserta_non_asn'),
            'jumlah_peserta_laki'           => $getI('jumlah_peserta_laki'),
            'jumlah_peserta_wanita'         => $getI('jumlah_peserta_wanita'),

            'jumlah_pendidikan_peserta_sma' => $getI('jumlah_pendidikan_peserta_sma'),
            'jumlah_pendidikan_peserta_d3'  => $getI('jumlah_pendidikan_peserta_d3'), // sesuaikan jika typo
            'jumlah_pendidikan_peserta_s1'  => $getI('jumlah_pendidikan_peserta_s1'),
            'jumlah_pendidikan_peserta_s2'  => $getI('jumlah_pendidikan_peserta_s2'),
            'jumlah_pendidikan_peserta_s3'  => $getI('jumlah_pendidikan_peserta_s3'),

            'rab'                           => $getF('rab'),
            'realisasi'                     => $getF('realisasi'),
            'jml_peserta_nilai_sm'  => $getI('jml_peserta_nilai_sm'),
            'jml_peserta_nilai_m'   => $getI('jml_peserta_nilai_m'),
            'jml_peserta_nilai_cm'  => $getI('jml_peserta_nilai_cm'),
            'jml_peserta_nilai_dl'  => $getI('jml_peserta_nilai_dl'),
            'jml_peserta_tm'        => $getI('jml_peserta_tm'),
            'peserta_peringkat_1'   => $getI('peserta_peringkat_1'),
            'peserta_peringkat_2'   => $getI('peserta_peringkat_2'),
            'peserta_peringkat_3'   => $getI('peserta_peringkat_3'),

            'updated_at'            => date('Y-m-d H:i:s')
        ], $data_panitia);

        $this->db->where('id_detail_pelatihan', $id_detail)->update('tbl_detail_pelatihan', $data_update);

        // Sinkronisasi tabel baru utk PJJ/PDWK
        if (in_array((int)$id_jenis_pelatihan, [1, 2], true)){
            $wi_ids       = $getA('wi_ids');
            $pengajar_ids = $getA('pengajar_ids');
            $wi_rapat     = $getI('wi_rapat_kelulusan');

            $sync_pengajar($id_pelatihan, $wi_ids, $wi_rapat, $pengajar_ids);

            $update_legacy = [];

            foreach (array_slice((array)$wi_ids, 0, 4) as $i => $id_wi) {
                $update_legacy['id_wi_' . ($i + 1)] = $id_wi;
            }

            if (!empty($wi_rapat)) {
                $update_legacy['id_wi_rapat_kelulusan'] = $wi_rapat;
            }

            foreach (array_slice((array)$pengajar_ids, 0, 3) as $i => $id_peng) {
                $update_legacy['id_pengajar_' . ($i + 1)] = $id_peng;
            }

            if (!empty($update_legacy)) {
                $this->db->where('id_detail_pelatihan', $id_detail)
                        ->update('tbl_detail_pelatihan', $update_legacy);
            }
        }

        $this->session->set_flashdata('pesan',
            '<div id="notifikasi"><div class="alert alert-success"><p>Edit Detail Pelatihan Sukses!</p></div></div>');
        return redirect($redir_url);
    }

    return redirect($redir_url);
}


public function detailpelatihantambah()
{
    $this->data['idbo'] = $this->session->userdata('ses_id');

    $jenis = $this->input->get('jenis', TRUE);
    $id_jenis = null;
    if ($jenis === 'PJJ') $id_jenis = 1;
    elseif ($jenis === 'PDWK') $id_jenis = 2;
    elseif ($jenis === 'Latsar') $id_jenis = 3;

    $existing_detail_ids = $this->db->select('id_pelatihan')
        ->from('tbl_detail_pelatihan')
        ->where('deleted_at', NULL)
        ->get()->result_array();
    $used_ids = array_column($existing_detail_ids, 'id_pelatihan');

    $this->db->from('tbl_pelatihan');
    $this->db->where('deleted_at', NULL);
    if (!empty($used_ids)) $this->db->where_not_in('id_pelatihan', $used_ids);
    if (!is_null($id_jenis)) $this->db->where('id_jenis_pelatihan', (int)$id_jenis);
    $this->db->order_by('id_pelatihan', 'DESC');
    $this->data['pelatihans'] = $this->db->get()->result_array();

    $this->data['pegawais'] = $this->db
        ->where('deleted_at', NULL)
        ->order_by('id_pegawai', 'DESC')
        ->get('tbl_pegawai')->result_array();

    $this->data['panitia'] = $this->db
        ->select('id_login AS id_pegawai, nama')
        ->from('tbl_login')
        ->where_in('level', ['Panitia'])
        ->order_by('id_login', 'DESC')
        ->get()->result_array();

    // flags & defaults untuk view
    $this->data['jenis']      = $jenis;
    $this->data['id_jenis']   = $id_jenis;
    $this->data['is_latsar']  = ($id_jenis === 3);

    // default selected (untuk PJJ/PDWK multi-select)
    $this->data['wi_selected']        = [];
    $this->data['pengajar_selected']  = [];
    $this->data['wi_rapat_selected']  = NULL;

    $baseTitle = 'Tambah Detail Pelatihan';
    if     ($jenis === 'PJJ')    $this->data['title_web'] = $baseTitle . ' – PJJ';
    elseif ($jenis === 'PDWK')   $this->data['title_web'] = $baseTitle . ' – PDWK';
    elseif ($jenis === 'Latsar') $this->data['title_web'] = $baseTitle . ' – Latsar CPNS';
    else                         $this->data['title_web'] = $baseTitle;

    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('detail_pelatihan/tambah_view', $this->data);
    $this->load->view('footer_view', $this->data);
}



	// public function detailpelatihandetail()
	// {
	// 	$this->data['idbo'] = $this->session->userdata('ses_id');
	// 	$count = $this->M_Admin->CountTableId('tbl_detail_pelatihan','id_detail_pelatihan',$this->uri->segment('3'));
	// 	if($count > 0)
	// 	{
	// 		$this->data['detail_pelatihan'] = $this->M_Admin->get_tableid_edit('tbl_detail_pelatihan','id_detail_pelatihan',$this->uri->segment('3'));
	// 		$this->data['pelatihans'] =  $this->db->query("SELECT * FROM tbl_pelatihan ORDER BY id_pelatihan DESC")->result_array();
	// 		$this->data['pegawais'] =  $this->db->query("SELECT * FROM tbl_pegawai ORDER BY id_pegawai DESC")->result_array();

	// 	}else{
	// 		echo '<script>alert("PEGAWAI TIDAK DITEMUKAN");window.location="'.base_url('data/detailpelatihan').'"</script>';
	// 	}

	// 	$this->data['title_web'] = 'Data Detail Pelatihan';
    //     $this->load->view('header_view',$this->data);
    //     $this->load->view('sidebar_view',$this->data);
    //     $this->load->view('detail_pelatihan/detail',$this->data);
    //     $this->load->view('footer_view',$this->data);
	// }

	// public function detailpelatihanedit()
	// {
	// 	$this->data['idbo'] = $this->session->userdata('ses_id');
	// 	$count = $this->M_Admin->CountTableId('tbl_detail_pelatihan','id_detail_pelatihan',$this->uri->segment('3'));
	// 	if($count > 0)
	// 	{
			
	// 		$this->data['detail_pelatihan'] = $this->M_Admin->get_tableid_edit('tbl_detail_pelatihan','id_detail_pelatihan',$this->uri->segment('3'));
	// 		$this->data['pelatihans'] =  $this->db->query("SELECT * FROM tbl_pelatihan ORDER BY id_pelatihan DESC")->result_array();
	// 		$this->data['pegawais'] =  $this->db->query("SELECT * FROM tbl_pegawai ORDER BY id_pegawai DESC")->result_array();

	// 	}else{
	// 		echo '<script>alert("PEGAWAI TIDAK DITEMUKAN");window.location="'.base_url('data/pegawai').'"</script>';
	// 	}

	// 	$this->data['title_web'] = 'Data Pegawai Edit';
    //     $this->load->view('header_view',$this->data);
    //     $this->load->view('sidebar_view',$this->data);
    //     $this->load->view('detail_pelatihan/edit_view',$this->data);
    //     $this->load->view('footer_view',$this->data);
	// }

// 	public function detailpelatihandetail()
// {
//     $this->data['idbo'] = $this->session->userdata('ses_id');

//     // Keep jenis context (optional, nice for back button + Latsar toggle)
//     $jenis = $this->input->get('jenis', TRUE); // 'PJJ'|'PDWK'|'Latsar'|NULL
//     $this->data['jenis'] = $jenis;
//     $this->data['is_latsar'] = ($jenis === 'Latsar');

//     $id_detail = $this->uri->segment('3');
//     $count = $this->M_Admin->CountTableId('tbl_detail_pelatihan', 'id_detail_pelatihan', $id_detail);

//     if ($count > 0) {
//         // Detail
//         $this->data['detail_pelatihan'] = $this->M_Admin
//             ->get_tableid_edit('tbl_detail_pelatihan', 'id_detail_pelatihan', $id_detail);

//         // Kegiatan & Pegawai for rendering names
//         $this->data['pelatihans'] = $this->db
//             ->order_by('id_pelatihan', 'DESC')
//             ->get('tbl_pelatihan')->result_array();

//         $this->data['pegawais'] = $this->db
//             ->order_by('id_pegawai', 'DESC')
//             ->get('tbl_pegawai')->result_array();

//         // === NEW: Ambil nama peserta untuk peringkat 1/2/3 ===
//         $dp = $this->data['detail_pelatihan'];
//         $rank_ids = array_filter([
//             !empty($dp->peserta_peringkat_1) ? $dp->peserta_peringkat_1 : null,
//             !empty($dp->peserta_peringkat_2) ? $dp->peserta_peringkat_2 : null,
//             !empty($dp->peserta_peringkat_3) ? $dp->peserta_peringkat_3 : null,
//         ]);

//         $rank_map = [];
//         if (!empty($rank_ids)) {
//             $rows = $this->db->select('id_peserta, nama_peserta')
//                 ->from('tbl_peserta_pelatihan')
//                 ->where_in('id_peserta', $rank_ids)
//                 ->where('deleted_at', NULL)      // hormati soft delete
//                 ->get()->result_array();

//             foreach ($rows as $r) {
//                 $rank_map[$r['id_peserta']] = $r['nama_peserta'];
//             }
//         }
//         $this->data['rank_peserta_map'] = $rank_map; // pass ke view

//     } else {
//         echo '<script>alert("DETAIL PELATIHAN TIDAK DITEMUKAN");window.location="' . base_url('data/detailpelatihan') . '"</script>';
//         return;
//     }

//     $this->data['title_web'] = 'Data Detail Pelatihan';
//     $this->load->view('header_view', $this->data);
//     $this->load->view('sidebar_view', $this->data);
//     $this->load->view('detail_pelatihan/detail', $this->data);
//     $this->load->view('footer_view', $this->data);
// }

public function detailpelatihandetail()
{
    $this->data['idbo'] = $this->session->userdata('ses_id');

    $jenis = $this->input->get('jenis', TRUE);
    $this->data['jenis'] = $jenis;
    $this->data['is_latsar'] = ($jenis === 'Latsar');

    $id_detail = (int)$this->uri->segment('3');
    $count = $this->M_Admin->CountTableId('tbl_detail_pelatihan', 'id_detail_pelatihan', $id_detail);

    if ($count <= 0) {
        echo '<script>alert("DETAIL PELATIHAN TIDAK DITEMUKAN");window.location="' . base_url('data/detailpelatihan') . '"</script>';
        return;
    }

    // join untuk dapat id_jenis_pelatihan & nama_kegiatan
    $dp = $this->db
    ->select('
        dp.*,
        p.id_jenis_pelatihan,
        p.nama_kegiatan,

        kp_user.nama AS nama_ketua_panitia,
        ak_user.nama AS nama_akademis,
        ke_user.nama AS nama_keuangan,
        ad_user.nama AS nama_administrasi,
        pic_user.nama AS nama_pic_smartbangkom

    ')
    ->from('tbl_detail_pelatihan dp')
    ->join('tbl_pelatihan p', 'p.id_pelatihan = dp.id_pelatihan', 'left')

    // Ketua Panitia
    ->join('tbl_panitia_pelatihan pp_ketua', 'pp_ketua.id = dp.id_ketua_panitia', 'left')
    ->join('tbl_login kp_user', 'kp_user.id_login = pp_ketua.panitia_id', 'left')

    // Akademis
    ->join('tbl_panitia_pelatihan pp_akademis', 'pp_akademis.id = dp.id_akademis', 'left')
    ->join('tbl_login ak_user', 'ak_user.id_login = pp_akademis.panitia_id', 'left')

    // Keuangan
    ->join('tbl_panitia_pelatihan pp_keuangan', 'pp_keuangan.id = dp.id_keuangan', 'left')
    ->join('tbl_login ke_user', 'ke_user.id_login = pp_keuangan.panitia_id', 'left')

    // Administrasi
    ->join('tbl_panitia_pelatihan pp_administrasi', 'pp_administrasi.id = dp.id_administrasi', 'left')
    ->join('tbl_login ad_user', 'ad_user.id_login = pp_administrasi.panitia_id', 'left')

    // PIC Smartbangkom
    ->join('tbl_panitia_pelatihan pp_pic', 'pp_pic.id = dp.pic_smartbangkom', 'left')
    ->join('tbl_login pic_user', 'pic_user.id_login = pp_pic.panitia_id', 'left')


    ->where('dp.id_detail_pelatihan', $id_detail)
    ->get()
    ->row();

    $this->data['detail_pelatihan'] = $dp;

    // list pelatihan & pegawai (render nama)
    $this->data['pelatihans'] = $this->db
        ->order_by('id_pelatihan', 'DESC')
        ->get('tbl_pelatihan')->result_array();

    $this->data['pegawais'] = $this->db
        ->order_by('id_pegawai', 'DESC')
        ->get('tbl_pegawai')->result_array();

    // === BACA PENGAJAR DARI TABEL BARU UNTUK PJJ/PDWK ===
    $ringkas_wi = $ringkas_pengajar = '-';
    $ringkas_wi_rapat = '-';

    if (in_array((int)$dp->id_jenis_pelatihan, [1,2], true)) {
        $map = $this->_get_pengajar_assignments((int)$dp->id_pelatihan);
        $this->data['wi_names']        = $map['wi_names'];        // array of names (Widyaiswara)
        $this->data['pengajar_names']  = $map['pengajar_names'];  // array of names (Pengajar)
        $this->data['wi_rapat_name']   = $map['wi_rapat_name'];   // string or null
        // if (!empty($map['wi_names']))        $ringkas_wi = implode(', ', $map['wi_names']);
        // if (!empty($map['pengajar_names']))  $ringkas_pengajar = implode(', ', $map['pengajar_names']);
        // if (!empty($map['wi_rapat_name']))   $ringkas_wi_rapat = $map['wi_rapat_name'];
    } else {
        // Latsar tetap bisa diringkas dari field lama bila Anda mau
        $ringkas_wi = $ringkas_pengajar = $ringkas_wi_rapat = '(menggunakan field lama)';
    }

    $this->data['ringkas_wi']        = $ringkas_wi;
    $this->data['ringkas_wi_rapat']  = $ringkas_wi_rapat;
    $this->data['ringkas_pengajar']  = $ringkas_pengajar;


    $this->data['title_web'] = 'Data Detail Pelatihan';
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('detail_pelatihan/detail', $this->data);
    $this->load->view('footer_view', $this->data);
}


// public function detailpelatihanedit()
// {
//     $this->data['idbo'] = $this->session->userdata('ses_id');

//     $id = (int) $this->uri->segment(3);

//     // Ambil record dengan join ke tbl_pelatihan untuk tahu jenis
//     $row = $this->db->select('dp.*, p.id_jenis_pelatihan, p.nama_kegiatan')
//         ->from('tbl_detail_pelatihan dp')
//         ->join('tbl_pelatihan p', 'p.id_pelatihan = dp.id_pelatihan', 'left')
//         ->where('dp.id_detail_pelatihan', $id)
//         ->get()->row();

//     if (!$row) {
//         $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
//             <p>DETAIL PELATIHAN TIDAK DITEMUKAN</p>
//         </div></div>');
//         return redirect(base_url('data/detailpelatihan'));
//     }

//     // Turunkan/override konteks jenis
//     $jenis_param = $this->input->get('jenis', TRUE);
//     if ($jenis_param) {
//         $jenis = $jenis_param; // 'PJJ' | 'PDWK' | 'Latsar'
//         $id_jenis = ($jenis === 'PJJ' ? 1 : ($jenis === 'PDWK' ? 2 : ($jenis === 'Latsar' ? 3 : null)));
//     } else {
//         $id_jenis = isset($row->id_jenis_pelatihan) ? (int)$row->id_jenis_pelatihan : null;
//         $jenis = ($id_jenis === 1 ? 'PJJ' : ($id_jenis === 2 ? 'PDWK' : ($id_jenis === 3 ? 'Latsar' : null)));
//     }
//     $is_latsar = ($id_jenis === 3);

//     // Data untuk view
//     $this->data['detail_pelatihan'] = $row;
//     $this->data['jenis']     = $jenis;
//     $this->data['id_jenis']  = $id_jenis;
//     $this->data['is_latsar'] = $is_latsar;

//     // Dropdown pelatihans: batasi ke jenis yang sama & tidak terhapus
//     $this->db->from('tbl_pelatihan');
//     $this->db->where('deleted_at', NULL);
//     if (!is_null($id_jenis)) {
//         $this->db->where('id_jenis_pelatihan', $id_jenis);
//     }
//     $this->db->order_by('id_pelatihan', 'DESC');
//     $this->data['pelatihans'] = $this->db->get()->result_array();

//     // Pegawais
//     $this->data['pegawais'] = $this->db
//         ->where('deleted_at', NULL)
//         ->order_by('id_pegawai', 'DESC')
//         ->get('tbl_pegawai')
//         ->result_array();

//     // Title
//     $baseTitle = 'Edit Detail Pelatihan';
//     if ($jenis === 'PJJ')        $this->data['title_web'] = $baseTitle . ' – PJJ';
//     elseif ($jenis === 'PDWK')   $this->data['title_web'] = $baseTitle . ' – PDWK';
//     elseif ($jenis === 'Latsar') $this->data['title_web'] = $baseTitle . ' – Latsar CPNS';
//     else                         $this->data['title_web'] = $baseTitle;

//     // Render
//     $this->load->view('header_view', $this->data);
//     $this->load->view('sidebar_view', $this->data);
//     $this->load->view('detail_pelatihan/edit_view', $this->data);
//     $this->load->view('footer_view', $this->data);
// }

public function detailpelatihanedit()
{
    $this->data['idbo'] = $this->session->userdata('ses_id');

    $id = (int) $this->uri->segment(3);

    $row = $this->db->select('
    dp.*, p.id_jenis_pelatihan, p.nama_kegiatan,
    kp_user.id_login AS id_ketua_panitia,
    ak_user.id_login AS id_akademis,
    ke_user.id_login AS id_keuangan,
    ad_user.id_login AS id_administrasi
    ')
    ->from('tbl_detail_pelatihan dp')
    ->join('tbl_pelatihan p', 'p.id_pelatihan = dp.id_pelatihan', 'left')
    ->join('tbl_panitia_pelatihan pp_ketua', 'pp_ketua.id = dp.id_ketua_panitia', 'left')
    ->join('tbl_login kp_user', 'kp_user.id_login = pp_ketua.panitia_id', 'left')
    ->join('tbl_panitia_pelatihan pp_akademis', 'pp_akademis.id = dp.id_akademis', 'left')
    ->join('tbl_login ak_user', 'ak_user.id_login = pp_akademis.panitia_id', 'left')
    ->join('tbl_panitia_pelatihan pp_keuangan', 'pp_keuangan.id = dp.id_keuangan', 'left')
    ->join('tbl_login ke_user', 'ke_user.id_login = pp_keuangan.panitia_id', 'left')
    ->join('tbl_panitia_pelatihan pp_admin', 'pp_admin.id = dp.id_administrasi', 'left')
    ->join('tbl_login ad_user', 'ad_user.id_login = pp_admin.panitia_id', 'left')
    ->where('dp.id_detail_pelatihan', $id)
    ->get()->row();

    if (!$row) {
        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
            <p>DETAIL PELATIHAN TIDAK DITEMUKAN</p></div></div>');
        return redirect(base_url('data/detailpelatihan'));
    }

    $jenis_param = $this->input->get('jenis', TRUE);
    if ($jenis_param) {
        $jenis = $jenis_param;
        $id_jenis = ($jenis === 'PJJ' ? 1 : ($jenis === 'PDWK' ? 2 : ($jenis === 'Latsar' ? 3 : null)));
    } else {
        $id_jenis = isset($row->id_jenis_pelatihan) ? (int)$row->id_jenis_pelatihan : null;
        $jenis = ($id_jenis === 1 ? 'PJJ' : ($id_jenis === 2 ? 'PDWK' : ($id_jenis === 3 ? 'Latsar' : null)));
    }
    $is_latsar = ($id_jenis === 3);

    // Data dasar utk view
    $this->data['detail_pelatihan'] = $row;
    $this->data['jenis']     = $jenis;
    $this->data['id_jenis']  = $id_jenis;
    $this->data['is_latsar'] = $is_latsar;

    // Dropdown pelatihan & pegawai
    $this->db->from('tbl_pelatihan');
    $this->db->where('deleted_at', NULL);
    if (!is_null($id_jenis)) $this->db->where('id_jenis_pelatihan', $id_jenis);
    $this->db->order_by('id_pelatihan', 'DESC');
    $this->data['pelatihans'] = $this->db->get()->result_array();

    $this->data['pegawais'] = $this->db
        ->where('deleted_at', NULL)
        ->order_by('id_pegawai', 'DESC')
        ->get('tbl_pegawai')->result_array();

    $this->data['panitia'] = $this->db
        ->select('id_login AS id_pegawai, nama')
        ->from('tbl_login')
        ->where_in('level', ['Panitia'])
        ->order_by('id_login', 'DESC')
        ->get()->result_array();

    // === PREFILL ASSIGNMENTS UNTUK PJJ/PDWK ===
    $wi_selected = []; $pengajar_selected = []; $wi_rapat_selected = NULL;
    if (in_array((int)$id_jenis, [1,2], true)) {
        $map = $this->_get_pengajar_assignments((int)$row->id_pelatihan);
        $wi_selected       = $map['wi_ids'];
        $pengajar_selected = $map['pengajar_ids'];
        $wi_rapat_selected = $map['wi_rapat_id'];
    } else {
        // Latsar: boleh gunakan field lama sebagai prefill (opsional)
        foreach (['id_wi_1','id_wi_2','id_wi_3','id_wi_4'] as $f) {
            if (!empty($row->$f)) $wi_selected[] = (int)$row->$f;
        }
        if (!empty($row->id_wi_rapat_kelulusan)) $wi_rapat_selected = (int)$row->id_wi_rapat_kelulusan;
        foreach (['id_pengajar_1','id_pengajar_2','id_pengajar_3'] as $f) {
            if (!empty($row->$f)) $pengajar_selected[] = (int)$row->$f;
        }
    }
    $this->data['wi_selected']       = $wi_selected;
    $this->data['pengajar_selected'] = $pengajar_selected;
    $this->data['wi_rapat_selected'] = $wi_rapat_selected;

    // Title
    $baseTitle = 'Edit Detail Pelatihan';
    if     ($jenis === 'PJJ')    $this->data['title_web'] = $baseTitle . ' – PJJ';
    elseif ($jenis === 'PDWK')   $this->data['title_web'] = $baseTitle . ' – PDWK';
    elseif ($jenis === 'Latsar') $this->data['title_web'] = $baseTitle . ' – Latsar CPNS';
    else                         $this->data['title_web'] = $baseTitle;

    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('detail_pelatihan/edit_view', $this->data);
    $this->load->view('footer_view', $this->data);
}


	// Code LDK Pekanbaru Materi Pelatihan

	public function materipelatihan()
    {
        $this->data['idbo'] = $this->session->userdata('ses_id');
        $panitia_id = $this->session->userdata('id_login');
        $level = $this->session->userdata('level');

        // Ambil parameter jenis pelatihan dari URL
        $jenis = $this->input->get('jenis');
        $id_jenis = null;

        // Konversi parameter jenis ke id_jenis_pelatihan
        if ($jenis === 'PJJ') {
            $id_jenis = 1;
            $this->data['title_web'] = 'Materi Pelatihan PJJ';
        } elseif ($jenis === 'PDWK') {
            $id_jenis = 2;
            $this->data['title_web'] = 'Materi Pelatihan PDWK';
        } elseif ($jenis === 'Latsar') {
            $id_jenis = 3;
            $this->data['title_web'] = 'Materi Pelatihan Dasar CPNS';
        } else {
            $this->data['title_web'] = 'Data Materi Pelatihan';
        }

        // === QUERY DASAR ===
        $this->db->select('
            mp.*,
            p.nama_kegiatan,
            p.id_jenis_pelatihan
        ');
        $this->db->from('tbl_materi_pelatihan mp');
        $this->db->join('tbl_pelatihan p', 'mp.id_pelatihan = p.id_pelatihan', 'left');
        $this->db->where('mp.deleted_at IS NULL', null, false);

        // Filter jenis pelatihan jika ada
        if ($id_jenis !== null) {
            $this->db->where('p.id_jenis_pelatihan', $id_jenis);
        }

        // 🔹 Jika bukan admin, filter hanya pelatihan di mana user ini menjadi panitia
        if ($level !== 'admin' && $level !== 'Admin') {
            $this->db->join('tbl_panitia_pelatihan pp', 'pp.pelatihan_id = p.id_pelatihan', 'left');
            $this->db->where('pp.panitia_id', $panitia_id);
        }

        $this->db->order_by('mp.id_materi_pelatihan', 'DESC');
        $this->data['materi_pelatihan'] = $this->db->get()->result();

        // === AMBIL DATA DETAIL UNTUK EDIT ===
        if (!empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            $count = $this->M_Admin->CountTableId('tbl_materi_pelatihan', 'id_materi_pelatihan', $id);

            if ($count > 0) {
                $this->data['materi_pelatihans'] = $this->db->query("
                    SELECT 
                        mp.*, 
                        p.nama_kegiatan,
                        p.id_jenis_pelatihan
                    FROM tbl_materi_pelatihan mp
                    LEFT JOIN tbl_pelatihan p ON mp.id_pelatihan = p.id_pelatihan
                    WHERE mp.id_materi_pelatihan = '$id'
                ")->row();
            } else {
                $this->session->set_flashdata('error', 'Materi pelatihan tidak ditemukan');
                redirect('data/materipelatihan?jenis=');
            }
        }

        // Simpan informasi level untuk view
        if ($this->session->userdata('level') == 'Panitia') {
            $this->data['level'] = 'Admin';
        } else {
            $this->data['level'] = $this->session->userdata('level');
        }

        // === LOAD VIEW ===
        $this->load->view('header_view', $this->data);
        $this->load->view('sidebar_view', $this->data);
        $this->load->view('materi_pelatihan/materi_pelatihan_view', $this->data);
        $this->load->view('footer_view', $this->data);
    }


	public function prosesmateripelatihan()
	{
		// Cek apakah user sudah login
		if ($this->session->userdata('masuk_perpus') != TRUE) {
			redirect(base_url('login'));
		}

		// // === SOFT DELETE MATERI PELATIHAN ===
		// if (!empty($this->input->get('id_materi_pelatihan'))) {
		// 	$id_materi_pelatihan = htmlentities($this->input->get('id_materi_pelatihan'));

		// 	$materi_pelatihan = $this->M_Admin->get_tableid_edit(
		// 		'tbl_materi_pelatihan',
		// 		'id_materi_pelatihan',
		// 		$id_materi_pelatihan
		// 	);

		// 	if ($materi_pelatihan) {
		// 		$this->db->set('deleted_at', date('Y-m-d H:i:s'));
		// 		$this->db->where('id_materi_pelatihan', $id_materi_pelatihan);
		// 		$this->db->update('tbl_materi_pelatihan');

		// 		$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-warning">
		// 			<p>Berhasil Hapus (Soft Delete) Materi Pelatihan!</p>
		// 		</div></div>');
		// 	} else {
		// 		$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
		// 			<p>Data Materi Pelatihan tidak ditemukan!</p>
		// 		</div></div>');
		// 	}

		// 	redirect(base_url('data/materipelatihan'));
		// }

        // === HARD DELETE MATERI PELATIHAN ===
        if (!empty($this->input->get('id_materi_pelatihan'))) {

            $id_materi_pelatihan = (int) $this->input->get('id_materi_pelatihan', TRUE);

            $materi_pelatihan = $this->M_Admin->get_tableid_edit(
                'tbl_materi_pelatihan',
                'id_materi_pelatihan',
                $id_materi_pelatihan
            );

            if ($materi_pelatihan) {

                $this->db->where('id_materi_pelatihan', $id_materi_pelatihan);
                $this->db->delete('tbl_materi_pelatihan');

                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
                        <p>Berhasil Hapus (Hard Delete) Materi Pelatihan!</p>
                    </div></div>');
                } else {
                    $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
                        <p>Gagal menghapus data!</p>
                    </div></div>');
                }

            } else {

                $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
                    <p>Data Materi Pelatihan tidak ditemukan!</p>
                </div></div>');
            }

            redirect(base_url('data/materipelatihan'));
        }

		// === TAMBAH MATERI PELATIHAN ===
		if (!empty($this->input->post('tambah'))) {
			$post = $this->input->post();

			$data = array(
				'id_pelatihan' => htmlentities($post['id_pelatihan']),
				'jumlah_jp' => !empty($post['jumlah_jp']) ? htmlentities($post['jumlah_jp']) : 0,
				'jp_kel_dasar' => !empty($post['jp_kel_dasar']) ? htmlentities($post['jp_kel_dasar']) : 0,
				'jp_kel_inti' => !empty($post['jp_kel_inti']) ? htmlentities($post['jp_kel_inti']) : 0,
				'jp_kel_penunjang' => !empty($post['jp_kel_penunjang']) ? htmlentities($post['jp_kel_penunjang']) : 0,
				'nama_mata_pelatihan_kel_dasar' => htmlentities($post['nama_mata_pelatihan_kel_dasar']),
				// 'nama_mata_pelatihan_kel_dasar' => json_encode(array_map('trim', preg_split('/\d+\./', $post['nama_mata_pelatihan_kel_dasar'], -1, PREG_SPLIT_NO_EMPTY))),
				'nama_mata_pelatihan_kel_inti' => htmlentities($post['nama_mata_pelatihan_kel_inti']),
				'nama_mata_pelatihan_kel_penunjang' => htmlentities($post['nama_mata_pelatihan_kel_penunjang']),
				'latar_belakang' => htmlentities($post['latar_belakang']),
				'tujuan_pelatihan' => htmlentities($post['tujuan_pelatihan']),
				'tujuan_kursil' => htmlentities($post['tujuan_kursil']),
				'asal_kursil' => htmlentities($post['asal_kursil']),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'deleted_at' => NULL
			);

			$this->db->insert('tbl_materi_pelatihan', $data);

			$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
				<p>Tambah Materi Pelatihan Sukses!</p>
			</div></div>');
			redirect(base_url('data/materipelatihan'));
		}

		// === EDIT MATERI PELATIHAN ===
		if (!empty($this->input->post('edit'))) {
			$post = $this->input->post();

			$data = array(
				'id_pelatihan' => htmlentities($post['id_pelatihan']),
				'jumlah_jp' => !empty($post['jumlah_jp']) ? htmlentities($post['jumlah_jp']) : 0,
				'jp_kel_dasar' => !empty($post['jp_kel_dasar']) ? htmlentities($post['jp_kel_dasar']) : 0,
				'jp_kel_inti' => !empty($post['jp_kel_inti']) ? htmlentities($post['jp_kel_inti']) : 0,
				'jp_kel_penunjang' => !empty($post['jp_kel_penunjang']) ? htmlentities($post['jp_kel_penunjang']) : 0,
				'nama_mata_pelatihan_kel_dasar' => htmlentities($post['nama_mata_pelatihan_kel_dasar']),
				'nama_mata_pelatihan_kel_inti' => htmlentities($post['nama_mata_pelatihan_kel_inti']),
				'nama_mata_pelatihan_kel_penunjang' => htmlentities($post['nama_mata_pelatihan_kel_penunjang']),
				'latar_belakang' => htmlentities($post['latar_belakang']),
				'tujuan_pelatihan' => htmlentities($post['tujuan_pelatihan']),
				'tujuan_kursil' => htmlentities($post['tujuan_kursil']),
				'asal_kursil' => htmlentities($post['asal_kursil']),
				'updated_at' => date('Y-m-d H:i:s')
			);

			$this->db->where('id_materi_pelatihan', htmlentities($post['edit']));
			$this->db->update('tbl_materi_pelatihan', $data);

			$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
				<p>Edit Materi Pelatihan Sukses!</p>
			</div></div>');
			redirect(base_url('data/materipelatihan'));
		}
	}


	public function materipelatihantambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		// Ambil id_pelatihan yang sudah dipakai di tbl_detail_pelatihan
		$existing_detail_ids = $this->db->select('id_pelatihan')
										->from('tbl_materi_pelatihan')
										->where('deleted_at', NULL) // Opsional jika menggunakan soft delete
										->get()
										->result_array();

		// Konversi ke array satu dimensi
		$used_ids = array_column($existing_detail_ids, 'id_pelatihan');

		// Ambil hanya pelatihan yang belum dipakai
		if (!empty($used_ids)) {
			$this->data['pelatihans'] = $this->db
				->where_not_in('id_pelatihan', $used_ids)
				->where('deleted_at', NULL) // Opsional untuk soft delete
				->order_by('id_pelatihan', 'DESC')
				->get('tbl_pelatihan')
				->result_array();
		} else {
			// Jika belum ada data di tbl_detail_pelatihan, ambil semua
			$this->data['pelatihans'] = $this->db
				->where('deleted_at', NULL)
				->order_by('id_pelatihan', 'DESC')
				->get('tbl_pelatihan')
				->result_array();
		}

		// // Ambil semua pegawai
		// $this->data['pegawais'] = $this->db
		// 	->where('deleted_at', NULL)
		// 	->order_by('id_pegawai', 'DESC')
		// 	->get('tbl_pegawai')
		// 	->result_array();

		$this->data['title_web'] = 'Tambah Materi Pelatihan';

		// Load views
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('materi_pelatihan/tambah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function materipelatihandetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_materi_pelatihan','id_materi_pelatihan',$this->uri->segment('3'));
		if($count > 0)
		{
			$this->data['materi_pelatihan'] = $this->M_Admin->get_tableid_edit('tbl_materi_pelatihan','id_materi_pelatihan',$this->uri->segment('3'));
			$this->data['pelatihans'] =  $this->db->query("SELECT * FROM tbl_pelatihan ORDER BY id_pelatihan DESC")->result_array();

            $pelatihan  =$this->db->get_where('tbl_pelatihan', [
                'id_pelatihan' => $this->data['materi_pelatihan']->id_pelatihan
            ])->row();

            $jenis = '';

            if ($pelatihan) {
                $id_jenis = $pelatihan->id_jenis_pelatihan;

                switch ($id_jenis) {
                    case 1 :
                        $jenis = 'PJJ';
                        break;
                    case 2 :
                        $jenis = 'PDWK';
                        break;
                    case 3 :
                        $jenis = 'Latsar';
                        break;
                    default:
                        $jenis = '';
                }
            }

            $this->data['jenis'] = $jenis;

		}else{
			echo '<script>alert("PEGAWAI TIDAK DITEMUKAN");window.location="'.base_url('data/materipelatihan').'"</script>';
		}

		$this->data['title_web'] = 'Data Materi Pelatihan Detail';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('materi_pelatihan/detail',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function materipelatihanedit()
    {
        $this->data['idbo'] = $this->session->userdata('ses_id');
        $id = $this->uri->segment(3);
        $count = $this->M_Admin->CountTableId('tbl_materi_pelatihan', 'id_materi_pelatihan', $id);

        if ($count > 0) {
            $this->data['materi_pelatihan'] = $this->M_Admin->get_tableid_edit('tbl_materi_pelatihan', 'id_materi_pelatihan', $id);
            $this->data['pelatihans'] = $this->db->query("SELECT * FROM tbl_pelatihan ORDER BY id_pelatihan DESC")->result_array();

            // Ambil data jenis dari tabel pelatihan berdasarkan id_pelatihan
            $pelatihan = $this->db->get_where('tbl_pelatihan', [
                'id_pelatihan' => $this->data['materi_pelatihan']->id_pelatihan
            ])->row();

            // Default
            $jenis = '';

            // Pastikan ada hasil pelatihan
            if ($pelatihan) {
                $id_jenis = $pelatihan->id_jenis_pelatihan;

                // Mapping ID ke nama jenis
                switch ($id_jenis) {
                    case 1:
                        $jenis = 'PJJ';
                        break;
                    case 2:
                        $jenis = 'PDWK';
                        break;
                    case 3:
                        $jenis = 'Latsar';
                        break;
                    default:
                        $jenis = 'lainnya';
                }
            }

            $this->data['jenis'] = $jenis;

        } else {
            echo '<script>alert("Materi Pelatihan tidak ditemukan");window.location="' . base_url('data/materipelatihan') . '"</script>';
        }

        $this->data['title_web'] = 'Data Materi Pelatihan Edit';
        $this->load->view('header_view', $this->data);
        $this->load->view('sidebar_view', $this->data);
        $this->load->view('materi_pelatihan/edit_view', $this->data);
        $this->load->view('footer_view', $this->data);
    }


	// Code LDK Pekanbaru Controller Menu Master Document
	
	public function dokumen()
	{
		// Ambil ID user yang login
		$this->data['idbo'] = $this->session->userdata('ses_id');

		// Ambil semua dokumen yang belum dihapus (deleted_at IS NULL)
		$this->data['dokumen'] = $this->db->query("SELECT * FROM tbl_dokumen WHERE deleted_at IS NULL ORDER BY id_dokumen DESC");

		// Cek apakah ada parameter ?id= di URL
		if (!empty($this->input->get('id'))) {
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_dokumen', 'id_dokumen', $id);

			if ($count > 0) {
				// Tetap ambil datanya (tanpa filtering deleted_at karena ini konteks pengeditan spesifik)
				$this->data['dokumen'] = $this->db->query("SELECT * FROM tbl_dokumen WHERE id_dokumen='$id'")->row();
			} else {
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="' . base_url('data/dokumen') . '"</script>';
			}
		}

		// Set judul dan load view
		$this->data['title_web'] = 'Data Dokumen';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('dokumen/dokumen_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}


	public function prosesdokumen()
	{
		if($this->session->userdata('masuk_perpus') != TRUE){
			redirect(base_url('login'));
		}
	
		// // hapus aksi form proses dokumen (soft delete)
		// if(!empty($this->input->get('id_dokumen')))
		// {
		// 	$dokumen = $this->M_Admin->get_tableid_edit(
		// 		'tbl_dokumen',
		// 		'id_dokumen',
		// 		htmlentities($this->input->get('id_dokumen'))
		// 	);
		// 	$id_dokumen = htmlentities($this->input->get('id_dokumen'));
	
		// 	// Soft delete: set deleted_at timestamp
		// 	$this->db->set('deleted_at', date('Y-m-d H:i:s'));
		// 	$this->db->where('id_dokumen', $id_dokumen);
		// 	$this->db->update('tbl_dokumen');
	
		// 	$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
		// 		<p>Berhasil Hapus Dokumen!</p>
		// 	</div></div>');
		// 	redirect(base_url('data/dokumen'));
		// }

        // === HARD DELETE DOKUMEN ===
        if (!empty($this->input->get('id_dokumen'))) {

            $id_dokumen = (int) $this->input->get('id_dokumen', TRUE);

            $dokumen = $this->M_Admin->get_tableid_edit(
                'tbl_dokumen',
                'id_dokumen',
                $id_dokumen
            );

            if ($dokumen) {

                $this->db->where('id_dokumen', $id_dokumen);
                $this->db->delete('tbl_dokumen');

                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
                        <p>Berhasil Hapus Dokumen (Hard Delete)!</p>
                    </div></div>');
                } else {
                    $this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
                        <p>Gagal menghapus dokumen!</p>
                    </div></div>');
                }

            } else {

                $this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
                    <p>Data dokumen tidak ditemukan!</p>
                </div></div>');
            }

            redirect(base_url('data/dokumen'));
        }

	
		// tambah aksi form proses role
		if(!empty($this->input->post('tambah')))
		{
			$post = $this->input->post();
			$data = array(
				'nama_dokumen' => htmlentities($post['nama_dokumen']),
				'deskripsi' => htmlentities($post['deskripsi']),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'deleted_at' => NULL
			);
	
			$this->db->insert('tbl_dokumen', $data);
	
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
				<p>Tambah Dokumen Sukses!</p>
			</div></div>');
			redirect(base_url('data/dokumen'));
		}
	
		// edit aksi form proses role
		if(!empty($this->input->post('edit')))
		{
			$post = $this->input->post();
			$data = array(
				'nama_dokumen' => htmlentities($post['nama_dokumen']),
				'deskripsi' => htmlentities($post['deskripsi']),
				'updated_at' => date('Y-m-d H:i:s')
			);
	
			$this->db->where('id_dokumen', htmlentities($post['edit']));
			$this->db->update('tbl_dokumen', $data);
	
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
				<p>Edit Dokumen Sukses!</p>
			</div></div>');
			// redirect(base_url('data/roleedit/'.$post['edit']));
	
			// Ganti redirect ke halaman utama data role
			redirect(base_url('data/dokumen'));
		}
	}

	public function dokumentambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

        $this->data['title_web'] = 'Tambah Dokumen';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('dokumen/tambah_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function dokumendetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_dokumen','id_dokumen',$this->uri->segment('3'));
		if($count > 0)
		{
			$this->data['dokumen'] = $this->M_Admin->get_tableid_edit('tbl_dokumen','id_dokumen',$this->uri->segment('3'));

		}else{
			echo '<script>alert("DOKUMEN TIDAK DITEMUKAN");window.location="'.base_url('data/dokumen').'"</script>';
		}

		$this->data['title_web'] = 'Data Dokumen Detail';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('dokumen/detail',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function dokumenedit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_dokumen','id_dokumen',$this->uri->segment('3'));
		if($count > 0)
		{
			
			$this->data['dokumen'] = $this->M_Admin->get_tableid_edit('tbl_dokumen','id_dokumen',$this->uri->segment('3'));

		}else{
			echo '<script>alert("DOKUMEN TIDAK DITEMUKAN");window.location="'.base_url('data/dokumen').'"</script>';
		}

		$this->data['title_web'] = 'Data Role Edit';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('dokumen/edit_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	// Code LDK Pekanbaru Controller Menu Dokumen Pelatihan

	public function dokumenpelatihan()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
        $panitia_id = $this->session->userdata('id_login');
        $level = $this->session->userdata('level');
    	$jenis = $this->input->get('jenis', TRUE);
        $id_jenis = null;

    	$this->data['pelatihan'] = $this->db->query("SELECT * FROM tbl_pelatihan WHERE deleted_at IS NULL ORDER BY id_pelatihan DESC");
		// Terapkan filter berdasarkan jenis
		if ($jenis === 'PJJ') {
            $id_jenis = 1;
            $this->data['title_web'] = 'Data Dokumen Pelatihan PJJ';
        } elseif ($jenis === 'PDWK') {
            $id_jenis = 2;
            $this->data['title_web'] = 'Data Dokumen Pelatihan PDWK';
        } elseif ($jenis === 'Latsar') {
            $id_jenis = 3;
            $this->data['title_web'] = 'Data Dokumen Pelatihan Dasar CPNS';
        } else {
            $this->data['title_web'] = 'Data Pelatihan Lampiran Dokumen';
        }

        $this->db->select('p.*, j.nama_jenis_pelatihan');
        $this->db->from('tbl_pelatihan p');
        $this->db->join('tbl_jenis_pelatihan j', 'j.id_jenis_pelatihan = p.id_jenis_pelatihan', 'left');
        $this->db->where('p.deleted_at IS NULL', NULL, FALSE);

        if (!is_null($id_jenis)) {
            $this->db->where('p.id_jenis_pelatihan', (int)$id_jenis);
        }

        if (strtolower($level) === 'panitia') {
            $this->db->join('tbl_panitia_pelatihan pp', 'pp.pelatihan_id = p.id_pelatihan', 'inner');
            $this->db->where('pp.panitia_id', $panitia_id);
        }

        $this->db->order_by('p.id_pelatihan', 'DESC');
        $this->data['pelatihan'] = $this->db->get();

        // $this->data['title_web'] = 'Data Pelatihan Lampiran Dokumen';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('dokumen_pelatihan/list_pelatihan',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function listdokumenpelatihan($id_pelatihan)
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
			'id_pelatihan' => $id_pelatihan,
			'deleted_at' => NULL
		])->row();

		if (!$cek_pelatihan) {
			echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/dokumenpelatihan') . '"</script>';
			return;
		}

		$this->data['pelatihan'] = $cek_pelatihan;
		$this->data['dokumen_pelatihan'] = $this->db->query("
			SELECT pd.*, d.nama_dokumen, d.deskripsi 
			FROM tbl_pelatihan_dokumen pd
			JOIN tbl_dokumen d ON pd.id_dokumen = d.id_dokumen
			WHERE pd.id_pelatihan = ? AND pd.deleted_at IS NULL
			ORDER BY pd.id_pelatihan_dokumen DESC
		", [$id_pelatihan]);

		// Ambil dokumen yang sudah dipakai di tbl_pelatihan_dokumen untuk pelatihan ini
		$used_doc_ids = $this->db->select('id_dokumen')
			->from('tbl_pelatihan_dokumen')
			->where('id_pelatihan', $id_pelatihan)
			->where('deleted_at', NULL)
			->get()
			->result_array();

		$used_ids = array_column($used_doc_ids, 'id_dokumen');

		// Ambil dokumen yang belum digunakan
		if (!empty($used_ids)) {
			$this->data['dokumen_all'] = $this->db
				->where_not_in('id_dokumen', $used_ids)
				->where('deleted_at', NULL)
				->order_by('id_dokumen', 'DESC')
				->get('tbl_dokumen')
				->result();
		} else {
			$this->data['dokumen_all'] = $this->db
				->where('deleted_at', NULL)
				->order_by('id_dokumen', 'DESC')
				->get('tbl_dokumen')
				->result();
		}

		if (!empty($used_ids)) {
		// INI AKAN DIPAKAI UNTUK EDIT, MAKA AMBIL SEMUA, TERMASUK YANG SUDAH DIPILIH
		$this->data['dokumen_all_raw'] = $this->db
			->where('deleted_at', NULL)
			->order_by('id_dokumen', 'DESC')
			->get('tbl_dokumen')
			->result();
		} else {
			$this->data['dokumen_all_raw'] = $this->db
				->where('deleted_at', NULL)
				->order_by('id_dokumen', 'DESC')
				->get('tbl_dokumen')
				->result();
		}

		$this->data['id_pelatihan'] = $id_pelatihan;
		$this->data['title_web'] = 'Lampiran Dokumen - ' . htmlentities($cek_pelatihan->nama_pelatihan);
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('dokumen_pelatihan/list_dokumen_pelatihan', $this->data);
		$this->load->view('footer_view', $this->data);
	}


	public function generateLaporan($id_pelatihan)
{
    $sess = $this->session->userdata('ses_id');
    $this->load->helper('date');

    if ($sess == null) {
        redirect('cetak_laporan/list_pelatihan_pjj');
        echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/dokumenpelatihan') . '"</script>';
        return;
    }

    // Ambil pelatihan dengan seluruh komponen (materi, peserta, agenda+topik+grup, pegawai, peringkat, dsb)
    $pelatihan = $this->M_Admin->dataPelatihan((int)$id_pelatihan);
    if (!$pelatihan) {
        echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/dokumenpelatihan') . '"</script>';
        return;
    }

    // Tentukan library berdasarkan jenis pelatihan
    $jenis_pelatihan = (int)$pelatihan->id_jenis_pelatihan;
    if ($jenis_pelatihan === 2) { // PDWK
        $this->load->library('wordgenerator_pdwk');
        $word_generator = $this->wordgenerator_pdwk;
    } elseif ($jenis_pelatihan === 3) { // Latsar
        $this->load->library('wordgenerator_latsar');
        $word_generator = $this->wordgenerator_latsar;
    } else { // PJJ (default)
        $this->load->library('wordgenerator');
        $word_generator = $this->wordgenerator;
    }

    $durasi     = $this->M_Admin->get_durasi_pelatihan($id_pelatihan);
    $ketua_loka = $this->M_Admin->get_ketua_loka();

    // Siapkan data untuk generator (akses langsung sebagai object)
    $data = [
        'pelatihan'        => $pelatihan,
        'durasi'           => $durasi,
        'tanggal_mulai'    => format_tanggal_indonesia($pelatihan->tanggal_mulai_pelatihan),
        'tanggal_selesai'  => format_tanggal_indonesia($pelatihan->tanggal_selesai_pelatihan),
        'ketua_loka'       => $ketua_loka
    ];

    // Pastikan materi punya parsed_tujuan (bila ada)
    if (!empty($pelatihan->materi)) {
        foreach ($pelatihan->materi as $materi) {
            $materi->parsed_tujuan = $this->M_Admin->parseTujuanKursil($materi->tujuan_kursil);
        }
    }

    try {
        // Naikkan notice/warning menjadi exception saat generate
        set_error_handler(function ($severity, $message, $file, $line) {
            if (!(error_reporting() & $severity)) return false;
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        $filename = $word_generator->generate($data);

    } catch (\Throwable $e) {
        restore_error_handler();
        log_message('error', "Wordgen gagal: {$e->getMessage()}\n{$e->getTraceAsString()}");
        show_error(
            'Gagal generate dokumen: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
            500,
            'Word Generator Error'
        );
        return;
    } finally {
        restore_error_handler();
    }

    if (!$filename) {
        show_error(
            'Gagal generate dokumen: Library mengembalikan FALSE tanpa exception. Cek path aset, izin folder downloads/, atau modifikasi library agar melempar exception saat gagal.',
            500,
            'Word Generator Error'
        );
        return;
    }

    $filepath = FCPATH . 'downloads/' . $filename;
    if (file_exists($filepath)) {
        header("Content-Description: File Transfer");
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: inline; filename=" . basename($filepath));
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        show_error('File tidak ditemukan: ' . $filepath);
    }
}


	// public function generateLaporanpdwk($id_pelatihan)
	// {
	// 	// $this->data['idbo'] = $this->session->userdata('ses_id');
	// 	$sess = $this->session->userdata('ses_id');
	// 	$this->load->helper('date');

	// 	if ($sess == null){
	// 		redirect('cetak_laporan/list_pelatihan_pdwk');
	// 		echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/dokumenpelatihan') . '"</script>';
	// 	} else {

	// 		$pelatihan= $this->M_Admin->dataPelatihan($id_pelatihan);
	// 		$durasi = $this->M_Admin->get_durasi_pelatihan($id_pelatihan);
	// 		$pelatihanData = is_object($pelatihan) ? json_decode(json_encode($pelatihan), true) : $pelatihan;
	// 		$ketua_loka = $this->M_Admin->get_ketua_loka();

	// 		echo '<pre>'; 
	// 		print_r($pelatihan ?? 'Tidak ada data'); 
	// 		die();
	
	// 		$data = [
	// 			'pelatihan' => $pelatihan,
	// 			// 'detail' => $pelatihan->detail,
	// 			// 'pegawai' => $pelatihan->pegawai,
	// 			'durasi' => $durasi,
	// 			'tanggal_mulai'  => format_tanggal_indonesia($pelatihanData['tanggal_mulai_pelatihan']),
    //         	'tanggal_selesai' => format_tanggal_indonesia($pelatihanData['tanggal_selesai_pelatihan']),
	// 			'ketua_loka' => $ketua_loka
	// 		];

	// 		if (!empty($pelatihan->materi)) {
	// 			foreach ($pelatihan->materi as $materi) {
	// 				$materi->parsed_tujuan = $this->M_Admin->parseTujuanKursil($materi->tujuan_kursil);
	// 			}
	// 		}	
			
			

	// 		$filename = $this->wordgenerator_pdwk->generate($data);
	// 		if (!$filename){
	// 			show_error('Gagal generate dokumen');
	// 		}
	// 		$filepath = FCPATH . 'downloads/' . $filename;
	// 		if(file_exists($filepath)){
				
	// 			header("Content-Description: File Transfer");
	// 			header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
	// 			header("Content-Disposition: inline; filename= " .basename($filepath));
	// 			header("Content-Transfer-Encoding: binary");
	// 			header("Expires: 0");
	// 			header("Cache-Control: must-revalidate");
	// 			header("Pragma: public");
	// 			header("Content-Length: " . filesize($filepath));
				
	// 			readfile($filepath);
	// 			exit;
	// 		} else {
	// 			show_error('File tidak ditemukan' . $filepath);
	// 		}

	// 	}

	// 	// $cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
	// 	// 	'id_pelatihan' => $id_pelatihan,
	// 	// 	'deleted_at' => NULL
	// 	// ])->row();

	// 	// if (!$cek_pelatihan) {
	// 	// 	echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/dokumenpelatihan') . '"</script>';
	// 	// 	return;
	// 	// }

	// 	$this->data['pelatihan'] = $cek_pelatihan;
	// 	$this->data['dokumen_pelatihan'] = $this->db->query("
	// 		SELECT pd.*, d.nama_dokumen, d.deskripsi 
	// 		FROM tbl_pelatihan_dokumen pd
	// 		JOIN tbl_dokumen d ON pd.id_dokumen = d.id_dokumen
	// 		WHERE pd.id_pelatihan = ? AND pd.deleted_at IS NULL
	// 		ORDER BY pd.id_pelatihan_dokumen DESC
	// 	", [$id_pelatihan]);

	// 	// Ambil dokumen yang sudah dipakai di tbl_pelatihan_dokumen untuk pelatihan ini
	// 	$used_doc_ids = $this->db->select('id_dokumen')
	// 		->from('tbl_pelatihan_dokumen')
	// 		->where('id_pelatihan', $id_pelatihan)
	// 		->where('deleted_at', NULL)
	// 		->get()
	// 		->result_array();

	// 	$used_ids = array_column($used_doc_ids, 'id_dokumen');

	// 	// Ambil dokumen yang belum digunakan
	// 	if (!empty($used_ids)) {
	// 		$this->data['dokumen_all'] = $this->db
	// 			->where_not_in('id_dokumen', $used_ids)
	// 			->where('deleted_at', NULL)
	// 			->order_by('id_dokumen', 'DESC')
	// 			->get('tbl_dokumen')
	// 			->result();
	// 	} else {
	// 		$this->data['dokumen_all'] = $this->db
	// 			->where('deleted_at', NULL)
	// 			->order_by('id_dokumen', 'DESC')
	// 			->get('tbl_dokumen')
	// 			->result();
	// 	}

	// 	if (!empty($used_ids)) {
	// 	// INI AKAN DIPAKAI UNTUK EDIT, MAKA AMBIL SEMUA, TERMASUK YANG SUDAH DIPILIH
	// 	$this->data['dokumen_all_raw'] = $this->db
	// 		->where('deleted_at', NULL)
	// 		->order_by('id_dokumen', 'DESC')
	// 		->get('tbl_dokumen')
	// 		->result();
	// 		} else {
	// 			$this->data['dokumen_all_raw'] = $this->db
	// 				->where('deleted_at', NULL)
	// 				->order_by('id_dokumen', 'DESC')
	// 				->get('tbl_dokumen')
	// 				->result();
	// 	}

	// 	$this->data['id_pelatihan'] = $id_pelatihan;
		
	// 	// $this->data['title_web'] = 'Lampiran Dokumen - ' . htmlentities($cek_pelatihan->nama_pelatihan);
	// 	// $this->load->view('header_view', $this->data);
	// 	// $this->load->view('sidebar_view', $this->data);
	// 	// $this->load->view('dokumen_pelatihan/list_dokumen_pelatihan', $this->data);
	// 	// $this->load->view('footer_view', $this->data);
	// }

	public function generateLaporanLatsar(){
				
		try {
			$filename = $this->wordgenerator_latsar->generate();
			
			if (!$filename){
				// Dapatkan error terakhir
				$error = error_get_last();
				log_message('error', 'Gagal generate dokumen. Error: ');
				
				return;
			}
			
			$filepath = FCPATH . 'downloads/' . $filename;
			
			if(file_exists($filepath)){
				echo '<h2>Laporan Berhasil Digenerate</h2>';
				echo '<p>Klik link berikut untuk mengunduh: ';
				echo '<a href="' . base_url('downloads/' . $filename) . '" download>Download Laporan</a></p>';
				echo '<p>Atau <a href="' . site_url(uri_string()) . '">generate ulang</a></p>';
			} else {
				echo '<h2>File Tidak Ditemukan</h2>';
				echo '<p>Path: ' . $filepath . '</p>';
				echo '<p><a href="' . site_url() . '">Kembali</a></p>';
			}
			
		} catch (Exception $e) {
			log_message('error', 'Exception: ' . $e->getMessage());
			echo '<h2>Terjadi Exception</h2>';
			echo '<p>' . $e->getMessage() . '</p>';
			echo '<p><a href="' . site_url() . '">Kembali</a></p>';
		}
	}

	public function prosesdokumenpelatihan()
	{
		$config['upload_path'] = FCPATH . 'assets_style/assets/dokumen/';
		$config['allowed_types'] = 'pdf|doc|docx|ppt|pptx|xls|xlsx|txt';
		$config['max_size'] = 10240; // 10 MB
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload');
        $this->upload->initialize($config);

		if ($this->session->userdata('masuk_perpus') != TRUE) {
			redirect(base_url('login'));
		}

		// Handle tambah dokumen pelatihan
		if (!empty($this->input->post('tambah'))) {
		$post = $this->input->post();
		$id_pelatihan = htmlentities($post['id_pelatihan']);
		$id_dokumen = htmlentities($post['id_dokumen']);
		$file_path = '';

		if (!empty($_FILES['file_upload']['name'])) {
			if (!$this->upload->do_upload('file_upload')) {
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
				redirect(base_url('data/listdokumenpelatihan/' . $id_pelatihan));
				return;
			} else {
				$upload_data = $this->upload->data();
				$file_path = $upload_data['file_name'];
			}
		}

		$cek = $this->db->get_where('tbl_pelatihan_dokumen', [
			'id_pelatihan' => $id_pelatihan,
			'id_dokumen' => $id_dokumen,
			'deleted_at' => NULL
		])->num_rows();

		if ($cek > 0) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger"><p>Dokumen ini sudah ditambahkan pada pelatihan!</p></div>');
			redirect(base_url('data/listdokumenpelatihan/' . $id_pelatihan));
			return;
		}

		$data = [
			'id_pelatihan' => $id_pelatihan,
			'id_dokumen' => $id_dokumen,
			'file_path' => $file_path,
			'tanggal_upload' => date('Y-m-d'),
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'deleted_at' => NULL
		];

		$this->db->insert('tbl_pelatihan_dokumen', $data);
		$this->session->set_flashdata('pesan', '<div class="alert alert-success"><p>Dokumen berhasil ditambahkan ke pelatihan!</p></div>');
		redirect(base_url('data/listdokumenpelatihan/' . $id_pelatihan));
	}


	// Handle edit dokumen pelatihan
	if (!empty($this->input->post('edit'))) {
	$post = $this->input->post();
	$id_pelatihan_dokumen = htmlentities($post['edit']);
	$id_pelatihan = htmlentities($post['id_pelatihan']);
	$id_dokumen = htmlentities($post['id_dokumen']);
	$dokumen_old = $this->db->get_where('tbl_pelatihan_dokumen', ['id_pelatihan_dokumen' => $id_pelatihan_dokumen])->row();
	$file_path = $dokumen_old->file_path;
	if (!empty($_FILES['file_upload']['name'])) {
		if (!$this->upload->do_upload('file_upload')) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
			redirect(base_url('data/listdokumenpelatihan/' . $id_pelatihan));
			return;
		} else {
			// Optional: delete old file
			if (file_exists('./assets_style/assets/dokumen/' . $file_path) && !empty($file_path)) {
				unlink('./assets_style/assets/dokumen/' . $file_path);
			}
			$upload_data = $this->upload->data();
			$file_path = $upload_data['file_name'];
		}
	}
	$this->db->where('id_pelatihan_dokumen', $id_pelatihan_dokumen);
	$this->db->update('tbl_pelatihan_dokumen', [
		'id_dokumen' => $id_dokumen,
		'file_path' => $file_path,
		'updated_at' => date('Y-m-d H:i:s')
	]);
	$this->session->set_flashdata('pesan', '<div class="alert alert-success"><p>Dokumen pelatihan berhasil diperbarui!</p></div>');
	redirect(base_url('data/listdokumenpelatihan/' . $id_pelatihan));
	}

	// // Handle hapus dokumen pelatihan (soft delete)
	// if (!empty($this->input->get('id_pelatihan_dokumen'))) {
	// 	$id_pelatihan_dokumen = htmlentities($this->input->get('id_pelatihan_dokumen'));
	// 	$data = $this->db->get_where('tbl_pelatihan_dokumen', [
	// 		'id_pelatihan_dokumen' => $id_pelatihan_dokumen
	// 	])->row();
	// 	if ($data) {
	// 		$this->db->set('deleted_at', date('Y-m-d H:i:s'));
	// 		$this->db->where('id_pelatihan_dokumen', $id_pelatihan_dokumen);
	// 		$this->db->update('tbl_pelatihan_dokumen');
	// 		$this->session->set_flashdata('pesan', '<div class="alert alert-warning"><p>Dokumen pelatihan berhasil dihapus!</p></div>');
	// 		redirect(base_url('data/listdokumenpelatihan/' . $data->id_pelatihan));
	// 	} else {
	// 		echo '<script>alert("Data tidak ditemukan."); window.location="' . base_url('data/dokumenpelatihan') . '"</script>';
	// 	}
	// }
    // === HANDLE HAPUS DOKUMEN PELATIHAN (HARD DELETE) ===
    if (!empty($this->input->get('id_pelatihan_dokumen'))) {

        $id_pelatihan_dokumen = (int) $this->input->get('id_pelatihan_dokumen', TRUE);

        $data = $this->db->get_where('tbl_pelatihan_dokumen', [
            'id_pelatihan_dokumen' => $id_pelatihan_dokumen
        ])->row();

        if ($data) {

            $this->db->where('id_pelatihan_dokumen', $id_pelatihan_dokumen);
            $this->db->delete('tbl_pelatihan_dokumen');

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-success">
                        <p>Dokumen pelatihan berhasil dihapus (Hard Delete)!</p>
                    </div>'
                );

                redirect(base_url('data/listdokumenpelatihan/' . $data->id_pelatihan));

            } else {

                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-danger">
                        <p>Gagal menghapus dokumen pelatihan!</p>
                    </div>'
                );

                redirect(base_url('data/listdokumenpelatihan/' . $data->id_pelatihan));
            }

        } else {

            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger">
                    <p>Data tidak ditemukan.</p>
                </div>'
            );

            redirect(base_url('data/dokumenpelatihan'));
        }
    }
	}

	// Code LDK Pekanbaru Controller Menu Dokumentasi Pelatihan

	public function dokumentasipelatihan()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$panitia_id = $this->session->userdata('id_login');
        $level = $this->session->userdata('level');
    	$jenis = $this->input->get('jenis');
    	$id_jenis = null;
    	$this->data['pelatihan'] = $this->db->query("SELECT * FROM tbl_pelatihan WHERE deleted_at IS NULL ORDER BY id_pelatihan DESC");
		// Terapkan filter berdasarkan jenis
		if ($jenis === 'PJJ') {
            $id_jenis = 1;
            $this->data['title_web'] = 'Data Dokumen Pelatihan PJJ';
        } elseif ($jenis === 'PDWK') {
            $id_jenis = 2;
            $this->data['title_web'] = 'Data Dokumen Pelatihan PDWK';
        } elseif ($jenis === 'Latsar') {
            $id_jenis = 3;
            $this->data['title_web'] = 'Data Dokumen Pelatihan Dasar CPNS';
        } else {
            $this->data['title_web'] = 'Data Pelatihan Lampiran Dokumen';
        }
		
		$this->db->select('p.*, j.nama_jenis_pelatihan');
        $this->db->from('tbl_pelatihan p');
        $this->db->join('tbl_jenis_pelatihan j', 'j.id_jenis_pelatihan = p.id_jenis_pelatihan', 'left');
        $this->db->where('p.deleted_at IS NULL', NULL, FALSE);

        if (!is_null($id_jenis)) {
            $this->db->where('p.id_jenis_pelatihan', (int)$id_jenis);
        }

        if (strtolower($level) === 'panitia') {
            $this->db->join('tbl_panitia_pelatihan pp', 'pp.pelatihan_id = p.id_pelatihan', 'inner');
            $this->db->where('pp.panitia_id', $panitia_id);
        }

        $this->db->order_by('p.id_pelatihan', 'DESC');
        $this->data['pelatihan'] = $this->db->get();
		$this->data['title_web'] = 'Data Kegiatan Pelatihan';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('dokumentasi_pelatihan/list_pelatihan',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function listkegiatanpelatihan($id_pelatihan)
	{
    	$this->data['idbo'] = $this->session->userdata('ses_id');

    	$cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
    	    'id_pelatihan' => $id_pelatihan,
    	    'deleted_at' => NULL
    	])->row();

    	if (!$cek_pelatihan) {
    	    echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/kegiatanpelatihan') . '"</script>';
    	    return;
    }

    $this->data['pelatihan'] = $cek_pelatihan;

    // Ambil semua kegiatan pelatihan terkait
    $this->data['dokumentasi_pelatihan'] = $this->db->query("
        SELECT a.*, p.nama as nama_narasumber
        FROM tbl_pelatihan_activity a
        LEFT JOIN tbl_pegawai p ON a.id_narasumber = p.id_pegawai
        WHERE a.id_pelatihan = ? AND a.deleted_at IS NULL
        ORDER BY a.tanggal_activity ASC, a.jam_mulai ASC
    ", [$id_pelatihan]);

    $this->data['pegawai'] = $this->db
        ->where('deleted_at', NULL)
        ->order_by('nama', 'ASC')
        ->get('tbl_pegawai')
        ->result();

    $this->data['id_pelatihan'] = $id_pelatihan;
    $this->data['title_web'] = 'Kegiatan Pelatihan - ' . htmlentities($cek_pelatihan->nama_pelatihan);
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('dokumentasi_pelatihan/list_kegiatan_pelatihan', $this->data);
    $this->load->view('footer_view', $this->data);
}

// Function helper untuk menghitung JP
private function calculate_jp($jam_mulai, $jam_selesai) {
    $start = new DateTime($jam_mulai);
    $end   = new DateTime($jam_selesai);
    $diff  = $start->diff($end);

    // Total menit
    $minutes = ($diff->h * 60) + $diff->i;

    // Misalnya: 1 JP = 45 menit
    return ceil($minutes / 45);
}


public function proseskegiatanpelatihan()
{
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        redirect(base_url('login'));
    }

	// Handle Upload Foto
	if (!empty($this->input->post('upload_foto'))) {
		$id_activity = htmlentities($this->input->post('id_activity'));
		$id_pelatihan = htmlentities($this->input->post('id_pelatihan'));
		$tanggal_foto = htmlentities($this->input->post('tanggal_foto'));
		$keterangan = htmlentities($this->input->post('keterangan'));

		if (!empty($_FILES['foto_kegiatan']['name'][0])) {
			$files = $_FILES['foto_kegiatan'];
			$count = count($files['name']);

			for ($i = 0; $i < $count; $i++) {
				if ($files['error'][$i] == 0) {
					$filename = time() . '_' . basename($files['name'][$i]);
					$upload_path = './assets/foto_kegiatan/';
					$save_path = 'assets/foto_kegiatan/' . $filename;

					if (!file_exists($upload_path)) {
						mkdir($upload_path, 0777, true);
					}

					move_uploaded_file($files['tmp_name'][$i], $upload_path . $filename);

					// Simpan ke database
					$this->db->insert('tbl_pelatihan_foto', [
						'id_activity'   => $id_activity,
						'foto_path'     => $save_path,
						'keterangan'    => $keterangan,
						'tanggal_foto'  => $tanggal_foto,
						'created_at'    => date('Y-m-d H:i:s'),
						'updated_at'    => date('Y-m-d H:i:s'),
						'deleted_at'    => NULL,
					]);
				}
			}

			$this->session->set_flashdata('pesan', '<div class="alert alert-success">Foto berhasil diupload.</div>');
		} else {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Tidak ada file yang dipilih.</div>');
		}

		redirect(base_url('data/listkegiatanpelatihan/' . $id_pelatihan));
	}

    // Handle Tambah Backup
    // if (!empty($this->input->post('tambah'))) {
    //     $post = $this->input->post();

    //     $data = [
    //         'id_pelatihan'      => htmlentities($post['id_pelatihan']),
    //         'sesi_ke'           => htmlentities($post['sesi_ke']),
    //         'day_ke'            => htmlentities($post['day_ke']),
    //         'nama_kegiatan'     => htmlentities($post['nama_kegiatan']),
    //         'id_narasumber'     => htmlentities($post['id_narasumber']),
    //         'activity_desc'     => htmlentities($post['activity_desc']),
    //         'tanggal_activity'  => htmlentities($post['tanggal_activity']),
    //         'jam_mulai'         => htmlentities($post['jam_mulai']),
    //         'jam_selesai'       => htmlentities($post['jam_selesai']),
    //         'created_at'        => date('Y-m-d H:i:s'),
    //         'updated_at'        => date('Y-m-d H:i:s'),
    //         'deleted_at'        => NULL
    //     ];

    //     $this->db->insert('tbl_pelatihan_activity', $data);
    //     $this->session->set_flashdata('pesan', '<div class="alert alert-success">Kegiatan berhasil ditambahkan!</div>');
    //     redirect(base_url('data/listkegiatanpelatihan/' . $post['id_pelatihan']));
    // }

	// Handle Tambah
	if (!empty($this->input->post('tambah'))) {
    $post = $this->input->post();

    // Hitung JP otomatis
    $jp_counts = $this->calculate_jp($post['jam_mulai'], $post['jam_selesai']);

    $data = [
        'id_pelatihan'      => htmlentities($post['id_pelatihan']),
        'sesi_ke'           => htmlentities($post['sesi_ke']),
        'day_ke'            => htmlentities($post['day_ke']),
        'nama_kegiatan'     => htmlentities($post['nama_kegiatan']),
        'id_narasumber'     => htmlentities($post['id_narasumber']),
        'activity_desc'     => htmlentities($post['activity_desc']),
        'tanggal_activity'  => htmlentities($post['tanggal_activity']),
        'jam_mulai'         => htmlentities($post['jam_mulai']),
        'jam_selesai'       => htmlentities($post['jam_selesai']),
        'jp_counts'         => $jp_counts,
        'jp_type'           => htmlentities($post['jp_type']),
        'created_at'        => date('Y-m-d H:i:s'),
        'updated_at'        => date('Y-m-d H:i:s'),
        'deleted_at'        => NULL
    ];

    $this->db->insert('tbl_pelatihan_activity', $data);
    $this->session->set_flashdata('pesan', '<div class="alert alert-success">Kegiatan berhasil ditambahkan!</div>');
    redirect(base_url('data/listkegiatanpelatihan/' . $post['id_pelatihan']));
}

    // Handle Edit Backup
    // if (!empty($this->input->post('edit'))) {
    //     $post = $this->input->post();

    //     $data = [
    //         'sesi_ke'           => htmlentities($post['sesi_ke']),
    //         'day_ke'            => htmlentities($post['day_ke']),
    //         'nama_kegiatan'     => htmlentities($post['nama_kegiatan']),
    //         'id_narasumber'     => htmlentities($post['id_narasumber']),
    //         'activity_desc'     => htmlentities($post['activity_desc']),
    //         'tanggal_activity'  => htmlentities($post['tanggal_activity']),
    //         'jam_mulai'         => htmlentities($post['jam_mulai']),
    //         'jam_selesai'       => htmlentities($post['jam_selesai']),
    //         'updated_at'        => date('Y-m-d H:i:s'),
    //     ];

    //     $this->db->where('id_activity', htmlentities($post['edit']));
    //     $this->db->update('tbl_pelatihan_activity', $data);
    //     $this->session->set_flashdata('pesan', '<div class="alert alert-success">Kegiatan berhasil diperbarui!</div>');
    //     redirect(base_url('data/listkegiatanpelatihan/' . $post['id_pelatihan']));
    // }

	// Handle Edit
	if (!empty($this->input->post('edit'))) {
		$post = $this->input->post();

		// Hitung ulang JP otomatis
		$jp_counts = $this->calculate_jp($post['jam_mulai'], $post['jam_selesai']);

		$data = [
			'sesi_ke'           => htmlentities($post['sesi_ke']),
			'day_ke'            => htmlentities($post['day_ke']),
			'nama_kegiatan'     => htmlentities($post['nama_kegiatan']),
			'id_narasumber'     => htmlentities($post['id_narasumber']),
			'activity_desc'     => htmlentities($post['activity_desc']),
			'tanggal_activity'  => htmlentities($post['tanggal_activity']),
			'jam_mulai'         => htmlentities($post['jam_mulai']),
			'jam_selesai'       => htmlentities($post['jam_selesai']),
			'jp_counts'         => $jp_counts,
			'jp_type'           => htmlentities($post['jp_type']),
			'updated_at'        => date('Y-m-d H:i:s'),
		];

		$this->db->where('id_activity', htmlentities($post['edit']));
		$this->db->update('tbl_pelatihan_activity', $data);
		$this->session->set_flashdata('pesan', '<div class="alert alert-success">Kegiatan berhasil diperbarui!</div>');
		redirect(base_url('data/listkegiatanpelatihan/' . $post['id_pelatihan']));
	}


	// // Handle Delete Foto (soft delete)
	// if (!empty($this->input->get('delete_foto'))) {
	// 	$id_foto = htmlentities($this->input->get('delete_foto'));
	// 	$id_pelatihan = htmlentities($this->input->get('id_pelatihan'));

	// 	$data = $this->db->get_where('tbl_pelatihan_foto', [
	// 		'id_foto' => $id_foto
	// 	])->row();

	// 	if ($data) {
	// 		$this->db->set('deleted_at', date('Y-m-d H:i:s'));
	// 		$this->db->where('id_foto', $id_foto);
	// 		$this->db->update('tbl_pelatihan_foto');

	// 		$this->session->set_flashdata('pesan', '<div class="alert alert-warning">Foto berhasil dihapus!</div>');
	// 		redirect(base_url('data/listkegiatanpelatihan/' . $id_pelatihan));
	// 	} else {
	// 		echo '<script>alert("Data tidak ditemukan."); window.location="' . base_url('data/kegiatanpelatihan') . '"</script>';
	// 	}
	// }

    // === HANDLE DELETE FOTO (HARD DELETE) ===
    if (!empty($this->input->get('delete_foto'))) {

        $id_foto       = (int) $this->input->get('delete_foto', TRUE);
        $id_pelatihan  = (int) $this->input->get('id_pelatihan', TRUE);

        $data = $this->db->get_where('tbl_pelatihan_foto', [
            'id_foto' => $id_foto
        ])->row();

        if ($data) {

            // OPTIONAL: Hapus file fisik jika ada path tersimpan (misalnya kolom 'file_foto')
            if (!empty($data->file_foto)) {
                $file_path = FCPATH . 'uploads/foto_pelatihan/' . $data->file_foto;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // Hard delete dari database
            $this->db->where('id_foto', $id_foto);
            $this->db->delete('tbl_pelatihan_foto');

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-success">Foto berhasil dihapus (Hard Delete)!</div>'
                );

            } else {

                $this->session->set_flashdata('pesan',
                    '<div class="alert alert-danger">Gagal menghapus foto!</div>'
                );
            }

            redirect(base_url('data/listkegiatanpelatihan/' . $id_pelatihan));

        } else {

            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger">Data tidak ditemukan.</div>'
            );

            redirect(base_url('data/kegiatanpelatihan'));
        }
    }



    // // Handle Delete (soft delete)
    // if (!empty($this->input->get('id_activity'))) {
    //     $id_activity = htmlentities($this->input->get('id_activity'));

    //     $data = $this->db->get_where('tbl_pelatihan_activity', [
    //         'id_activity' => $id_activity
    //     ])->row();

    //     if ($data) {
    //         $this->db->set('deleted_at', date('Y-m-d H:i:s'));
    //         $this->db->where('id_activity', $id_activity);
    //         $this->db->update('tbl_pelatihan_activity');

    //         $this->session->set_flashdata('pesan', '<div class="alert alert-warning">Kegiatan berhasil dihapus!</div>');
    //         redirect(base_url('data/listkegiatanpelatihan/' . $data->id_pelatihan));
    //     } else {
    //         echo '<script>alert("Data tidak ditemukan."); window.location="' . base_url('data/kegiatanpelatihan') . '"</script>';
    //     }
    // }

    // === HANDLE DELETE ACTIVITY (HARD DELETE) ===
    if (!empty($this->input->get('id_activity'))) {

        $id_activity = (int) $this->input->get('id_activity', TRUE);

        $data = $this->db->get_where('tbl_pelatihan_activity', [
            'id_activity' => $id_activity
        ])->row();

        if ($data) {

            $this->db->where('id_activity', $id_activity);
            $this->db->delete('tbl_pelatihan_activity');

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-success">Kegiatan berhasil dihapus (Hard Delete)!</div>'
                );

            } else {

                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-danger">Gagal menghapus kegiatan!</div>'
                );
            }

            redirect(base_url('data/listkegiatanpelatihan/' . $data->id_pelatihan));

        } else {

            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-danger">Data tidak ditemukan.</div>'
            );

            redirect(base_url('data/kegiatanpelatihan'));
        }
    }

}

	// Code LDK Pekanbaru Controller Menu Peserta Pelatihan

public function pesertapelatihanjenis()
{
    $this->data['idbo'] = $this->session->userdata('ses_id');
    $panitia_id = $this->session->userdata('id_login');
    $level = $this->session->userdata('level');

    // Ambil filter dari query string: ?jenis=PJJ|PDWK|Latsar
    $jenis = $this->input->get('jenis', TRUE); // bisa null
    $id_jenis = null;

    if ($jenis === 'PJJ') {
        $id_jenis = 1;
        $this->data['title_web'] = 'Data Peserta Pelatihan PJJ';
    } elseif ($jenis === 'PDWK') {
        $id_jenis = 2;
        $this->data['title_web'] = 'Data Peserta Pelatihan PDWK';
    } elseif ($jenis === 'Latsar') {
        $id_jenis = 3;
        $this->data['title_web'] = 'Data Peserta Pelatihan Dasar CPNS';
    } else {
        // Jika tidak ada/invalid → tampilkan semua
        $this->data['title_web'] = 'Data Peserta Pelatihan (Semua Jenis)';
    }

     $this->db->select('p.*, j.nama_jenis_pelatihan');
    $this->db->from('tbl_pelatihan p');
    $this->db->join('tbl_jenis_pelatihan j', 'j.id_jenis_pelatihan = p.id_jenis_pelatihan', 'left');
    
    $this->db->where('p.deleted_at IS NULL', NULL, FALSE);
    if (!is_null($id_jenis)) {
        $this->db->where('p.id_jenis_pelatihan', (int)$id_jenis);
    }
    $this->db->order_by('id_pelatihan', 'DESC');

    if (strtolower($level) === 'panitia') {
        $this->db->join('tbl_panitia_pelatihan pp', 'pp.pelatihan_id = p.id_pelatihan', 'inner');
        $this->db->where('pp.panitia_id', $panitia_id);
    }

    $this->db->order_by('p.tanggal_mulai_pelatihan', 'DESC');
    $this->data['pelatihan'] = $this->db->get();

    // Simpan juga info filter agar view bisa bikin breadcrumb/tab aktif dsb.
    $this->data['jenis_pelatihan'] = $jenis;      // 'PJJ'|'PDWK'|'Latsar'|null
    $this->data['id_jenis_pelatihan'] = $id_jenis; // 1|2|3|null

    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('peserta_pelatihan/list_pelatihan', $this->data);
    $this->load->view('footer_view', $this->data);
}


	public function pesertapelatihan()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
    	$jenis = $this->input->get('jenis');
    	$this->data['pelatihan'] = $this->db->query("SELECT * FROM tbl_pelatihan WHERE deleted_at IS NULL ORDER BY id_pelatihan DESC");
		// Terapkan filter berdasarkan jenis
		if ($jenis == 'PJJ') {
			$this->db->where('id_jenis_pelatihan', 1);
		} elseif ($jenis == 'PDWK') {
			$this->db->where('id_jenis_pelatihan', 2);
		} elseif ($jenis == 'Latsar') {
			$this->db->where('id_jenis_pelatihan', 3);
		}
        $this->data['title_web'] = 'Data Peserta Pelatihan';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('peserta_pelatihan/list_pelatihan',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function listpesertapelatihan($id_pelatihan)
	{
		if ($this->session->userdata('masuk_perpus') != TRUE) {
			$url = base_url('login');
			redirect($url);
		}

		$this->data['idbo'] = $this->session->userdata('ses_id');

		// Cek apakah pelatihan exists
		$cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
			'id_pelatihan' => $id_pelatihan,
			'deleted_at' => NULL
		])->row();

		if (!$cek_pelatihan) {
			echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data') . '"</script>';
			return;
		}

		$this->data['pelatihan'] = $cek_pelatihan;
		
		// Get all peserta for this pelatihan
		$this->data['peserta_pelatihan'] = $this->db->query("
			SELECT * FROM tbl_peserta_pelatihan 
			WHERE id_pelatihan = ?
			AND deleted_at IS NULL
			ORDER BY nama_peserta ASC
		", [$id_pelatihan])->result();

		$this->data['id_pelatihan'] = $id_pelatihan;
		$this->data['title_web'] = 'Data Peserta - ' . htmlentities($cek_pelatihan->nama_pelatihan);
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('peserta_pelatihan/list_peserta_pelatihan', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function prosespesertapelatihan()
{
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        $url = base_url('login');
        redirect($url);
    }

    // === DELETE PESERTA ===
    if (!empty($this->input->get('id_peserta'))) {
        $id_peserta = htmlentities($this->input->get('id_peserta'));
        $id_pelatihan = htmlentities($this->input->get('id_pelatihan'));

        $this->db->set('deleted_at', date('Y-m-d H:i:s'));
        $this->db->where('id_peserta', $id_peserta);
        $this->db->update('tbl_peserta_pelatihan');

        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-warning">
            <p>Berhasil Hapus Data Peserta!</p>
        </div></div>');
        redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
    }

    // === TAMBAH PESERTA (SINGLE) ===
    if (!empty($this->input->post('tambah'))) {
        $post = $this->input->post();
        $id_pelatihan = htmlentities($post['id_pelatihan']);
        $nip = htmlentities($post['nip']);

        // Validasi NIP unik untuk pelatihan ini
        $this->db->select('nama_peserta');
        $this->db->from('tbl_peserta_pelatihan');
        $this->db->where('id_pelatihan', $id_pelatihan);
        $this->db->where('nip', $nip);
        $this->db->where('deleted_at IS NULL', NULL, FALSE); // Syarat utama: belum dihapus
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            // Ambil nama peserta yang sudah terdaftar untuk pesan yang lebih personal
            $existing = $query->row();
            
            $this->session->set_flashdata('pesan', '
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Gagal Menambah!</strong><br>
                    Peserta dengan NIP <b>' . $nip . '</b> ('. $existing->nama_peserta .') sudah terdaftar dalam pelatihan ini.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
            redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
            return; // Hentikan eksekusi
        }

        $data = array(
            'id_pelatihan' => $id_pelatihan,
            'nama_peserta' => htmlentities($post['nama_peserta']),
            'jenis_kelamin' => htmlentities($post['jenis_kelamin']),
            'nip' => htmlentities($post['nip']),
            'pangkatgol' => htmlentities($post['pangkatgol']),
            'jabatan' => htmlentities($post['jabatan']),
            'unit_kerja' => htmlentities($post['unit_kerja']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at'    => NULL
        );

        $this->db->insert('tbl_peserta_pelatihan', $data);

        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
            <p>Tambah Data Peserta Berhasil!</p>
        </div></div>');
        redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
    }

    // === EDIT PESERTA ===
    if (!empty($this->input->post('edit'))) {
        $post = $this->input->post();
        $id_peserta = htmlentities($post['edit']);
        $id_pelatihan = htmlentities($post['id_pelatihan']);

        // Validasi NIP unik (kecuali untuk data yang sedang diedit)
        $cek_nip = $this->db->get_where('tbl_peserta_pelatihan', [
            'id_pelatihan' => $id_pelatihan,
            'nip' => htmlentities($post['nip']),
            'id_peserta !=' => $id_peserta,
            'deleted_at' => NULL
        ])->row();

        if ($cek_nip) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
                <p>NIP ' . htmlentities($post['nip']) . ' sudah terdaftar pada pelatihan ini!</p>
            </div>');
            redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
        }

        $data = array(
            'nama_peserta' => htmlentities($post['nama_peserta']),
            'jenis_kelamin' => htmlentities($post['jenis_kelamin']),
            'nip' => htmlentities($post['nip']),
            'pangkatgol' => htmlentities($post['pangkatgol']),
            'jabatan' => htmlentities($post['jabatan']),
            'unit_kerja' => htmlentities($post['unit_kerja']),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->db->where('id_peserta', $id_peserta);
        $this->db->update('tbl_peserta_pelatihan', $data);

        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
            <p>Edit Data Peserta Berhasil!</p>
        </div></div>');
        redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
    }

//   
    // === IMPORT EXCEL BATCH ===
if (!empty($this->input->post('import_excel'))) {
    $id_pelatihan = htmlentities($this->input->post('id_pelatihan'));

    $config['upload_path']   = './assets/excel/';
    $config['allowed_types'] = 'xlsx|xls|csv';
    $config['max_size']      = 4096;
    $config['encrypt_name']  = TRUE;

    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file_excel')) {
        $error = $this->upload->display_errors();
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
            <p>Error upload file: ' . $error . '</p>
        </div>');
        redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
    }

$upload_data = $this->upload->data();
$file_path   = $upload_data['full_path'];

// Convert Excel → CSV using your helper
$this->load->helper('excel');  // make sure helper loaded
$csv_path = convert_excel_to_csv($file_path);

if ($csv_path === false || !file_exists($csv_path)) {
    @unlink($file_path);
    $this->session->set_flashdata('pesan',
        '<div class="alert alert-danger"><p>Gagal konversi Excel ke CSV.</p></div>');
    redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
}


    // Baca CSV
    $success_count  = 0;
    $error_count    = 0;
    $error_messages = [];

    // Deteksi delimiter dari header (koma vs titik koma)
    $fp = fopen($csv_path, 'r');
    if ($fp === false) {
        @unlink($file_path);
        @unlink($csv_path);
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
            <p>Tidak bisa membuka CSV hasil konversi.</p>
        </div>');
        redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
    }

    // Baca baris pertama untuk deteksi delimiter & buang BOM jika ada
    $firstLine = fgets($fp);
    if ($firstLine === false) { $firstLine = ''; }
    // Hilangkan BOM UTF-8
    $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);

    // Hitung kandidat delimiter
    $commaCount = substr_count($firstLine, ',');
    $semiCount  = substr_count($firstLine, ';');
    $delim      = ($semiCount > $commaCount) ? ';' : ',';

    // Kembalikan pointer ke awal file
    rewind($fp);

    // Gunakan fgetcsv dengan delimiter terdeteksi
    $row_number = 0;
    while (($row = fgetcsv($fp, 0, $delim)) !== false) {
        $row_number++;

        // Skip header (baris 1)
        if ($row_number == 1) {
            // buang BOM di kolom pertama jika masih ada
            if (!empty($row[0])) {
                $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]);
            }
            continue;
        }

        // Skip baris kosong (kolom 0 kosong)
        if (!isset($row[0]) || trim($row[0]) === '') {
            continue;
        }

        // Mapping kolom: [0]=nama_peserta, [1]=jenis_kelamin, [2]=nip, [3]=pangkatgol, [4]=jabatan, [5]=unit_kerja
        $nama_peserta  = isset($row[0]) ? trim($row[0]) : '';
        $jenis_kelamin = isset($row[1]) ? trim($row[1]) : '';
        $nip_raw       = isset($row[2]) ? trim($row[2]) : '';
        $pangkatgol    = isset($row[3]) ? trim($row[3]) : '';
        $jabatan       = isset($row[4]) ? trim($row[4]) : '';
        $unit_kerja    = isset($row[5]) ? trim($row[5]) : '';

        // Normalisasi NIP (ambil bagian sebelum "/" bila format "NIP/NIK")
        $nip = $this->extract_nip_before_slash($nip_raw);

        // Validasi wajib nama
        if ($nama_peserta === '') {
            $error_count++;
            $error_messages[] = "Baris $row_number: Nama peserta wajib diisi";
            continue;
        }

        // Jenis kelamin normalisasi: default 'L'
        $jk = 'L';
        if ($jenis_kelamin !== '') {
            $jk_lower = strtolower($jenis_kelamin);
            if (strpos($jk_lower, 'perempuan') !== false || $jk_lower === 'p') {
                $jk = 'P';
            } elseif (strpos($jk_lower, 'laki') !== false || $jk_lower === 'l' || $jk_lower === 'laki-laki') {
                $jk = 'L';
            }
        }

        // Jika NIP kosong, generate sementara
        if ($nip === '') {
            $nip = 'TEMP_' . preg_replace('/[^a-zA-Z0-9]/', '', $nama_peserta) . '_' . time();
        }

        // Validasi unik NIP untuk pelatihan ini (abaikan TEMP_)
        if (strpos($nip, 'TEMP_') !== 0) {
            $cek_nip = $this->db->get_where('tbl_peserta_pelatihan', [
                'id_pelatihan' => $id_pelatihan,
                'nip'          => $nip,
                'deleted_at'   => NULL
            ])->row();

            if ($cek_nip) {
                $error_count++;
                $error_messages[] = "Baris $row_number: NIP $nip sudah terdaftar dalam pelatihan ini";
                continue;
            }
        }

        // Bersihkan jabatan
        $jabatan = $this->bersihkan_jabatan($jabatan);

        $data_insert = [
            'id_pelatihan'  => $id_pelatihan,
            'nama_peserta'  => $nama_peserta,
            'jenis_kelamin' => $jk,
            'nip'           => $nip,
            'pangkatgol'    => $pangkatgol,
            'jabatan'       => $jabatan,
            'unit_kerja'    => $unit_kerja,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        if ($this->db->insert('tbl_peserta_pelatihan', $data_insert)) {
            $success_count++;
        } else {
            $error_count++;
            $db_error = $this->db->error();
            $error_messages[] = "Baris $row_number: Gagal simpan - " . $db_error['message'];
        }
    }

    fclose($fp);

    // Bersih-bersih file
    @unlink($file_path);
    // Jika CSV hasil konversi berbeda file, hapus juga
    if ($csv_path !== $file_path && file_exists($csv_path)) {
        @unlink($csv_path);
    }

    // Flash message ringkas
    $message = "<div class='alert alert-success'>
        <p>Import selesai! Berhasil: $success_count data, Gagal: $error_count data</p>";

    if (!empty($error_messages)) {
        $message .= "<br>Detail error:<br>" . implode('<br>', array_slice($error_messages, 0, 10));
        if (count($error_messages) > 10) {
            $message .= "<br>... dan " . (count($error_messages) - 10) . " error lainnya";
        }
    }
    $message .= "</div>";

    $this->session->set_flashdata('pesan', $message);
    redirect(base_url('data/listpesertapelatihan/' . $id_pelatihan));
}

}

// Function untuk membersihkan dan standardisasi jabatan
private function bersihkan_jabatan($jabatan) {
    if (empty($jabatan)) return $jabatan;
    
    $jabatan = trim($jabatan);
    
    // Perbaiki typo umum
    $jabatan = str_replace('Penghulu Ahli Pertamaa', 'Penghulu Ahli Pertama', $jabatan);
    $jabatan = str_replace('Ahli Pertamaa', 'Ahli Pertama', $jabatan);
    
    // Standardisasi pemisah
    $jabatan = str_replace(' - ', ' - ', $jabatan); // pastikan konsisten
    $jabatan = str_replace('-', ' - ', $jabatan); // ubah single dash ke format standar
    $jabatan = str_replace('/', ' / ', $jabatan); // beri spasi sekitar slash
    
    // Standardisasi penulisan "Ahli Pertama"
    $jabatan = preg_replace('/Ahli\s+Pertama/i', 'Ahli Pertama', $jabatan);
    
    return $jabatan;
}

	// Code LDK Pekanbaru Materi (Latsar)

public function materi()
{
    // Wajib login (opsional jika diperlukan di halaman ini)
    $this->data['idbo'] = $this->session->userdata('ses_id');

    // a) Ambil daftar agenda beserta ringkasan total JP dan jumlah topik
    $sqlAgenda = "
        SELECT 
            a.agenda_id,
            a.agenda_title,
            COALESCE(COUNT(t.topic_id), 0) AS total_topics,
            COALESCE(SUM(t.jp_sync), 0)   AS total_jp_sync,
            COALESCE(SUM(t.jp_async), 0)  AS total_jp_async
        FROM tbl_agenda a
        LEFT JOIN tbl_topik t ON t.agenda_id = a.agenda_id
        GROUP BY a.agenda_id, a.agenda_title
        ORDER BY a.agenda_id ASC
    ";
    $this->data['agenda'] = $this->db->query($sqlAgenda); // result object (untuk foreach di view)

    // b) Ambil semua topik dan kelompokkan per agenda_id untuk kemudahan render nested list/table
    $sqlTopik = "
        SELECT 
            t.topic_id, t.agenda_id, t.topic_no, t.topic_title, t.jp_sync, t.jp_async
        FROM tbl_topik t
        ORDER BY t.agenda_id ASC, t.topic_no ASC
    ";
    $allTopics = $this->db->query($sqlTopik)->result_array();

    $topics_by_agenda = [];
    foreach ($allTopics as $row) {
        $topics_by_agenda[$row['agenda_id']][] = $row;
    }
    $this->data['topics_by_agenda'] = $topics_by_agenda;

    // (opsional) peta agenda_id => agenda_title bila view butuh
    $agenda_title_map = [];
    foreach ($this->data['agenda']->result_array() as $ag) {
        $agenda_title_map[(int)$ag['agenda_id']] = $ag['agenda_title'];
    }
    $this->data['agenda_title_map'] = $agenda_title_map;

    // judul & render
    $this->data['title_web'] = 'Data Materi (Agenda & Topik)';
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('materi_pengajar/list_materi', $this->data); // gunakan pola tampilan dokumenview
    $this->load->view('footer_view', $this->data);
}

public function prosesmateri()
{
    // Wajib login
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        redirect(base_url('login'));
    }

    // Helper redirect + flash
    $redir = function($flash = null) {
        if (!empty($flash)) {
            $this->session->set_flashdata('pesan', $flash);
        }
        redirect(base_url('data/materi'));
        exit;
    };

    // =============== CRUD AGENDA ===============

    // Tambah Agenda
    if (!empty($this->input->post('tambah_agenda'))) {
        $post = $this->input->post();
        $agenda_title = trim($post['agenda_title'] ?? '');

        if ($agenda_title === '') {
            return $redir('<div class="alert alert-danger">Judul Agenda wajib diisi.</div>');
        }

        $data = [
            'agenda_title' => htmlentities($agenda_title, ENT_QUOTES, 'UTF-8')
        ];

        $this->db->insert('tbl_agenda', $data);
        return $redir('<div class="alert alert-success">Agenda berhasil ditambahkan!</div>');
    }

    // Edit Agenda
    if (!empty($this->input->post('edit_agenda'))) {
        $post      = $this->input->post();
        $agenda_id = (int)$post['edit_agenda'];
        $title     = trim($post['agenda_title'] ?? '');

        if (!$agenda_id) {
            return $redir('<div class="alert alert-danger">Parameter Agenda tidak lengkap.</div>');
        }
        $agenda = $this->db->get_where('tbl_agenda', ['agenda_id' => $agenda_id])->row();
        if (!$agenda) {
            return $redir('<div class="alert alert-danger">Agenda tidak ditemukan.</div>');
        }
        if ($title === '') {
            return $redir('<div class="alert alert-danger">Judul Agenda wajib diisi.</div>');
        }

        $data_update = [
            'agenda_title' => htmlentities($title, ENT_QUOTES, 'UTF-8')
        ];
        $this->db->where('agenda_id', $agenda_id)->update('tbl_agenda', $data_update);

        return $redir('<div class="alert alert-success">Agenda berhasil diperbarui!</div>');
    }

    // Hapus Agenda (beserta semua topiknya)
    if (!empty($this->input->get('delete_agenda'))) {
        $agenda_id = (int)$this->input->get('delete_agenda');

        $agenda = $this->db->get_where('tbl_agenda', ['agenda_id' => $agenda_id])->row();
        if (!$agenda) {
            return $redir('<div class="alert alert-danger">Agenda tidak ditemukan.</div>');
        }

        $this->db->trans_begin();
        // jika tidak ada FK ON DELETE CASCADE, hapus manual topik
        $this->db->delete('tbl_topik', ['agenda_id' => $agenda_id]);
        $this->db->delete('tbl_agenda', ['agenda_id' => $agenda_id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $redir('<div class="alert alert-danger">Terjadi kesalahan saat menghapus agenda.</div>');
        } else {
            $this->db->trans_commit();
            return $redir('<div class="alert alert-warning">Agenda (beserta topik) berhasil dihapus!</div>');
        }
    }

    // =============== CRUD TOPIK ===============

    // Tambah Topik
    if (!empty($this->input->post('tambah_topik'))) {
        $post       = $this->input->post();
        $agenda_id  = (int)($post['agenda_id'] ?? 0);
        $topic_no   = (int)($post['topic_no'] ?? 0);
        $topic_title= trim($post['topic_title'] ?? '');
        $jp_async   = isset($post['jp_async']) ? (int)$post['jp_async'] : 0;
        $jp_sync    = isset($post['jp_sync'])  ? (int)$post['jp_sync']  : 0;

        if (!$agenda_id || !$topic_no || $topic_title === '') {
            // open kembali modal topik di view
            $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
            return $redir('<div class="alert alert-danger">Agenda, Topic No, dan Judul Topik wajib diisi.</div>');
        }

        // Validasi agenda ada
        $agenda = $this->db->get_where('tbl_agenda', ['agenda_id' => $agenda_id])->row();
        if (!$agenda) {
            $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
            return $redir('<div class="alert alert-danger">Agenda tidak valid.</div>');
        }

        // Cegah duplikasi topic_no dalam satu agenda
        $dup = $this->db->get_where('tbl_topik', [
            'agenda_id' => $agenda_id,
            'topic_no'  => $topic_no
        ])->row();
        if ($dup) {
            $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
            return $redir('<div class="alert alert-danger">Topic No sudah digunakan pada agenda ini.</div>');
        }

        $data = [
            'agenda_id'   => $agenda_id,
            'topic_no'    => $topic_no,
            'topic_title' => htmlentities($topic_title, ENT_QUOTES, 'UTF-8'),
            'jp_async'    => $jp_async,
            'jp_sync'     => $jp_sync
        ];
        $this->db->insert('tbl_topik', $data);

        $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
        return $redir('<div class="alert alert-success">Topik berhasil ditambahkan!</div>');
    }

    // Edit Topik
    if (!empty($this->input->post('edit_topik'))) {
        $post        = $this->input->post();
        $topic_id    = (int)($post['edit_topik'] ?? 0);
        $agenda_id   = (int)($post['agenda_id'] ?? 0);
        $topic_no    = (int)($post['topic_no'] ?? 0);
        $topic_title = trim($post['topic_title'] ?? '');
        $jp_async    = isset($post['jp_async']) ? (int)$post['jp_async'] : 0;
        $jp_sync     = isset($post['jp_sync'])  ? (int)$post['jp_sync']  : 0;

        if (!$topic_id || !$agenda_id || !$topic_no || $topic_title === '') {
            $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
            return $redir('<div class="alert alert-danger">Parameter topik tidak lengkap.</div>');
        }

        $topic = $this->db->get_where('tbl_topik', ['topic_id' => $topic_id, 'agenda_id' => $agenda_id])->row();
        if (!$topic) {
            $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
            return $redir('<div class="alert alert-danger">Topik tidak ditemukan.</div>');
        }

        // Pastikan tidak bentrok topic_no lain dalam agenda yang sama
        $dup = $this->db->where('agenda_id', $agenda_id)
                        ->where('topic_no', $topic_no)
                        ->where('topic_id !=', $topic_id)
                        ->get('tbl_topik')->row();
        if ($dup) {
            $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
            return $redir('<div class="alert alert-danger">Topic No sudah digunakan pada agenda ini.</div>');
        }

        $data_update = [
            'topic_no'    => $topic_no,
            'topic_title' => htmlentities($topic_title, ENT_QUOTES, 'UTF-8'),
            'jp_async'    => $jp_async,
            'jp_sync'     => $jp_sync
        ];
        $this->db->where('topic_id', $topic_id)->update('tbl_topik', $data_update);

        $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
        return $redir('<div class="alert alert-success">Topik berhasil diperbarui!</div>');
    }

    // Hapus Topik
    if (!empty($this->input->get('delete_topik'))) {
        $topic_id = (int)$this->input->get('delete_topik');

        $topic = $this->db->get_where('tbl_topik', ['topic_id' => $topic_id])->row();
        if (!$topic) {
            return $redir('<div class="alert alert-danger">Topik tidak ditemukan.</div>');
        }

        $this->db->delete('tbl_topik', ['topic_id' => $topic_id]);

        // Re-open modal topik pada agenda bersangkutan (opsional)
        $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => (int)$topic->agenda_id]);
        return $redir('<div class="alert alert-warning">Topik berhasil dihapus!</div>');
    }

    // Default: jika tidak ada aksi cocok, kembali ke list
    return $redir();
}


// 	public function listmateripengajar($id_pelatihan)
// {
//     // Pastikan user sudah login
//     if ($this->session->userdata('masuk_perpus') != TRUE) {
//         redirect(base_url('login'));
//     }

//     // Sanitasi dasar
//     $id_pelatihan = (int) $id_pelatihan;

//     // Cek pelatihan eksis dan belum di-soft delete
//     $cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
//         'id_pelatihan' => $id_pelatihan,
//         'deleted_at'   => NULL
//     ])->row();

//     if (!$cek_pelatihan) {
//         echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/kegiatanpelatihan') . '"</script>';
//         return;
//     }

//     $this->data['idbo']       = $this->session->userdata('ses_id');
//     $this->data['pelatihan']  = $cek_pelatihan;
//     $this->data['id_pelatihan']= $id_pelatihan;

//     // ===== Daftar Materi & Pengajar (Agenda) =====
// 	// Catatan: pakai ag.id_pelatihan (BUKAN ag.agenda_id) untuk mem-filter per pelatihan.
// 	$this->data['materi_pengajar'] = $this->db->query("
// 		SELECT 
// 			ag.agenda_id,
// 			ag.agenda_title,
// 			ag.main_teacher_id,
// 			pg.nama AS main_teacher_name,

// 			COUNT(DISTINCT tp.topic_id) AS jumlah_topik,
// 			COALESCE(SUM(tp.jp_async + tp.jp_sync), 0) AS total_jp,
// 			COUNT(DISTINCT ga.teacher_id) AS jumlah_pengajar_kelompok

// 		FROM tbl_agenda ag
// 		LEFT JOIN tbl_topik tp 
// 			ON tp.agenda_id = ag.agenda_id
// 			-- Jika tbl_topik ada soft delete, aktifkan baris di bawah:
// 			-- AND tp.deleted_at IS NULL
// 		LEFT JOIN tbl_grup_agenda ga
// 			ON ga.agenda_id = ag.agenda_id
// 			-- Jika tbl_grup_agenda ada soft delete, aktifkan baris di bawah:
// 			-- AND ga.deleted_at IS NULL
// 		LEFT JOIN tbl_pegawai pg 
// 			ON pg.id_pegawai = ag.main_teacher_id 
// 			AND (pg.deleted_at IS NULL OR pg.deleted_at IS NULL) -- aman jika kolomnya ada

// 		WHERE ag.id_pelatihan = ?
// 		-- Jika tbl_agenda ada soft delete, aktifkan baris di bawah:
// 		-- AND ag.deleted_at IS NULL

// 		GROUP BY 
// 			ag.agenda_id, ag.agenda_title, ag.main_teacher_id, pg.nama
// 		ORDER BY ag.agenda_id ASC
// 	", [$id_pelatihan])->result();


//     // (Opsional) Daftar pegawai untuk assignment pengajar di UI
//     $this->data['pegawai'] = $this->db
//         ->where('deleted_at', NULL)
//         ->order_by('nama', 'ASC')
//         ->get('tbl_pegawai')
//         ->result();

//     // Title halaman
// 	$judul = !empty($cek_pelatihan->nama_pelatihan) ? $cek_pelatihan->nama_pelatihan
//        : (!empty($cek_pelatihan->nama_kegiatan) ? $cek_pelatihan->nama_kegiatan : 'Pelatihan');
// 	$this->data['title_web'] = 'Materi & Pengajar - ' . htmlentities($judul);


//     // Render view
//     $this->load->view('header_view',  $this->data);
//     $this->load->view('sidebar_view', $this->data);
//     $this->load->view('materi_pengajar/list_materi_pengajar', $this->data);
//     $this->load->view('footer_view',  $this->data);
// }

// public function prosesmateripengajar()
// {
//     // Wajib login
//     if ($this->session->userdata('masuk_perpus') != TRUE) {
//         redirect(base_url('login'));
//     }

//     // Helper kecil untuk redirect aman
//     $redir = function($id_pelatihan, $flash = null) {
//         if (!empty($flash)) {
//             $this->session->set_flashdata('pesan', $flash);
//         }
//         redirect(base_url('data/listmateripengajar/' . (int)$id_pelatihan));
//         exit;
//     };

//     // =========================
//     // Tambah Agenda
//     // =========================
//     if (!empty($this->input->post('tambah_agenda'))) {
//         $post = $this->input->post();

//         if (empty($post['id_pelatihan']) || empty($post['agenda_title'])) {
//             return $redir($post['id_pelatihan'] ?? 0, '<div class="alert alert-danger">ID Pelatihan dan Judul Agenda wajib diisi.</div>');
//         }

//         $cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
//             'id_pelatihan' => (int)$post['id_pelatihan'],
//             'deleted_at'   => NULL
//         ])->row();

//         if (!$cek_pelatihan) {
//             $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data pelatihan tidak ditemukan.</div>');
//             redirect(base_url('data/kegiatanpelatihan'));
//             return;
//         }

//         $data = [
//             'id_pelatihan'    => (int)$post['id_pelatihan'],
//             'agenda_title'    => htmlentities(trim($post['agenda_title'])),
//             'main_teacher_id' => !empty($post['main_teacher_id']) ? (int)$post['main_teacher_id'] : NULL,
//         ];

//         $this->db->insert('tbl_agenda', $data);
//         return $redir($post['id_pelatihan'], '<div class="alert alert-success">Agenda berhasil ditambahkan!</div>');
//     }

//     // =========================
//     // Edit Agenda
//     // =========================
//     if (!empty($this->input->post('edit_agenda'))) {
//         $post = $this->input->post();

//         $agenda_id    = (int)$post['edit_agenda'];
//         $id_pelatihan = (int)$post['id_pelatihan'];

//         if (!$agenda_id || !$id_pelatihan) {
//             $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Parameter tidak lengkap.</div>');
//             redirect(base_url('data/kegiatanpelatihan'));
//             return;
//         }

//         $cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
//             'id_pelatihan' => $id_pelatihan,
//             'deleted_at'   => NULL
//         ])->row();
//         if (!$cek_pelatihan) {
//             $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data pelatihan tidak ditemukan.</div>');
//             redirect(base_url('data/kegiatanpelatihan'));
//             return;
//         }

//         $agenda = $this->db->get_where('tbl_agenda', [
//             'agenda_id'    => $agenda_id,
//             'id_pelatihan' => $id_pelatihan
//         ])->row();
//         if (!$agenda) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda tidak ditemukan.</div>');
//         }

//         if (empty($post['agenda_title'])) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Judul Agenda wajib diisi.</div>');
//         }

//         $data_update = [
//             'agenda_title'    => htmlentities(trim($post['agenda_title'])),
//             'main_teacher_id' => !empty($post['main_teacher_id']) ? (int)$post['main_teacher_id'] : NULL,
//         ];

//         $this->db->where('agenda_id', $agenda_id);
//         $this->db->update('tbl_agenda', $data_update);

//         return $redir($id_pelatihan, '<div class="alert alert-success">Agenda berhasil diperbarui!</div>');
//     }

//     // =========================
//     // Hapus Agenda (beserta turunan)
//     // =========================
//     if (!empty($this->input->get('delete_agenda'))) {
//         $agenda_id    = (int)$this->input->get('delete_agenda');
//         $id_pelatihan = (int)$this->input->get('id_pelatihan');

//         $agenda = $this->db->get_where('tbl_agenda', ['agenda_id' => $agenda_id])->row();
//         if (!$agenda) {
//             $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Agenda tidak ditemukan.</div>');
//             redirect(base_url('data/kegiatanpelatihan'));
//             return;
//         }
//         if (!$id_pelatihan) $id_pelatihan = (int)$agenda->id_pelatihan;

//         $this->db->trans_begin();
//         $this->db->delete('tbl_topik',       ['agenda_id' => $agenda_id]);
//         $this->db->delete('tbl_grup_agenda', ['agenda_id' => $agenda_id]);
//         $this->db->delete('tbl_agenda',      ['agenda_id' => $agenda_id]);

//         if ($this->db->trans_status() === FALSE) {
//             $this->db->trans_rollback();
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Terjadi kesalahan saat menghapus agenda.</div>');
//         } else {
//             $this->db->trans_commit();
//             return $redir($id_pelatihan, '<div class="alert alert-warning">Agenda berhasil dihapus!</div>');
//         }
//     }

//     // ======================================================================
//     // ========================  CRUD TOPIK  =================================
//     // ======================================================================

//     // Tambah Topik
//     if (!empty($this->input->post('tambah_topik'))) {
//         $post        = $this->input->post();
//         $agenda_id   = (int)$post['agenda_id'];
//         $id_pelatihan= (int)$post['id_pelatihan'];

//         if (!$agenda_id || !$id_pelatihan || empty($post['topic_no']) || empty($post['topic_title'])) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda, Topic No, dan Judul Topik wajib diisi.</div>');
//         }

//         // Validasi agenda milik pelatihan
//         $agenda = $this->db->get_where('tbl_agenda', [
//             'agenda_id'    => $agenda_id,
//             'id_pelatihan' => $id_pelatihan
//         ])->row();
//         if (!$agenda) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda tidak valid.</div>');
//         }

//         $data = [
//             'agenda_id'   => $agenda_id,
//             'topic_no'    => (int)$post['topic_no'],
//             'topic_title' => htmlentities(trim($post['topic_title'])),
//             'jp_async'    => isset($post['jp_async']) ? (int)$post['jp_async'] : 0,
//             'jp_sync'     => isset($post['jp_sync'])  ? (int)$post['jp_sync']  : 0,
//         ];
//         $this->db->insert('tbl_topik', $data);

//         // opsional: buka kembali modal topik
//         $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);

//         return $redir($id_pelatihan, '<div class="alert alert-success">Topik berhasil ditambahkan!</div>');
//     }

//     // Edit Topik
//     if (!empty($this->input->post('edit_topik'))) {
//         $post         = $this->input->post();
//         $topic_id     = (int)$post['edit_topik'];
//         $agenda_id    = (int)$post['agenda_id'];
//         $id_pelatihan = (int)$post['id_pelatihan'];

//         if (!$topic_id || !$agenda_id || !$id_pelatihan || empty($post['topic_no']) || empty($post['topic_title'])) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Parameter topik tidak lengkap.</div>');
//         }

//         // Validasi kepemilikan topik -> agenda -> pelatihan
//         $topic = $this->db->get_where('tbl_topik', ['topic_id' => $topic_id, 'agenda_id' => $agenda_id])->row();
//         if (!$topic) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Topik tidak ditemukan.</div>');
//         }

//         $agenda = $this->db->get_where('tbl_agenda', ['agenda_id' => $agenda_id, 'id_pelatihan' => $id_pelatihan])->row();
//         if (!$agenda) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda tidak valid.</div>');
//         }

//         $data_update = [
//             'topic_no'    => (int)$post['topic_no'],
//             'topic_title' => htmlentities(trim($post['topic_title'])),
//             'jp_async'    => isset($post['jp_async']) ? (int)$post['jp_async'] : 0,
//             'jp_sync'     => isset($post['jp_sync'])  ? (int)$post['jp_sync']  : 0,
//         ];
//         $this->db->where('topic_id', $topic_id)->update('tbl_topik', $data_update);

//         $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => $agenda_id]);
//         return $redir($id_pelatihan, '<div class="alert alert-success">Topik berhasil diperbarui!</div>');
//     }

//     // Hapus Topik
//     if (!empty($this->input->get('delete_topik'))) {
//         $topic_id     = (int)$this->input->get('delete_topik');
//         $agenda_id    = (int)$this->input->get('agenda_id');
//         $id_pelatihan = (int)$this->input->get('id_pelatihan');

//         $topic = $this->db->get_where('tbl_topik', ['topic_id' => $topic_id])->row();
//         if (!$topic) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Topik tidak ditemukan.</div>');
//         }

//         // Pastikan topik sesuai agenda & pelatihan
//         $agenda = $this->db->get_where('tbl_agenda', ['agenda_id' => $topic->agenda_id])->row();
//         if (!$agenda || ($agenda_id && $agenda->agenda_id != $agenda_id)) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda topik tidak valid.</div>');
//         }

//         $this->db->delete('tbl_topik', ['topic_id' => $topic_id]);

//         $this->session->set_flashdata('open_modal', ['type' => 'topik', 'agenda_id' => (int)$agenda->agenda_id]);
//         return $redir(($id_pelatihan ?: (int)$agenda->id_pelatihan), '<div class="alert alert-warning">Topik berhasil dihapus!</div>');
//     }

//     // ======================================================================
//     // ====================  CRUD GRUP PENGAJAR  =============================
//     // ======================================================================

//     // Tambah Grup
//     if (!empty($this->input->post('tambah_grup'))) {
//         $post         = $this->input->post();
//         $agenda_id    = (int)$post['agenda_id'];
//         $id_pelatihan = (int)$post['id_pelatihan'];

//         if (!$agenda_id || !$id_pelatihan || empty($post['group_no']) || empty($post['teacher_id'])) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda, Nomor Grup, dan Teacher wajib diisi.</div>');
//         }

//         // Validasi agenda milik pelatihan
//         $agenda = $this->db->get_where('tbl_agenda', [
//             'agenda_id'    => $agenda_id,
//             'id_pelatihan' => $id_pelatihan
//         ])->row();
//         if (!$agenda) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda tidak valid.</div>');
//         }

//         $data = [
//             'agenda_id' => $agenda_id,
//             'group_no'  => (int)$post['group_no'],
//             'teacher_id'=> (int)$post['teacher_id'],
//         ];
//         $this->db->insert('tbl_grup_agenda', $data);

//         $this->session->set_flashdata('open_modal', ['type' => 'grup', 'agenda_id' => $agenda_id]);
//         return $redir($id_pelatihan, '<div class="alert alert-success">Grup pengajar berhasil ditambahkan!</div>');
//     }

//     // Edit Grup
//     if (!empty($this->input->post('edit_grup'))) {
//         $post            = $this->input->post();
//         $agenda_group_id = (int)$post['edit_grup'];
//         $agenda_id       = (int)$post['agenda_id'];
//         $id_pelatihan    = (int)$post['id_pelatihan'];

//         if (!$agenda_group_id || !$agenda_id || !$id_pelatihan || empty($post['group_no']) || empty($post['teacher_id'])) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Parameter grup tidak lengkap.</div>');
//         }

//         $gr = $this->db->get_where('tbl_grup_agenda', ['agenda_group_id' => $agenda_group_id, 'agenda_id' => $agenda_id])->row();
//         if (!$gr) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Data grup tidak ditemukan.</div>');
//         }

//         $agenda = $this->db->get_where('tbl_agenda', ['agenda_id' => $agenda_id, 'id_pelatihan' => $id_pelatihan])->row();
//         if (!$agenda) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda tidak valid.</div>');
//         }

//         $data_update = [
//             'group_no'  => (int)$post['group_no'],
//             'teacher_id'=> (int)$post['teacher_id'],
//         ];
//         $this->db->where('agenda_group_id', $agenda_group_id)->update('tbl_grup_agenda', $data_update);

//         $this->session->set_flashdata('open_modal', ['type' => 'grup', 'agenda_id' => $agenda_id]);
//         return $redir($id_pelatihan, '<div class="alert alert-success">Grup pengajar berhasil diperbarui!</div>');
//     }

//     // Hapus Grup
//     if (!empty($this->input->get('delete_grup_agenda'))) {
//         $agenda_group_id = (int)$this->input->get('delete_grup_agenda');
//         $agenda_id       = (int)$this->input->get('agenda_id');
//         $id_pelatihan    = (int)$this->input->get('id_pelatihan');

//         $gr = $this->db->get_where('tbl_grup_agenda', ['agenda_group_id' => $agenda_group_id])->row();
//         if (!$gr) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Data grup tidak ditemukan.</div>');
//         }

//         // validasi agenda
//         if ($agenda_id && $gr->agenda_id != $agenda_id) {
//             return $redir($id_pelatihan, '<div class="alert alert-danger">Agenda grup tidak valid.</div>');
//         }

//         $this->db->delete('tbl_grup_agenda', ['agenda_group_id' => $agenda_group_id]);

//         $this->session->set_flashdata('open_modal', ['type' => 'grup', 'agenda_id' => (int)$gr->agenda_id]);
//         return $redir(($id_pelatihan ?: (int)$this->db->get_where('tbl_agenda', ['agenda_id' => $gr->agenda_id])->row()->id_pelatihan), '<div class="alert alert-warning">Grup pengajar berhasil dihapus!</div>');
//     }
// }

	// Code LDK Pekanbaru Materi dan Pengajar (Latsar)

	public function pengajar()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
        $panitia_id = $this->session->userdata('id_login');
        $level = $this->session->userdata('level');

        $this->data['title_web'] = 'Data Materi dan Pengajar Pelatihan Dasar CPNS';

        $this->db->select('p.*, j.nama_jenis_pelatihan');
        $this->db->from('tbl_pelatihan p');
        $this->db->join('tbl_jenis_pelatihan j', 'j.id_jenis_pelatihan = p.id_jenis_pelatihan', 'left');

        $this->db->where('p.id_jenis_pelatihan', 3);
        $this->db->where('p.deleted_at IS NULL', NULL, FALSE);

        if (strtolower($level) === 'panitia') {
            // Panitia hanya boleh melihat pelatihan yang dia ikuti
            $this->db->join('tbl_panitia_pelatihan pp', 'pp.pelatihan_id = p.id_pelatihan', 'inner');
            $this->db->where('pp.panitia_id', $panitia_id);
        }

        $this->db->order_by('p.id_pelatihan', 'DESC');
        $this->data['pelatihan'] = $this->db->get();

        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('materi_pengajar/list_pelatihan',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function listpengajar($id_pelatihan = null)
{
    // Wajib login
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        redirect(base_url('login'));
    }

    // Ambil id_pelatihan dari parameter / URI seg3 bila null
    if (!$id_pelatihan) {
        $id_pelatihan = (int) $this->uri->segment(3);
    }
    $id_pelatihan = (int)$id_pelatihan;

    // Validasi pelatihan
    $pel = $this->db->get_where('tbl_pelatihan', [
        'id_pelatihan' => $id_pelatihan,
        'deleted_at'   => NULL
    ])->row_array();

    if (!$pel) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data pelatihan tidak ditemukan.</div>');
        redirect(base_url('data/pengajar')); // kembali ke daftar pelatihan
        return;
    }

    $this->data['idbo'] = $this->session->userdata('ses_id');
    $this->data['pelatihan'] = $pel;

    // a) Master agenda + ringkasan JP dan jumlah topik
    $sqlAgenda = "
        SELECT 
            a.agenda_id,
            a.agenda_title,
            COALESCE(COUNT(t.topic_id), 0) AS total_topics,
            COALESCE(SUM(t.jp_sync), 0)   AS total_jp_sync,
            COALESCE(SUM(t.jp_async), 0)  AS total_jp_async
        FROM tbl_agenda a
        LEFT JOIN tbl_topik t ON t.agenda_id = a.agenda_id
        GROUP BY a.agenda_id, a.agenda_title
        ORDER BY a.agenda_id ASC
    ";
    $this->data['agenda'] = $this->db->query($sqlAgenda); // result()

    // b) Semua topik, dikelompokkan per agenda
    $sqlTopik = "
        SELECT topic_id, agenda_id, topic_no, topic_title, jp_sync, jp_async
        FROM tbl_topik
        ORDER BY agenda_id ASC, topic_no ASC
    ";
    $topics = $this->db->query($sqlTopik)->result_array();
    $topics_by_agenda = [];
    foreach ($topics as $tp) {
        $topics_by_agenda[(int)$tp['agenda_id']][] = $tp;
    }
    $this->data['topics_by_agenda'] = $topics_by_agenda;

    // c) Pemetaan pelatihan-agenda (status main teacher per agenda untuk pelatihan ini)
    $sqlPA = "
        SELECT pa.pelatihan_agenda_id, pa.agenda_id, pa.main_teacher_id,
               peg.nama AS main_teacher_name
        FROM tbl_pelatihan_agenda pa
        LEFT JOIN tbl_pegawai peg ON peg.id_pegawai = pa.main_teacher_id
        WHERE pa.id_pelatihan = ?
    ";
    $pa_rows = $this->db->query($sqlPA, [$id_pelatihan])->result_array();
    $pa_by_agenda = [];
    $pa_ids = [];
    foreach ($pa_rows as $r) {
        $pa_by_agenda[(int)$r['agenda_id']] = $r;
        $pa_ids[] = (int)$r['pelatihan_agenda_id'];
    }
    $this->data['pa_by_agenda'] = $pa_by_agenda;

    // d) Grup pengajar per pelatihan-agenda
    $groups_by_agenda = [];
    if (!empty($pa_ids)) {
        $in = implode(',', array_map('intval', $pa_ids));
        $sqlGroups = "
            SELECT ga.agenda_group_id, ga.pelatihan_agenda_id, ga.group_no, ga.teacher_id,
                   pa.agenda_id, peg.nama AS teacher_name
            FROM tbl_grup_agenda ga
            JOIN tbl_pelatihan_agenda pa ON pa.pelatihan_agenda_id = ga.pelatihan_agenda_id
            LEFT JOIN tbl_pegawai peg ON peg.id_pegawai = ga.teacher_id
            WHERE ga.pelatihan_agenda_id IN ($in)
            ORDER BY pa.agenda_id ASC, ga.group_no ASC
        ";
        $gr = $this->db->query($sqlGroups)->result_array();
        foreach ($gr as $row) {
            $aid = (int)$row['agenda_id'];
            $groups_by_agenda[$aid][] = $row;
        }
    }
    $this->data['groups_by_agenda'] = $groups_by_agenda;

    // e) Daftar pegawai untuk dropdown pemilihan pengajar
    $this->data['pegawai'] = $this->db->query("
        SELECT id_pegawai, nama FROM tbl_pegawai ORDER BY nama ASC
    ")->result_array();

    // untuk reopen UI
    $this->data['open_modal'] = $this->session->flashdata('open_modal');

    // judul & render
    $this->data['title_web'] = 'Data Pengajar Pelatihan Dasar CPNS';
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    // View pakai pola dokumenview: baris agenda -> collapse topik -> area aksi (set main teacher, tambah grup)
    $this->load->view('materi_pengajar/list_pengajar', $this->data);
    $this->load->view('footer_view', $this->data);
}


public function prosespengajar()
{
    // Wajib login
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        redirect(base_url('login'));
    }

    // Helper redirect ke halaman listpengajar/id_pelatihan
    $redir = function($id_pelatihan, $flash = null, $open = null) {
        if (!empty($flash)) {
            $this->session->set_flashdata('pesan', $flash);
        }
        if (!empty($open)) {
            $this->session->set_flashdata('open_modal', $open);
        }
        redirect(base_url('data/listpengajar/' . (int)$id_pelatihan));
        exit;
    };

    // Helper: ambil atau buat tbl_pelatihan_agenda, return pelatihan_agenda_id
    $get_or_create_pa = function($id_pelatihan, $agenda_id) {
        $row = $this->db->get_where('tbl_pelatihan_agenda', [
            'id_pelatihan' => (int)$id_pelatihan,
            'agenda_id'    => (int)$agenda_id
        ])->row_array();

        if ($row) return (int)$row['pelatihan_agenda_id'];

        $this->db->insert('tbl_pelatihan_agenda', [
            'id_pelatihan'    => (int)$id_pelatihan,
            'agenda_id'       => (int)$agenda_id,
            'main_teacher_id' => NULL
        ]);
        return (int)$this->db->insert_id();
    };

    // =========================
    // Validasi dasar entitas
    // =========================
    $require_pel = function($id_pel) {
        $pel = $this->db->get_where('tbl_pelatihan', [
            'id_pelatihan' => (int)$id_pel,
            'deleted_at'   => NULL
        ])->row_array();
        return $pel ? true : false;
    };
    $require_agenda = function($agenda_id) {
        return $this->db->get_where('tbl_agenda', ['agenda_id' => (int)$agenda_id])->row_array() ? true : false;
    };
    $require_pegawai = function($id_pegawai) {
        if (!$id_pegawai) return true; // boleh NULL utk clear main teacher
        return $this->db->get_where('tbl_pegawai', ['id_pegawai' => (int)$id_pegawai])->row_array() ? true : false;
    };

    // =========================
    // Set / Update Main Teacher
    // =========================
    if (!empty($this->input->post('set_main_teacher'))) {
        $post           = $this->input->post();
        $id_pelatihan   = (int)($post['id_pelatihan'] ?? 0);
        $agenda_id      = (int)($post['agenda_id'] ?? 0);
        $main_teacher_id= (int)($post['main_teacher_id'] ?? 0);

        if (!$id_pelatihan || !$agenda_id) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Parameter tidak lengkap (pelatihan/agenda).</div>');
        }
        if (!$require_pel($id_pelatihan) || !$require_agenda($agenda_id)) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Pelatihan/Agenda tidak valid.</div>');
        }
        if (!$require_pegawai($main_teacher_id)) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Main teacher tidak ditemukan.</div>', ['type'=>'main','agenda_id'=>$agenda_id]);
        }

        // Upsert: ada -> update, tidak ada -> insert
        $pa_id = $get_or_create_pa($id_pelatihan, $agenda_id);
        $this->db->where('pelatihan_agenda_id', $pa_id)
                 ->update('tbl_pelatihan_agenda', ['main_teacher_id' => ($main_teacher_id ?: NULL)]);

        return $redir($id_pelatihan, '<div class="alert alert-success">Main teacher berhasil disimpan.</div>', ['type'=>'main','agenda_id'=>$agenda_id]);
    }

    // Hapus main teacher (set NULL); bila tanpa grup -> opsional hapus baris pa
    if (!empty($this->input->get('clear_main_teacher'))) {
        $id_pelatihan = (int)$this->input->get('id_pelatihan');
        $agenda_id    = (int)$this->input->get('agenda_id');

        if (!$id_pelatihan || !$agenda_id) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Parameter tidak lengkap.</div>');
        }
        $pa = $this->db->get_where('tbl_pelatihan_agenda', [
            'id_pelatihan' => $id_pelatihan, 'agenda_id' => $agenda_id
        ])->row_array();
        if (!$pa) {
            return $redir($id_pelatihan, '<div class="alert alert-warning">Penugasan belum ada.</div>');
        }

        $this->db->where('pelatihan_agenda_id', (int)$pa['pelatihan_agenda_id'])
                 ->update('tbl_pelatihan_agenda', ['main_teacher_id' => NULL]);

        // Cek orphan: jika tidak ada grup & main_teacher NULL -> hapus pa (opsional)
        $has_group = $this->db->get_where('tbl_grup_agenda', [
            'pelatihan_agenda_id' => (int)$pa['pelatihan_agenda_id']
        ])->row_array();

        if (!$has_group) {
            $this->db->delete('tbl_pelatihan_agenda', ['pelatihan_agenda_id' => (int)$pa['pelatihan_agenda_id']]);
        }

        return $redir($id_pelatihan, '<div class="alert alert-warning">Main teacher dihapus.</div>', ['type'=>'main','agenda_id'=>$agenda_id]);
    }

    // =========================
    // Tambah Grup Pengajar
    // =========================
    if (!empty($this->input->post('tambah_grup'))) {
        $post         = $this->input->post();
        $id_pelatihan = (int)($post['id_pelatihan'] ?? 0);
        $agenda_id    = (int)($post['agenda_id'] ?? 0);
        $group_no     = (int)($post['group_no'] ?? 0);
        $teacher_id   = (int)($post['teacher_id'] ?? 0);

        if (!$id_pelatihan || !$agenda_id || !$group_no || !$teacher_id) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Pelatihan, Agenda, No Grup, dan Teacher wajib diisi.</div>', ['type'=>'grup','agenda_id'=>$agenda_id]);
        }
        if (!$require_pel($id_pelatihan) || !$require_agenda($agenda_id) || !$require_pegawai($teacher_id)) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Entitas tidak valid.</div>', ['type'=>'grup','agenda_id'=>$agenda_id]);
        }

        $this->db->trans_begin();

        // Pastikan pa ada
        $pa_id = $get_or_create_pa($id_pelatihan, $agenda_id);

        // Cek duplikasi group_no pada pa yang sama
        $dup = $this->db->get_where('tbl_grup_agenda', [
            'pelatihan_agenda_id' => $pa_id,
            'group_no'            => $group_no
        ])->row_array();
        if ($dup) {
            $this->db->trans_rollback();
            return $redir($id_pelatihan, '<div class="alert alert-danger">Nomor grup sudah ada untuk agenda ini.</div>', ['type'=>'grup','agenda_id'=>$agenda_id]);
        }

        $this->db->insert('tbl_grup_agenda', [
            'pelatihan_agenda_id' => $pa_id,
            'group_no'            => $group_no,
            'teacher_id'          => $teacher_id
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $redir($id_pelatihan, '<div class="alert alert-danger">Gagal menambahkan grup.</div>', ['type'=>'grup','agenda_id'=>$agenda_id]);
        }
        $this->db->trans_commit();

        return $redir($id_pelatihan, '<div class="alert alert-success">Grup pengajar berhasil ditambahkan.</div>', ['type'=>'grup','agenda_id'=>$agenda_id]);
    }

    // =========================
    // Edit Grup Pengajar
    // =========================
    if (!empty($this->input->post('edit_grup'))) {
        $post            = $this->input->post();
        $agenda_group_id = (int)($post['edit_grup'] ?? 0);
        $id_pelatihan    = (int)($post['id_pelatihan'] ?? 0);
        $group_no        = (int)($post['group_no'] ?? 0);
        $teacher_id      = (int)($post['teacher_id'] ?? 0);

        if (!$agenda_group_id || !$id_pelatihan || !$group_no || !$teacher_id) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Parameter grup tidak lengkap.</div>');
        }
        if (!$require_pel($id_pelatihan) || !$require_pegawai($teacher_id)) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Entitas tidak valid.</div>');
        }

        $gr = $this->db->get_where('tbl_grup_agenda', [
            'agenda_group_id' => $agenda_group_id
        ])->row_array();
        if (!$gr) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Data grup tidak ditemukan.</div>');
        }

        // Pastikan grup ini memang milik pelatihan yang sama
        $pa = $this->db->get_where('tbl_pelatihan_agenda', [
            'pelatihan_agenda_id' => (int)$gr['pelatihan_agenda_id']
        ])->row_array();
        if (!$pa || (int)$pa['id_pelatihan'] !== $id_pelatihan) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Grup tidak sesuai pelatihan.</div>');
        }

        // Cek bentrok group_no lain pada PA yang sama
        $dup = $this->db->where('pelatihan_agenda_id', (int)$gr['pelatihan_agenda_id'])
                        ->where('group_no', $group_no)
                        ->where('agenda_group_id !=', $agenda_group_id)
                        ->get('tbl_grup_agenda')->row_array();
        if ($dup) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Nomor grup sudah digunakan.</div>', ['type'=>'grup','agenda_id'=>$pa['agenda_id']]);
        }

        $this->db->where('agenda_group_id', $agenda_group_id)
                 ->update('tbl_grup_agenda', [
                     'group_no'   => $group_no,
                     'teacher_id' => $teacher_id
                 ]);

        return $redir($id_pelatihan, '<div class="alert alert-success">Grup pengajar berhasil diperbarui.</div>', ['type'=>'grup','agenda_id'=>$pa['agenda_id']]);
    }

    // =========================
    // Hapus Grup Pengajar
    // =========================
    if (!empty($this->input->get('delete_grup_agenda'))) {
        $agenda_group_id = (int)$this->input->get('delete_grup_agenda');
        $id_pelatihan    = (int)$this->input->get('id_pelatihan');

        $gr = $this->db->get_where('tbl_grup_agenda', ['agenda_group_id' => $agenda_group_id])->row_array();
        if (!$gr) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Data grup tidak ditemukan.</div>');
        }

        $pa = $this->db->get_where('tbl_pelatihan_agenda', [
            'pelatihan_agenda_id' => (int)$gr['pelatihan_agenda_id']
        ])->row_array();
        if (!$pa || (int)$pa['id_pelatihan'] !== $id_pelatihan) {
            return $redir($id_pelatihan, '<div class="alert alert-danger">Grup tidak sesuai pelatihan.</div>');
        }

        $this->db->trans_begin();
        $this->db->delete('tbl_grup_agenda', ['agenda_group_id' => $agenda_group_id]);

        // Bersihkan PA bila orphan (tanpa main teacher & tanpa grup)
        $pa = $this->db->get_where('tbl_pelatihan_agenda', [
            'pelatihan_agenda_id' => (int)$gr['pelatihan_agenda_id']
        ])->row_array();

        if ($pa) {
            $has_group = $this->db->get_where('tbl_grup_agenda', [
                'pelatihan_agenda_id' => (int)$pa['pelatihan_agenda_id']
            ])->row_array();

            if (!$has_group && empty($pa['main_teacher_id'])) {
                $this->db->delete('tbl_pelatihan_agenda', ['pelatihan_agenda_id' => (int)$pa['pelatihan_agenda_id']]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $redir($id_pelatihan, '<div class="alert alert-danger">Terjadi kesalahan saat menghapus grup.</div>');
        }
        $this->db->trans_commit();

        return $redir($id_pelatihan, '<div class="alert alert-warning">Grup pengajar berhasil dihapus.</div>', ['type'=>'grup','agenda_id'=>$pa['agenda_id'] ?? null]);
    }

    // Default fallback
    $id_pel = (int)($this->input->post('id_pelatihan') ?? $this->input->get('id_pelatihan') ?? 0);
    return $redir($id_pel);
}



	// Code LDK Pekanbaru Controller Cetak Laporan

	public function cetaklaporan()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$jenis = $this->input->get('jenis');

		// Build query dengan benar
		$this->db->select('*');
		$this->db->from('tbl_pelatihan');
		$this->db->where('deleted_at IS NULL', null, false); // Hanya yang tidak dihapus

		// Terapkan filter berdasarkan jenis
		if ($jenis == 'PJJ') {
			$this->db->where('id_jenis_pelatihan', 1);
		} elseif ($jenis == 'PDWK') {
			$this->db->where('id_jenis_pelatihan', 2);
		}
		// Jika tidak ada filter, tampilkan semua (tanpa where id_jenis_pelatihan)

		$this->data['pelatihan'] = $this->db->order_by('id_pelatihan', 'DESC')->get();

		// DEBUG: Tampilkan query dan hasil untuk memastikan
		// echo "Query: " . $this->db->last_query() . "<br>";
		// echo "Jumlah hasil: " . $this->data['pelatihan']->num_rows() . "<br>";
		
		// Tampilkan beberapa data untuk debugging
		// if ($this->data['pelatihan']->num_rows() > 0) {
		// 	echo "Contoh data:<br>";
		// 	$first_row = $this->data['pelatihan']->first_row('array');
		// 	print_r($first_row);
		// }
		// die();

		$this->data['title_web'] = 'Cetak Laporan Pelatihan';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('cetak_laporan/list_pelatihan_pjj',$this->data);
		$this->load->view('footer_view',$this->data);
	}
	

	public function cetaklaporanpdwk()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
    	$this->data['pelatihan'] = $this->db->query("SELECT * FROM tbl_pelatihan WHERE deleted_at IS NULL and id_jenis_pelatihan = 2 ORDER BY id_pelatihan DESC");
        $this->data['title_web'] = 'Cetak Laporan Pelatihan PDWK';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('cetak_laporan/list_pelatihan_pdwk',$this->data);
        $this->load->view('footer_view',$this->data);
	}
	
	public function cetaklaporanlatsar()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
    	$this->data['pelatihan'] = $this->db->query("SELECT * FROM tbl_pelatihan WHERE deleted_at IS NULL and id_jenis_pelatihan = 3 ORDER BY id_pelatihan DESC");
        $this->data['title_web'] = 'Cetak Laporan Latsar';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('cetak_laporan/list_pelatihan_latsar',$this->data);
        $this->load->view('footer_view',$this->data);
	}

		public function cetaklampiranlaporan()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$jenis = $this->input->get('jenis');
    	$this->data['pelatihan'] = $this->db->query("SELECT * FROM tbl_pelatihan WHERE deleted_at IS NULL ORDER BY id_pelatihan DESC");
		// Terapkan filter berdasarkan jenis
		if ($jenis == 'PJJ') {
			$this->db->where('id_jenis_pelatihan', 1);
		} elseif ($jenis == 'PDWK') {
			$this->db->where('id_jenis_pelatihan', 2);
		} elseif ($jenis == 'Latsar') {
			$this->db->where('id_jenis_pelatihan', 3);
		}
        $this->data['title_web'] = 'Cetak Lampiran Dokumen Pelatihan';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('cetak_laporan/list_lampiran_pelatihan_pjj',$this->data);
        $this->load->view('footer_view',$this->data);
	}

		public function cetaklampiranlaporanpdwk()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
    	$this->data['pelatihan'] = $this->db->query("SELECT * FROM tbl_pelatihan WHERE deleted_at IS NULL and id_jenis_pelatihan = 2 ORDER BY id_pelatihan DESC");
        $this->data['title_web'] = 'Cetak Lampiran Dokumen Pelatihan';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('cetak_laporan/list_lampiran_pelatihan_pdwk',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function listlampiranpelatihan($id_pelatihan)
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$query = $this->db->get_where('tbl_pelatihan', [
			'id_pelatihan' => $id_pelatihan,
			'deleted_at' => NULL
		]);

		if ($query->num_rows() == 0) {
			show_404(); // Or you can use redirect with flash message
		}

		$this->data['pelatihan'] = $query->row_array();
		$this->data['title_web'] = 'Preview Cetak Lampiran Pelatihan';
		$this->load->view('cetak_laporan/cetak_lampiran_preview', $this->data);

	}

// 	public function exportLampiranPelatihan($id_pelatihan)
// {
//     $pelatihan = $this->db->get_where('tbl_pelatihan', [
//         'id_pelatihan' => $id_pelatihan,
//         'deleted_at' => NULL
//     ])->row_array();

//     if (!$pelatihan) {
//         show_404();
//     }

//     // Ambil data kegiatan
//     $activities = $this->db->query("
//         SELECT a.*, p.nama AS nama_narasumber
//         FROM tbl_pelatihan_activity a
//         LEFT JOIN tbl_pegawai p ON a.id_narasumber = p.id_pegawai
//         WHERE a.id_pelatihan = ? AND a.deleted_at IS NULL
//         ORDER BY a.tanggal_activity ASC, a.jam_mulai ASC
//     ", [$id_pelatihan])->result_array();

//     // Ambil semua foto, dikelompokkan berdasarkan id_activity
//     $fotos = $this->db->query("
//         SELECT * FROM tbl_pelatihan_foto 
//         WHERE id_activity IN (
//             SELECT id_activity FROM tbl_pelatihan_activity 
//             WHERE id_pelatihan = ? AND deleted_at IS NULL
//         ) AND deleted_at IS NULL
//         ORDER BY tanggal_foto ASC
//     ", [$id_pelatihan])->result_array();

//     $fotoMap = [];
//     foreach ($fotos as $f) {
//         $fotoMap[$f['id_activity']][] = $f;
//     }

//     $phpWord = new PhpWord();
//     $section = $phpWord->addSection();

//     // Judul Dokumen
//     $section->addText('LAMPIRAN DOKUMENTASI PELATIHAN', ['bold' => true, 'size' => 14], ['align' => 'center']);
//     $section->addTextBreak(1);

//     // Tambahkan deskripsi pelatihan
//     $bulan = [
//         '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
//         '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
//         '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
//     ];
//     $tgl1 = explode('-', $pelatihan['tanggal_mulai_pelatihan']);
//     $tgl2 = explode('-', $pelatihan['tanggal_selesai_pelatihan']);
//     $tglMulai = $tgl1[2] . ' ' . $bulan[$tgl1[1]] . ' ' . $tgl1[0];
//     $tglSelesai = $tgl2[2] . ' ' . $bulan[$tgl2[1]] . ' ' . $tgl2[0];

//     $section->addText("Nama Pelatihan: " . $pelatihan['nama_pelatihan'], ['bold' => true]);
//     $section->addText("Periode: $tglMulai - $tglSelesai", ['italic' => true]);
//     $section->addTextBreak(1);

//     // Loop kegiatan
//     foreach ($activities as $act) {
//         $section->addText("Hari ke-{$act['day_ke']} | Sesi ke-{$act['sesi_ke']}", ['bold' => true, 'size' => 11]);
//         $section->addText("Judul Kegiatan: {$act['nama_kegiatan']}");
//         $section->addText("Tanggal: {$act['tanggal_activity']}, Pukul: {$act['jam_mulai']} - {$act['jam_selesai']}");
//         $section->addText("Narasumber: " . ($act['nama_narasumber'] ?? '-'));
//         if (!empty($act['activity_desc'])) {
//             $section->addText("Deskripsi: {$act['activity_desc']}", [], ['alignment' => Jc::BOTH]);
//         }
//         $section->addTextBreak(1);

//         // Tambahkan foto
//         if (isset($fotoMap[$act['id_activity']])) {
//             foreach ($fotoMap[$act['id_activity']] as $foto) {
//                 $fotoPath = FCPATH . $foto['foto_path'];
//                 if (file_exists($fotoPath)) {
//                     $section->addImage($fotoPath, [
//                         'width' => 300,
//                         'height' => 200,
//                         'alignment' => Jc::CENTER
//                     ]);
//                     if (!empty($foto['keterangan'])) {
//                         $section->addText("Keterangan Foto: {$foto['keterangan']}", ['italic' => true], ['align' => 'center']);
//                     }
//                     $section->addText("Tanggal Foto: {$foto['tanggal_foto']}", ['size' => 10], ['align' => 'center']);
//                     $section->addTextBreak(1);
//                 }
//             }
//         } else {
//             $section->addText("Tidak ada dokumentasi foto untuk sesi ini.", ['italic' => true]);
//         }

//         $section->addTextBreak(2);
//     }

//     // Penutup
//     $section->addTextBreak(2);
//     $section->addText("Mengetahui,", ['bold' => true], ['align' => 'right']);
//     $section->addTextBreak(2);
//     $section->addText("........................................", ['underline' => 'single'], ['align' => 'right']);
//     $section->addText("Penanggung Jawab", [], ['align' => 'right']);

//     // Ekspor dokumen
//     $filename = 'Lampiran_Dokumentasi_Pelatihan_' . date('Ymd_His') . '.docx';
//     header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
//     header("Content-Disposition: attachment; filename=\"$filename\"");
//     header('Cache-Control: max-age=0');

//     $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
//     $objWriter->save('php://output');
//     exit;
// }

// export yang dipake

// public function exportFotoPelatihan($id_pelatihan)
// {
//     $pelatihan = $this->db->get_where('tbl_pelatihan', [
//         'id_pelatihan' => $id_pelatihan,
//         'deleted_at' => NULL
//     ])->row_array();

//     if (!$pelatihan) {
//         show_404();
//     }

//     // Ambil data kegiatan
//     $activities = $this->db->query("
//         SELECT a.*, p.nama AS nama_narasumber
//         FROM tbl_pelatihan_activity a
//         LEFT JOIN tbl_pegawai p ON a.id_narasumber = p.id_pegawai
//         WHERE a.id_pelatihan = ? AND a.deleted_at IS NULL
//         ORDER BY a.tanggal_activity ASC, a.jam_mulai ASC
//     ", [$id_pelatihan])->result_array();

//     // Ambil semua foto
//     $fotos = $this->db->query("
//         SELECT * FROM tbl_pelatihan_foto 
//         WHERE id_activity IN (
//             SELECT id_activity FROM tbl_pelatihan_activity 
//             WHERE id_pelatihan = ? AND deleted_at IS NULL
//         ) AND deleted_at IS NULL
//         ORDER BY tanggal_foto ASC
//     ", [$id_pelatihan])->result_array();

//     // Group foto berdasarkan id_activity
//     $fotoMap = [];
//     foreach ($fotos as $f) {
//         $fotoMap[$f['id_activity']][] = $f;
//     }

//     // Load PHPWord & View untuk export
//     $this->load->view('cetak_laporan/export_foto_pelatihan', [
//         'pelatihan' => $pelatihan,
//         'activities' => $activities,
//         'fotoMap' => $fotoMap
//     ]);
// }

// Export yang dipake 2 (foto)
public function exportFotoPelatihan($id_pelatihan)
{
    $panitia_id = $this->session->userdata('id_login'); 
    $level = $this->session->userdata('level');
    $pelatihan = $this->db->get_where('tbl_pelatihan', [
        'id_pelatihan' => $id_pelatihan,
        'deleted_at' => NULL
    ])->row_array();

    if (!$pelatihan) {
        show_404();
    }

    if ($level !== 'admin' && $level !== 'Admin') { 
        $cek_akses = $this->db 
            ->where('pelatihan_id', $id_pelatihan) 
            ->where('panitia_id', $panitia_id) 
            ->get('tbl_panitia_pelatihan') 
            ->num_rows(); 
    
        if ($cek_akses == 0) { 
            show_error('Anda tidak berhak mengakses pelatihan ini.', 403); 
            return;
        } 
    }

    // Ambil data kegiatan dengan join ke tbl_pegawai dan tbl_role
    $activities = $this->db->query("
        SELECT 
            a.*, 
            p.nama AS nama_narasumber,
            p.asal_satker,
            r.nama_role AS jabatan
        FROM tbl_pelatihan_activity a
        LEFT JOIN tbl_pegawai p ON a.id_narasumber = p.id_pegawai
        LEFT JOIN tbl_role r ON p.jabatan = r.id_role
        WHERE a.id_pelatihan = ? AND a.deleted_at IS NULL
        ORDER BY a.day_ke ASC, a.sesi_ke ASC, a.jam_mulai ASC
    ", [$id_pelatihan])->result_array();

    // Ambil semua foto
    $fotos = $this->db->query("
        SELECT * FROM tbl_pelatihan_foto 
        WHERE id_activity IN (
            SELECT id_activity FROM tbl_pelatihan_activity 
            WHERE id_pelatihan = ? AND deleted_at IS NULL
        ) AND deleted_at IS NULL
        ORDER BY tanggal_foto ASC
    ", [$id_pelatihan])->result_array();

    // Group foto berdasarkan id_activity
    $fotoMap = [];
    foreach ($fotos as $f) {
        $fotoMap[$f['id_activity']][] = $f;
    }

    // Load PHPWord & View untuk export
    $this->load->view('cetak_laporan/export_foto_pelatihan', [
        'pelatihan' => $pelatihan,
        'activities' => $activities,
        'fotoMap' => $fotoMap
    ]);
}

// ini dipake sebelummnya utk export lampiran
// public function exportLampiranPelatihan($id_pelatihan)
// {
//     $pelatihan = $this->db->get_where('tbl_pelatihan', [
//         'id_pelatihan' => $id_pelatihan,
//         'deleted_at' => NULL
//     ])->row_array();

//     if (!$pelatihan) {
//         show_404();
//     }

//     // Get all documents for this training
//     $documents = $this->db->query("
//         SELECT pd.*, d.nama_dokumen, d.deskripsi
//         FROM tbl_pelatihan_dokumen pd
//         JOIN tbl_dokumen d ON pd.id_dokumen = d.id_dokumen
//         WHERE pd.id_pelatihan = ? AND pd.deleted_at IS NULL
//         ORDER BY pd.tanggal_upload ASC
//     ", [$id_pelatihan])->result_array();
    
//     // Add full path to each document
//     foreach ($documents as &$doc) {
//         $doc['full_path'] = FCPATH . 'assets_style/assets/dokumen/' . $doc['file_path'];
//     }
    
//     // Pass data to view
//     $this->load->view('cetak_laporan/export_lampiran_pelatihan', [
//         'pelatihan' => $pelatihan,
//         'documents' => $documents
//     ]);
// }

// Backup
// public function exportLampiranPelatihan($id_pelatihan)
// {
//     $pelatihan = $this->db->get_where('tbl_pelatihan', [
//         'id_pelatihan' => $id_pelatihan,
//         'deleted_at' => NULL
//     ])->row_array();

//     if (!$pelatihan) {
//         show_404();
//     }

//     // Get all documents for this training
//     $documents = $this->db->query("
//         SELECT pd.*, d.id_dokumen, d.nama_dokumen, d.deskripsi
//         FROM tbl_pelatihan_dokumen pd
//         JOIN tbl_dokumen d ON pd.id_dokumen = d.id_dokumen
//         WHERE pd.id_pelatihan = ? AND pd.deleted_at IS NULL
//         ORDER BY pd.tanggal_upload ASC
//     ", [$id_pelatihan])->result_array();
    
//     // Define the custom order of document types
//     $customOrder = [
//         'Susunan Acara Pembukaan' => 1,
//         'Susunan Acara Penutupan' => 2,
//         'Surat Tanda Tamat Pelatihan (STTP)' => 3,
//         'Daftar Hadir Rapat Persiapan' => 4,
//         'Daftar Hadir Pelaksanaan Kegiatan Pelatihan' => 5,
//         'Daftar Hadir Rapat Evaluasi' => 6,
//         'Notulen Rapat Persiapan' => 7,
//         'Notulen Rapat Evaluasi' => 8,
//         'Surat Permohonan Widyaiswara' => 9,
//         'Surat Pemberitahuan' => 10,
//         'Jadwal Kegiatan' => 11,
//         'Buku Panduan' => 12,
//         'Bahan Tayang Widyaiswara' => 13,
//         'Bahan Ajar Widyaiswara' => 14
//     ];
    
//     // Sort documents according to custom order
//     usort($documents, function($a, $b) use ($customOrder) {
//         $orderA = $customOrder[$a['nama_dokumen']] ?? PHP_INT_MAX;
//         $orderB = $customOrder[$b['nama_dokumen']] ?? PHP_INT_MAX;
        
//         return $orderA <=> $orderB;
//     });
    
//     // Add full path to each document
//     foreach ($documents as &$doc) {
//         $doc['full_path'] = FCPATH . 'assets_style/assets/dokumen/' . $doc['file_path'];
//     }
    
//     // Pass data to view
//     $this->load->view('cetak_laporan/export_lampiran_pelatihan', [
//         'pelatihan' => $pelatihan,
//         'documents' => $documents
//     ]);
// }

public function exportLampiranPelatihan($id_pelatihan)
{
    $pelatihan = $this->db->get_where('tbl_pelatihan', [
        'id_pelatihan' => $id_pelatihan,
        'deleted_at'   => NULL
    ])->row_array();

    if (!$pelatihan) {
        show_404();
    }

    // Ambil dokumen
    $documents = $this->db->query("
        SELECT pd.*, d.id_dokumen, d.nama_dokumen, d.deskripsi
        FROM tbl_pelatihan_dokumen pd
        JOIN tbl_dokumen d ON pd.id_dokumen = d.id_dokumen
        WHERE pd.id_pelatihan = ? AND pd.deleted_at IS NULL
        ORDER BY pd.tanggal_upload ASC, pd.id_pelatihan_dokumen ASC
    ", [$id_pelatihan])->result_array();

    // ========= 1) Definisi URUTAN =========
    // Urutan DEFAULT (non-Latsar).
    $defaultOrder = [
        'Susunan Acara Pembukaan',
        'Susunan Acara Penutupan',
        'Surat Tanda Tamat Pelatihan (STTP)',
        'Daftar Hadir Rapat Persiapan',
        'Daftar Hadir Pelaksanaan Kegiatan Pelatihan',
        'Daftar Hadir Rapat Evaluasi',
        'Notulen Rapat Persiapan',
        'Notulen Rapat Evaluasi',
        'Surat Permohonan Widyaiswara',
        'Surat Pemberitahuan',
        'Jadwal Kegiatan',
        'Buku Panduan',
        'Bahan Tayang Widyaiswara',
        'Bahan Ajar Widyaiswara',
    ];

    // Urutan LATSAR (id_jenis_pelatihan = 3).
    $latsarOrder = [
        'Notulensi Hasil Rapat Persiapan Latsar',
        'Daftar Hadir dan Dokumentasi Rapat Persiapan',
        'Laporan Persiapan Penyelenggaraan Pelatihan',
        'Struktur Kurikulum',
        'TOR',
        'Panduan',
        'Jadwal Kegiatan',
        'Realisasi Jadwal Kegiatan',
        'Susunan Acara Pembukaan',
        'Laporan Ketua pada Pembukaan',
        'Surat Permintaan Penceramah/Pengajar',
        'Surat Pemanggilan Mentor',
        'Surat Permintaan Penguji',
        'Surat Panggilan Peserta',
        'SK Panitia Penyelenggara',
        'Surat Keputusan Penunjukkan Pengajar dan Peserta serta Lampirannya',
        'Lampiran SK Penunjukkan Panitia',
        'Lampiran SK Penunjukkan Pengajar',
        'Lampiran SK Penunjukkan Coach',
        'Lampiran SK Penunjukkan Mentor',
        'Lampiran SK Penunjukkan Penguji',
        'Lampiran SK Penunjukkan Peserta',
        'Biodata Peserta',
        'Biodata Pengajar/Narasumber',
        'Daftar Hadir Peserta',
        'Daftar Hadir Pengajar/Narasumber',
        'Daftar Hadir Seminar RA dan Aktualisasi',
        'Undangan Rapat Penyamaan Persepsi Evaluasi RA',
        'Notulen Rapat Penyamaan Persepsi Evaluasi RA',
        'DH dan Dokumentasi Rapat Penyamaan Persepsi Evaluasi RA',
        'Undangan Rapat Penyamaan Persepsi Evaluasi Aktualisasi',
        'Notulen Rapat Penyamaan Persepsi Evaluasi Aktualisasi',
        'DH dan Dokumentasi Rapat Penyamaan Persepsi Aktualisasi',
        'Notulen Rapat Evaluasi Akhir',
        'DH dan Dokumentasi Rapat Evaluasi Akhir',
        'Bertita Acara Kelulusan Peserta',
        'SK Penetapan Kelulusan Peserta',
        'Hasil Evaluasi Peserta Terhadap Penyelenggara',
        'Hasil Evaluasi Peserta Terhadap Pengajar/Narasumber',
        'Hasil Evaluasi Terhadap Peserta',
        'Analisis Hasil Evaluasi',
        'Susunan Acara Penutupan',
        'Laporan Ketua pada Penutupan',
        'STPP/Sertifikat/Surat Keterangan Pelatihan',
        'Surat Pengembalian Peserta',
        'Dokumentasi',
        'Undangan Pembekalan Mentor',
        'DH dan Dokumentasi Pembekalan Mentor',
        'Materi Pembekalan Mentor',
    ];

    // ========= 2) Normalisasi & Kamus Sinonim =========
    $normalize = function (string $s): string {
        // lowercase, hapus spasi ganda, buang karakter non-alfanum (kecuali spasi), trim
        $s = strtolower($s);
        $s = preg_replace('/\s+/', ' ', $s);
        $s = preg_replace('/[^a-z0-9\s\/\-\(\)]+/u', '', $s);
        $s = trim($s);
        // standarisasi beberapa istilah umum
        $s = str_replace(['stpp', 'sttp'], 'stpp', $s);        // samakan STTP↔STPP
        $s = str_replace(['dh ', ' dh'], ' daftar hadir ', $s); // “DH” → “daftar hadir”
        $s = str_replace('notulensi', 'notulen', $s);           // selaraskan “notulensi”→“notulen”
        return $s;
    };

    $buildOrderIndex = function (array $orderedNames) use ($normalize): array {
        $idx = [];
        $i = 1;
        foreach ($orderedNames as $name) {
            $idx[$normalize($name)] = $i++;
        }
        return $idx;
    };

    // Pilih urutan sesuai jenis pelatihan
    $isLatsar   = ((int)($pelatihan['id_jenis_pelatihan'] ?? 0) === 3);
    $orderIndex = $buildOrderIndex($isLatsar ? $latsarOrder : $defaultOrder);

    // ========= 3) Sort dokumen sesuai urutan kustom =========
    // Beri ranking: jika tidak ditemukan di kamus, letakkan di bagian akhir (PHP_INT_MAX) namun tetap stabil.
    foreach ($documents as $k => &$doc) {
        $normName            = $normalize($doc['nama_dokumen'] ?? '');
        $doc['__order_rank'] = $orderIndex[$normName] ?? PHP_INT_MAX;
        $doc['__orig_idx']   = $k; // untuk menjaga stabilitas urutan awal jika sama
    }
    unset($doc);

    usort($documents, function ($a, $b) {
        if ($a['__order_rank'] === $b['__order_rank']) {
            // stabil: pertahankan urutan input
            return $a['__orig_idx'] <=> $b['__orig_idx'];
        }
        return $a['__order_rank'] <=> $b['__order_rank'];
    });

    // ========= 4) Tambahkan full_path (opsional: cek keberadaan file) =========
    foreach ($documents as &$doc) {
        $safePath = ltrim(str_replace(['..', '\\'], ['','/'], $doc['file_path']), '/');
        $doc['full_path'] = FCPATH . 'assets_style/assets/dokumen/' . $safePath;
        // Jika perlu: tandai bila file tidak ditemukan
        $doc['exists'] = is_file($doc['full_path']);
    }
    unset($doc);

    // ========= 5) Kirim ke view =========
    $this->load->view('cetak_laporan/export_lampiran_pelatihan', [
        'pelatihan' => $pelatihan,
        'documents' => $documents,
        'is_latsar' => $isLatsar,
    ]);
}


// public function exportLampiranPelatihan($id_pelatihan)
// {
//     $pelatihan = $this->db->get_where('tbl_pelatihan', [
//         'id_pelatihan' => $id_pelatihan,
//         'deleted_at' => NULL
//     ])->row_array();

//     if (!$pelatihan) {
//         show_404();
//     }

//     // Ambil data kegiatan
//     $activities = $this->db->query("
//         SELECT a.*, p.nama AS nama_narasumber
//         FROM tbl_pelatihan_activity a
//         LEFT JOIN tbl_pegawai p ON a.id_narasumber = p.id_pegawai
//         WHERE a.id_pelatihan = ? AND a.deleted_at IS NULL
//         ORDER BY a.tanggal_activity ASC, a.jam_mulai ASC
//     ", [$id_pelatihan])->result_array();

//     // Ambil semua foto
//     $fotos = $this->db->query("
//         SELECT * FROM tbl_pelatihan_foto 
//         WHERE id_activity IN (
//             SELECT id_activity FROM tbl_pelatihan_activity 
//             WHERE id_pelatihan = ? AND deleted_at IS NULL
//         ) AND deleted_at IS NULL
//         ORDER BY tanggal_foto ASC
//     ", [$id_pelatihan])->result_array();

//     // Ambil data dokumen
//     $dokumen = $this->db->query("
//         SELECT pd.*, d.nama_dokumen, d.deskripsi
//         FROM tbl_pelatihan_dokumen pd
//         JOIN tbl_dokumen d ON pd.id_dokumen = d.id_dokumen
//         WHERE pd.id_pelatihan = ? AND pd.deleted_at IS NULL
//         ORDER BY pd.tanggal_upload ASC
//     ", [$id_pelatihan])->result_array();

//     // Group foto berdasarkan id_activity
//     $fotoMap = [];
//     foreach ($fotos as $f) {
//         $fotoMap[$f['id_activity']][] = $f;
//     }

//     // Load PHPWord & View untuk export
//     $this->load->view('cetak_laporan/export_lampiran_pelatihan', [
//         'pelatihan' => $pelatihan,
//         'activities' => $activities,
//         'fotoMap' => $fotoMap,
//         'dokumen' => $dokumen
//     ]);
// }




	// public function listkegiatanpelatihan($id_pelatihan)
	// {
	// 	$this->data['idbo'] = $this->session->userdata('ses_id');

	// 	$cek_pelatihan = $this->db->get_where('tbl_pelatihan', [
	// 		'id_pelatihan' => $id_pelatihan,
	// 		'deleted_at' => NULL
	// 	])->row();

	// 	if (!$cek_pelatihan) {
	// 		echo '<script>alert("Data pelatihan tidak ditemukan."); window.location="' . base_url('data/kegiatanpelatihan') . '"</script>';
	// 		return;
	// 	}

	// 	$this->data['pelatihan'] = $cek_pelatihan;

	// 	// Ambil semua kegiatan pelatihan terkait
	// 	$this->data['dokumentasi_pelatihan'] = $this->db->query("
	// 		SELECT a.*, p.nama as nama_narasumber 
	// 		FROM tbl_pelatihan_activity a 
	// 		LEFT JOIN tbl_pegawai p ON a.id_narasumber = p.id_pegawai 
	// 		WHERE a.id_pelatihan = ? AND a.deleted_at IS NULL
	// 		ORDER BY a.tanggal_activity ASC, a.jam_mulai ASC
	// 	", [$id_pelatihan]);

	// 	$this->data['pegawai'] = $this->db
	// 		->where('deleted_at', NULL)
	// 		->order_by('nama', 'ASC')
	// 		->get('tbl_pegawai')
	// 		->result();

	// 	$this->data['id_pelatihan'] = $id_pelatihan;
	// 	$this->data['title_web'] = 'Kegiatan Pelatihan - ' . htmlentities($cek_pelatihan->nama_pelatihan);
	// 	$this->load->view('header_view', $this->data);
	// 	$this->load->view('sidebar_view', $this->data);
	// 	$this->load->view('dokumentasi_pelatihan/list_kegiatan_pelatihan', $this->data);
	// 	$this->load->view('footer_view', $this->data);
	// }

	// public function proseskegiatanpelatihan()
	// {
	// 	if ($this->session->userdata('masuk_perpus') != TRUE) {
	// 		redirect(base_url('login'));
	// 	}

	// 	// Handle Tambah
	// 	if (!empty($this->input->post('tambah'))) {
	// 		$post = $this->input->post();

	// 		$data = [
	// 			'id_pelatihan'      => htmlentities($post['id_pelatihan']),
	// 			'sesi_ke'           => htmlentities($post['sesi_ke']),
	// 			'day_ke'            => htmlentities($post['day_ke']),
	// 			'nama_kegiatan'     => htmlentities($post['nama_kegiatan']),
	// 			'id_narasumber'     => htmlentities($post['id_narasumber']),
	// 			'activity_desc'     => htmlentities($post['activity_desc']),
	// 			'tanggal_activity'  => htmlentities($post['tanggal_activity']),
	// 			'jam_mulai'         => htmlentities($post['jam_mulai']),
	// 			'jam_selesai'       => htmlentities($post['jam_selesai']),
	// 			'created_at'        => date('Y-m-d H:i:s'),
	// 			'updated_at'        => date('Y-m-d H:i:s'),
	// 			'deleted_at'        => NULL
	// 		];

	// 		$this->db->insert('tbl_pelatihan_activity', $data);
	// 		$this->session->set_flashdata('pesan', '<div class="alert alert-success">Kegiatan berhasil ditambahkan!</div>');
	// 		redirect(base_url('data/listkegiatanpelatihan/' . $post['id_pelatihan']));
	// 	}

	// 	// Handle Edit
	// 	if (!empty($this->input->post('edit'))) {
	// 		$post = $this->input->post();

	// 		$data = [
	// 			'sesi_ke'           => htmlentities($post['sesi_ke']),
	// 			'day_ke'            => htmlentities($post['day_ke']),
	// 			'nama_kegiatan'     => htmlentities($post['nama_kegiatan']),
	// 			'id_narasumber'     => htmlentities($post['id_narasumber']),
	// 			'activity_desc'     => htmlentities($post['activity_desc']),
	// 			'tanggal_activity'  => htmlentities($post['tanggal_activity']),
	// 			'jam_mulai'         => htmlentities($post['jam_mulai']),
	// 			'jam_selesai'       => htmlentities($post['jam_selesai']),
	// 			'updated_at'        => date('Y-m-d H:i:s'),
	// 		];

	// 		$this->db->where('id_activity', htmlentities($post['edit']));
	// 		$this->db->update('tbl_pelatihan_activity', $data);
	// 		$this->session->set_flashdata('pesan', '<div class="alert alert-success">Kegiatan berhasil diperbarui!</div>');
	// 		redirect(base_url('data/listkegiatanpelatihan/' . $post['id_pelatihan']));
	// 	}

	// 	// Handle Delete (soft delete)
	// 	if (!empty($this->input->get('id_activity'))) {
	// 		$id_activity = htmlentities($this->input->get('id_activity'));

	// 		$data = $this->db->get_where('tbl_pelatihan_activity', [
	// 			'id_activity' => $id_activity
	// 		])->row();

	// 		if ($data) {
	// 			$this->db->set('deleted_at', date('Y-m-d H:i:s'));
	// 			$this->db->where('id_activity', $id_activity);
	// 			$this->db->update('tbl_pelatihan_activity');

	// 			$this->session->set_flashdata('pesan', '<div class="alert alert-warning">Kegiatan berhasil dihapus!</div>');
	// 			redirect(base_url('data/listkegiatanpelatihan/' . $data->id_pelatihan));
	// 		} else {
	// 			echo '<script>alert("Data tidak ditemukan."); window.location="' . base_url('data/kegiatanpelatihan') . '"</script>';
	// 		}
	// 	}
	// }

	public function faqsistem()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
    	
        $this->data['title_web'] = 'Frequently Asked Questions (FAQ)';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('faqsistem_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}


	


	// Code LDK Pekanbaru Controller Menu Pegawai
	public function pegawai()
{
    // Ambil ID user yang login
    $this->data['idbo'] = $this->session->userdata('ses_id');

    // Ambil semua role yang belum dihapus (deleted_at IS NULL)
    $this->data['pegawai'] = $this->db->query("SELECT * FROM tbl_pegawai WHERE deleted_at IS NULL ORDER BY id_pegawai DESC");

    // Cek apakah ada parameter ?id= di URL
    if (!empty($this->input->get('id'))) {
        $id = $this->input->get('id');
        $count = $this->M_Admin->CountTableId('tbl_pegawai', 'id_pegawai', $id);

        if ($count > 0) {
            // Tetap ambil datanya (tanpa filtering deleted_at karena ini konteks pengeditan spesifik)
            $this->data['roles'] = $this->db->query("SELECT * FROM tbl_pegawai WHERE id_pegawai='$id'")->row();
        } else {
            echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="' . base_url('data/pegawai') . '"</script>';
        }
    }

    // Set judul dan load view
    $this->data['title_web'] = 'Data Pegawai ';
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('pegawai/pegawai_view', $this->data);
    $this->load->view('footer_view', $this->data);
}

public function prosespegawai()
{
    // Pastikan user sudah login
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        redirect(base_url('login'));
    }

    // // === SOFT DELETE PEGAWAI ===
    // if (!empty($this->input->get('id_pegawai'))) {
    //     $id_pegawai = htmlentities($this->input->get('id_pegawai'));

    //     // Cek apakah data pegawai ada
    //     $pegawai = $this->M_Admin->get_tableid_edit(
    //         'tbl_pegawai',
    //         'id_pegawai',
    //         $id_pegawai
    //     );

    //     if ($pegawai) {
    //         // Lakukan soft delete dengan mengisi kolom deleted_at
    //         $this->db->set('deleted_at', date('Y-m-d H:i:s'));
    //         $this->db->where('id_pegawai', $id_pegawai);
    //         $this->db->update('tbl_pegawai');

    //         $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-warning">
    //             <p>Berhasil Hapus (Soft Delete) Data Pegawai!</p>
    //         </div></div>');
    //     } else {
    //         $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
    //             <p>Data Pegawai tidak ditemukan!</p>
    //         </div></div>');
    //     }

    //     redirect(base_url('data/pegawai'));
    // }

    // === HARD DELETE PEGAWAI ===
    if (!empty($this->input->get('id_pegawai'))) {

        $id_pegawai = (int) $this->input->get('id_pegawai', TRUE);

        // Cek apakah data pegawai ada
        $pegawai = $this->M_Admin->get_tableid_edit(
            'tbl_pegawai',
            'id_pegawai',
            $id_pegawai
        );

        if ($pegawai) {

            $this->db->where('id_pegawai', $id_pegawai);
            $this->db->delete('tbl_pegawai');

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('pesan',
                    '<div id="notifikasi"><div class="alert alert-success">
                        <p>Berhasil Hapus (Hard Delete) Data Pegawai!</p>
                    </div></div>'
                );

            } else {

                $this->session->set_flashdata('pesan',
                    '<div id="notifikasi"><div class="alert alert-danger">
                        <p>Gagal menghapus data pegawai!</p>
                    </div></div>'
                );
            }

        } else {

            $this->session->set_flashdata('pesan',
                '<div id="notifikasi"><div class="alert alert-danger">
                    <p>Data Pegawai tidak ditemukan!</p>
                </div></div>'
            );
        }

        redirect(base_url('data/pegawai'));
    }


    // === TAMBAH PEGAWAI ===
    if (!empty($this->input->post('tambah'))) {
        $post = $this->input->post();

        $data = array(
            'nama'         => htmlentities($post['nama']),
            'NIP'          => htmlentities($post['nip']),
            'jabatan'      => htmlentities($post['jabatan']),
            'asal_satker'  => htmlentities($post['asal_satker']),
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
            'deleted_at'   => NULL
        );

        $this->db->insert('tbl_pegawai', $data);

        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
            <p>Tambah Pegawai Sukses!</p>
        </div></div>');
        redirect(base_url('data/pegawai'));
    }

    // === EDIT PEGAWAI ===
    if (!empty($this->input->post('edit'))) {
        $post = $this->input->post();

        $data = array(
            'nama'         => htmlentities($post['nama']),
            'NIP'          => htmlentities($post['nip']),
            'jabatan'      => htmlentities($post['jabatan']),
            'asal_satker'  => htmlentities($post['asal_satker']),
            'updated_at'   => date('Y-m-d H:i:s')
        );

        $this->db->where('id_pegawai', htmlentities($post['edit']));
        $this->db->update('tbl_pegawai', $data);

        $this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
            <p>Edit Pegawai Sukses!</p>
        </div></div>');
        redirect(base_url('data/pegawai'));
    }
}

	public function pegawaitambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$this->data['roles'] =  $this->db->query("SELECT * FROM tbl_role ORDER BY id_role DESC")->result_array();

        $this->data['title_web'] = 'Tambah Pegawai';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('pegawai/tambah_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

		public function pegawaidetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_pegawai','id_pegawai',$this->uri->segment('3'));
		if($count > 0)
		{
			$this->data['pegawai'] = $this->M_Admin->get_tableid_edit('tbl_pegawai','id_pegawai',$this->uri->segment('3'));
			$this->data['roles'] =  $this->db->query("SELECT * FROM tbl_role ORDER BY id_role DESC")->result_array();

		}else{
			echo '<script>alert("PEGAWAI TIDAK DITEMUKAN");window.location="'.base_url('data/pegawai').'"</script>';
		}

		$this->data['title_web'] = 'Data Pegawai Detail';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('pegawai/detail',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function pegawaiedit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_pegawai','id_pegawai',$this->uri->segment('3'));
		if($count > 0)
		{
			
			$this->data['pegawai'] = $this->M_Admin->get_tableid_edit('tbl_pegawai','id_pegawai',$this->uri->segment('3'));

			$this->data['roles'] =  $this->db->query("SELECT * FROM tbl_role ORDER BY id_role DESC")->result_array();

		}else{
			echo '<script>alert("PEGAWAI TIDAK DITEMUKAN");window.location="'.base_url('data/pegawai').'"</script>';
		}

		$this->data['title_web'] = 'Data Pegawai Edit';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('pegawai/edit_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

// Code LDK Pekanbaru Controller Menu Role
	
	public function role()
{
    // Ambil ID user yang login
    $this->data['idbo'] = $this->session->userdata('ses_id');

    // Ambil semua role yang belum dihapus (deleted_at IS NULL)
    $this->data['role'] = $this->db->query("SELECT * FROM tbl_role WHERE deleted_at IS NULL ORDER BY id_role DESC");

    // Cek apakah ada parameter ?id= di URL
    if (!empty($this->input->get('id'))) {
        $id = $this->input->get('id');
        $count = $this->M_Admin->CountTableId('tbl_role', 'id_role', $id);

        if ($count > 0) {
            // Tetap ambil datanya (tanpa filtering deleted_at karena ini konteks pengeditan spesifik)
            $this->data['roles'] = $this->db->query("SELECT * FROM tbl_role WHERE id_role='$id'")->row();
        } else {
            echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="' . base_url('data/role') . '"</script>';
        }
    }

    // Set judul dan load view
    $this->data['title_web'] = 'Data Role ';
    $this->load->view('header_view', $this->data);
    $this->load->view('sidebar_view', $this->data);
    $this->load->view('role/role_view', $this->data);
    $this->load->view('footer_view', $this->data);
}


	public function prosesrole()
	{
	if($this->session->userdata('masuk_perpus') != TRUE){
		redirect(base_url('login'));
	}

	// // hapus aksi form proses role (soft delete)
	// if(!empty($this->input->get('role_id')))
	// {

	// 	     $role = $this->M_Admin->get_tableid_edit(
    //         'tbl_role',
    //         'id_role',
    //         htmlentities($this->input->get('id_role'))
    //     );
	// 	$role_id = htmlentities($this->input->get('role_id'));

	// 	// Soft delete: set deleted_at timestamp
	// 	$this->db->set('deleted_at', date('Y-m-d H:i:s'));
	// 	$this->db->where('id_role', $role_id);
	// 	$this->db->update('tbl_role');

	// 	$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
	// 		<p>Berhasil Hapus Role!</p>
	// 	</div></div>');
	// 	redirect(base_url('data/role'));
	// }

    // === HANDLE DELETE ROLE (HARD DELETE) ===
    if (!empty($this->input->get('role_id'))) {

        $role_id = (int) $this->input->get('role_id', TRUE);

        // Cek apakah data role ada
        $role = $this->M_Admin->get_tableid_edit(
            'tbl_role',
            'id_role',
            $role_id
        );

        if ($role) {

            $this->db->where('id_role', $role_id);
            $this->db->delete('tbl_role');

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata(
                    'pesan',
                    '<div id="notifikasi"><div class="alert alert-success">
                        <p>Berhasil Hapus Role (Hard Delete)!</p>
                    </div></div>'
                );

            } else {

                $this->session->set_flashdata(
                    'pesan',
                    '<div id="notifikasi"><div class="alert alert-danger">
                        <p>Gagal menghapus role!</p>
                    </div></div>'
                );
            }

        } else {

            $this->session->set_flashdata(
                'pesan',
                '<div id="notifikasi"><div class="alert alert-danger">
                    <p>Data Role tidak ditemukan!</p>
                </div></div>'
            );
        }

        redirect(base_url('data/role'));
    }


	// tambah aksi form proses role
	if(!empty($this->input->post('tambah')))
	{
		$post = $this->input->post();
		$data = array(
			'nama_role' => htmlentities($post['nama_role']),
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'deleted_at' => NULL
		);

		$this->db->insert('tbl_role', $data);

		$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p>Tambah Role Sukses!</p>
		</div></div>');
		redirect(base_url('data/role'));
	}

	// edit aksi form proses role
	if(!empty($this->input->post('edit')))
	{
		$post = $this->input->post();
		$data = array(
			'nama_role' => htmlentities($post['nama_role']),
			'updated_at' => date('Y-m-d H:i:s')
		);

		$this->db->where('id_role', htmlentities($post['edit']));
		$this->db->update('tbl_role', $data);

		$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p>Edit Role Sukses!</p>
		</div></div>');
		// redirect(base_url('data/roleedit/'.$post['edit']));

		 // Ganti redirect ke halaman utama data role
    	redirect(base_url('data/role'));
	}
	}

		public function roletambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

        $this->data['title_web'] = 'Tambah Role';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('role/tambah_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

		public function roledetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_role','id_role',$this->uri->segment('3'));
		if($count > 0)
		{
			$this->data['role'] = $this->M_Admin->get_tableid_edit('tbl_role','id_role',$this->uri->segment('3'));

		}else{
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="'.base_url('data').'"</script>';
		}

		$this->data['title_web'] = 'Data Buku Detail';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('role/detail',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function roleedit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_role','id_role',$this->uri->segment('3'));
		if($count > 0)
		{
			
			$this->data['role'] = $this->M_Admin->get_tableid_edit('tbl_role','id_role',$this->uri->segment('3'));

		}else{
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="'.base_url('data').'"</script>';
		}

		$this->data['title_web'] = 'Data Role Edit';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('role/edit_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	// Code LDK Pekanbaru Controller Menu Jenis Pelatihan
	public function jenispelatihan(){
		// $sess = $this->session->userdata('sess_id');
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$data['jenis_pelatihan'] = $this->M_Admin->getJenisPelatihan();

		$this->data['title_web'] = 'Data Jenis Pelatihan';
		$this->load->view('header_view', $this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('jenis_pelatihan/jenis_pelatihan_view', $data);
        $this->load->view('footer_view',$this->data);
	}

	public function tambahJenisPelatihan(){
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$this->form_validation->set_rules('nama_jenis_pelatihan', 'Nama Jenis Pelatihan', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->data['title_web'] = 'Tambah Jenis Pelatihan';
			$this->load->view('header_view',$this->data);
			$this->load->view('sidebar_view',$this->data);
			$this->load->view('jenis_pelatihan/jenis_pelatihan_tambah');
			$this->load->view('footer_view',$this->data);

		} else {
			$nama_jenis_pelatihan = $this->input->post("nama_jenis_pelatihan", TRUE);

			$save = [
				'nama_jenis_pelatihan' => $nama_jenis_pelatihan,
				'deleted_at' => NULL
			];
			

			$this->db->insert('tbl_jenis_pelatihan', $save);
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
				<p>Tambah Jenis Pelatihan Sukses!</p>
			</div></div>');
			redirect(base_url('data/jenispelatihan'));
		}
	}

	public function hapusjenispelatihan($id_jenis_pelatihan) {
		$data = $this->db->get_where('tbl_jenis_pelatihan', ['id_jenis_pelatihan' => $id_jenis_pelatihan])->row_array();
		$this->db->where(['id_jenis_pelatihan'=> $id_jenis_pelatihan]);
		$this->db->delete('tbl_jenis_pelatihan');
		$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p>Hapus Jenis Pelatihan Sukses!</p>
		</div></div>');
		redirect(base_url('data/jenispelatihan'));
	}

	// ========================================================================================================== //

	public function kategori()
	{
		
        $this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['kategori'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC");

		if(!empty($this->input->get('id'))){
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_kategori','id_kategori',$id);
			if($count > 0)
			{			
				$this->data['kat'] = $this->db->query("SELECT *FROM tbl_kategori WHERE id_kategori='$id'")->row();
			}else{
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="'.base_url('data/kategori').'"</script>';
			}
		}

        $this->data['title_web'] = 'Data Kategori ';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('kategori/kat_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function katproses()
	{
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_kategori'=>htmlentities($post['kategori']),
			);

			$this->db->insert('tbl_kategori', $data);

			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah Kategori Sukses !</p>
			</div></div>');
			redirect(base_url('data/kategori'));  
		}

		if(!empty($this->input->post('edit')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_kategori'=>htmlentities($post['kategori']),
			);
			$this->db->where('id_kategori',htmlentities($post['edit']));
			$this->db->update('tbl_kategori', $data);


			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Edit Kategori Sukses !</p>
			</div></div>');
			redirect(base_url('data/kategori')); 		
		}

		if(!empty($this->input->get('kat_id')))
		{
			$this->db->where('id_kategori',$this->input->get('kat_id'));
			$this->db->delete('tbl_kategori');

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Kategori Sukses !</p>
			</div></div>');
			redirect(base_url('data/kategori')); 
		}
	}

	public function rak()
	{
		
        $this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC");

		if(!empty($this->input->get('id'))){
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_rak','id_rak',$id);
			if($count > 0)
			{	
				$this->data['rak'] = $this->db->query("SELECT *FROM tbl_rak WHERE id_rak='$id'")->row();
			}else{
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="'.base_url('data/rak').'"</script>';
			}
		}

        $this->data['title_web'] = 'Data Rak Buku ';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('rak/rak_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function rakproses()
	{
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_rak'=>htmlentities($post['rak']),
			);

			$this->db->insert('tbl_rak', $data);

			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah Rak Buku Sukses !</p>
			</div></div>');
			redirect(base_url('data/rak'));  
		}

		if(!empty($this->input->post('edit')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_rak'=>htmlentities($post['rak']),
			);
			$this->db->where('id_rak',htmlentities($post['edit']));
			$this->db->update('tbl_rak', $data);


			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Edit Rak Sukses !</p>
			</div></div>');
			redirect(base_url('data/rak')); 		
		}

		if(!empty($this->input->get('rak_id')))
		{
			$this->db->where('id_rak',$this->input->get('rak_id'));
			$this->db->delete('tbl_rak');

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Rak Buku Sukses !</p>
			</div></div>');
			redirect(base_url('data/rak')); 
		}
	}



public function bukudetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku','id_buku',$this->uri->segment('3'));
		if($count > 0)
		{
			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku','id_buku',$this->uri->segment('3'));
			$this->data['kats'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
			$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();

		}else{
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="'.base_url('data').'"</script>';
		}

		$this->data['title_web'] = 'Data Buku Detail';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/detail',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function bukuedit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku','id_buku',$this->uri->segment('3'));
		if($count > 0)
		{
			
			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku','id_buku',$this->uri->segment('3'));
	   
			$this->data['kats'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
			$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();

		}else{
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="'.base_url('data').'"</script>';
		}

		$this->data['title_web'] = 'Data Buku Edit';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/edit_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function bukutambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$this->data['kats'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
		$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();


        $this->data['title_web'] = 'Tambah Buku';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/tambah_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function prosesbuku()
	{
		if($this->session->userdata('masuk_perpus') != TRUE){
			$url=base_url('login');
			redirect($url);
		}

		// hapus aksi form proses buku
		if(!empty($this->input->get('buku_id')))
		{
        
			$buku = $this->M_Admin->get_tableid_edit('tbl_buku','id_buku',htmlentities($this->input->get('buku_id')));
			
			$sampul = './assets/image/buku/'.$buku->sampul;
			if(file_exists($sampul))
			{
				unlink($sampul);
			}
			
			$lampiran = './assets/image/buku/'.$buku->lampiran;
			if(file_exists($lampiran))
			{
				unlink($lampiran);
			}
			
			$this->M_Admin->delete_table('tbl_buku','id_buku',$this->input->get('buku_id'));
			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
					<p> Berhasil Hapus Buku !</p>
				</div></div>');
			redirect(base_url('data'));  
		}

		// tambah aksi form proses buku
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			$buku_id = $this->M_Admin->buat_kode('tbl_buku','BK','id_buku','ORDER BY id_buku DESC LIMIT 1'); 
			$data = array(
				'buku_id'=>$buku_id,
				'id_kategori'=>htmlentities($post['kategori']), 
				'id_rak' => htmlentities($post['rak']), 
				'isbn' => htmlentities($post['isbn']), 
				'title'  => htmlentities($post['title']), 
				'pengarang'=> htmlentities($post['pengarang']), 
				'penerbit'=> htmlentities($post['penerbit']),    
				'thn_buku' => htmlentities($post['thn']), 
				'isi' => $this->input->post('ket'), 
				'jml'=> htmlentities($post['jml']),  
				'tgl_masuk' => date('Y-m-d H:i:s')
			);

			$this->load->library('upload',$config);
			if(!empty($_FILES['gambar']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('gambar')) {
					$this->upload->data();
					$file1 = array('upload_data' => $this->upload->data());
					$this->db->set('sampul', $file1['upload_data']['file_name']);
				}else{
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			if(!empty($_FILES['lampiran']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'pdf'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);
				// script uplaod file kedua
				if ($this->upload->do_upload('lampiran')) {
					$this->upload->data();
					$file2 = array('upload_data' => $this->upload->data());
					$this->db->set('lampiran', $file2['upload_data']['file_name']);
				}else{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			$this->db->insert('tbl_buku', $data);

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah Buku Sukses !</p>
			</div></div>');
			redirect(base_url('data')); 
		}

		// edit aksi form proses buku
		if(!empty($this->input->post('edit')))
		{
			$post = $this->input->post();
			$data = array(
				'id_kategori'=>htmlentities($post['kategori']), 
				'id_rak' => htmlentities($post['rak']), 
				'isbn' => htmlentities($post['isbn']), 
				'title'  => htmlentities($post['title']),
				'pengarang'=> htmlentities($post['pengarang']), 
				'penerbit'=> htmlentities($post['penerbit']),  
				'thn_buku' => htmlentities($post['thn']), 
				'isi' => $this->input->post('ket'), 
				'jml'=> htmlentities($post['jml']),  
				'tgl_masuk' => date('Y-m-d H:i:s')
			);

			if(!empty($_FILES['gambar']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('gambar')) {
					$this->upload->data();
					$gambar = './assets/image/buku/'.htmlentities($post['gmbr']);
					if(file_exists($gambar)) {
						unlink($gambar);
					}
					$file1 = array('upload_data' => $this->upload->data());
					$this->db->set('sampul', $file1['upload_data']['file_name']);
				}else{
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			if(!empty($_FILES['lampiran']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'pdf'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);
				// script uplaod file kedua
				if ($this->upload->do_upload('lampiran')) {
					$this->upload->data();
					$lampiran = './assets_style/image/buku/'.htmlentities($post['lamp']);
					if(file_exists($lampiran)) {
						unlink($lampiran);
					}
					$file2 = array('upload_data' => $this->upload->data());
					$this->db->set('lampiran', $file2['upload_data']['file_name']);
				}else{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			$this->db->where('id_buku',htmlentities($post['edit']));
			$this->db->update('tbl_buku', $data);

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Edit Buku Sukses !</p>
				</div></div>');
			redirect(base_url('data/bukuedit/'.$post['edit'])); 
		}
	}

	// Endpoint JSON: daftar peserta berdasarkan id_pelatihan
public function get_peserta_by_pelatihan()
{
    if ($this->session->userdata('masuk_perpus') != TRUE) {
        return $this->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => 'Unauthorized']));
    }

    $id_pelatihan = (int)$this->input->get('id_pelatihan', TRUE);
    if ($id_pelatihan <= 0) {
        return $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => 'id_pelatihan invalid']));
    }

    // Sesuaikan nama tabel & kolom di bawah ini dengan skema Anda
    $rows = $this->db->select('id_peserta, nama_peserta')
        ->from('tbl_peserta_pelatihan')
        ->where('id_pelatihan', $id_pelatihan)
        ->order_by('nama_peserta', 'ASC')
        ->get()->result_array();

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($rows));
}

/**
 * Ambil mapping pengajar per pelatihan dari tabel baru.
 * Return:
 * [
 *   'wi_ids'          => [int, ...],
 *   'wi_names'        => [string, ...],
 *   'wi_rapat_id'     => int|null,
 *   'wi_rapat_name'   => string|null,
 *   'pengajar_ids'    => [int, ...],
 *   'pengajar_names'  => [string, ...],
 * ]
 */
private function _get_pengajar_assignments($id_pelatihan)
{
    $rows = $this->db->select('pp.id_pegawai, pp.tipe_peran, pg.nama')
        ->from('tbl_pelatihan_pengajar pp')
        ->join('tbl_pegawai pg', 'pg.id_pegawai = pp.id_pegawai', 'left')
        ->where('pp.id_pelatihan', (int)$id_pelatihan)
        ->where('pp.deleted_at IS NULL', NULL, FALSE)
        ->get()->result_array();

    $res = [
        'wi_ids'         => [], 'wi_names' => [],
        'wi_rapat_id'    => NULL, 'wi_rapat_name' => NULL,
        'pengajar_ids'   => [], 'pengajar_names' => [],
    ];

    foreach ($rows as $r) {
        $idp   = (int)$r['id_pegawai'];
        $nama  = $r['nama'];
        $tipe  = $r['tipe_peran'];

        if ($tipe === 'Widyaiswara') {
            $res['wi_ids'][$idp] = true;
            if ($nama !== NULL) $res['wi_names'][] = $nama;
        } elseif ($tipe === 'Widyaiswara Rapat Kelulusan') {
            $res['wi_rapat_id']   = $idp;
            $res['wi_rapat_name'] = $nama;
        } elseif ($tipe === 'Pengajar') {
            $res['pengajar_ids'][$idp] = true;
            if ($nama !== NULL) $res['pengajar_names'][] = $nama;
        }
    }

    // konversi set → array indexed
    $res['wi_ids']       = array_map('intval', array_keys($res['wi_ids']));
    $res['pengajar_ids'] = array_map('intval', array_keys($res['pengajar_ids']));
    return $res;
}

// Ambil NIP sebelum tanda "/"; juga hilangkan spasi yang sering terselip sebelum/ sesudah "/"
private function extract_nip_before_slash($raw) {
    if ($raw === null) return '';
    $v = trim($raw);
    if ($v === '') return '';

    // contoh input yang perlu distandarkan:
    // "199708282025052002/ 1404016808970004"  -> ambil kiri sebelum "/"
    // "199404152025052004/ 1403055504945069"
    // "199402262025052001/1471096602940021"
    // jika tidak ada "/", kembalikan apa adanya
    $pos = strpos($v, '/');
    if ($pos === false) {
        return preg_replace('/\s+/', '', $v); // hilangkan spasi di dalam NIP
    }
    $left = substr($v, 0, $pos);
    return preg_replace('/\s+/', '', trim($left));
}

}