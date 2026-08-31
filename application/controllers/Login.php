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

        // Gunakan query builder (lebih aman dari SQL injection)
        $this->db->where('user', $user);
        $this->db->where('pass', md5($pass)); // pastikan kolom pass disimpan dalam md5
        $proses_login = $this->db->get('tbl_login');

        if ($proses_login->num_rows() > 0) {
            $hasil_login = $proses_login->row_array();

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
