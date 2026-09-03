<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
	 parent::__construct();
	 	//validasi jika user belum login
        $this->data['CI'] =& get_instance();
        $this->load->helper(array('form', 'url'));
        $this->load->model('M_login');
        
	 }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->data['title_web'] = 'Login | Sistem Informasi Perpustakaan';
		$this->load->view('login_view',$this->data);
	}

    public function auth()
    {
        $user = $this->input->post('user', TRUE);
        $pass = $this->input->post('pass', TRUE);

        // PERBAIKAN KEAMANAN: cari user berdasarkan username SAJA dulu,
        // baru verifikasi password terpisah. Ini perlu supaya kita bisa
        // mendukung dua format hash sekaligus (migrasi bertahap dari MD5
        // ke bcrypt) tanpa memaksa semua user reset password sekarang.
        $this->db->where('user', $user);
        $proses_login = $this->db->get('tbl_login');

        $password_valid = FALSE;
        $hasil_login = null;

        if ($proses_login->num_rows() > 0) {
            $hasil_login = $proses_login->row_array();
            $stored_hash = $hasil_login['pass'];

            if (password_get_info($stored_hash)['algo'] !== null) {
                // Hash sudah format bcrypt/modern -> verifikasi dengan aman
                $password_valid = password_verify($pass, $stored_hash);
            } else {
                // Hash masih format lama (MD5). Cek dengan cara lama,
                // lalu kalau cocok, upgrade otomatis ke bcrypt supaya
                // ke depannya user ini sudah pakai hash yang aman.
                if (hash_equals($stored_hash, md5($pass))) {
                    $password_valid = TRUE;
                    $new_hash = password_hash($pass, PASSWORD_BCRYPT);
                    $this->db->where('id_login', $hasil_login['id_login']);
                    $this->db->update('tbl_login', ['pass' => $new_hash]);
                }
            }
        }

        if ($password_valid) {
            // Simpan data ke session
            $this->session->set_userdata([
                'masuk_perpus' => TRUE,
                'level'        => $hasil_login['level'],
                'ses_id'       => $hasil_login['id_login'],
                'nama'         => $hasil_login['nama'],
                'anggota_id'   => $hasil_login['anggota_id'],
                'id_login'     => $hasil_login['id_login'] // penting untuk filter pelatihan
            ]);

            redirect('dashboard');
        } else {
            echo '<script>alert("Login gagal, periksa kembali username dan password Anda"); window.location="' . base_url() . '"</script>';
        }
    }


    public function logout()
    {
        $this->session->sess_destroy();
        echo '<script>window.location="'.base_url().'";</script>';
    }
}
