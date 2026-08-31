<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->data['CI'] =& get_instance();
		$this->load->helper(array('form', 'url'));
		$this->load->model('M_Admin');

		if($this->session->userdata('masuk_perpus') != TRUE){
			redirect(base_url('login'));
		}
	}

	public function index()
	{	
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['title_web'] = 'Dashboard';

		// Query Count
		$this->data['count_pelatihan'] = $this->db->where('deleted_at', NULL)->count_all_results('tbl_pelatihan');
		$this->data['count_pelatihan_pjj'] = $this->db->where('deleted_at', NULL)->where('id_jenis_pelatihan', 1)->count_all_results('tbl_pelatihan');
		$this->data['count_pelatihan_pdwk'] = $this->db->where('deleted_at', NULL)->where('id_jenis_pelatihan', 2)->count_all_results('tbl_pelatihan');
		$this->data['count_pelatihan_latsar'] = $this->db->where('deleted_at', NULL)->where('id_jenis_pelatihan', 3)->count_all_results('tbl_pelatihan');
		// $this->data['count_pjj'] = $this->db->where('deleted_at', NULL)
        //                            ->where('jenis_pelatihan', 'PJJ')
        //                            ->count_all_results('tbl_pelatihan');
		// $this->data['count_peserta']   = $this->db->select_sum('jumlah_peserta')->get_where('tbl_detail_pelatihan', ['deleted_at' => NULL])->row()->jumlah_peserta;
		// $this->data['count_pegawai']   = $this->db->where('deleted_at', NULL)->count_all_results('tbl_pegawai');

		// Load Views
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('dashboard_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}
}
