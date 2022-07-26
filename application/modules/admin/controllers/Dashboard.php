<?php defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
class Dashboard extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengajuan_model');
		// echo check merged
	}

	public function index($tahun = null)
	{
		if(!$tahun) {		
			$tahun = date('Y');
		} else {
			$tahun = $tahun;
		}

		$data['selected_year'] = $tahun;

		$prodinya = $this->session->userdata('id_prodi');

		if ($prodinya == 0) {
			$prodi = '';
		} else {
			$prodi = 'AND DEPARTMENT_ID = ' . $prodinya;
		}

		$data['pengajuan_perlu_diproses'] =  $this->db->query("SELECT *, YEAR(date) as tahun 
			FROM v_tr_pengajuan_status
			WHERE status_id = 2 AND YEAR(date) = $tahun " . $prodi )->num_rows();

		$data['verified'] =  $this->db->query("SELECT *, YEAR(date) as tahun 
			FROM v_tr_pengajuan_status
			WHERE status_id = 7 AND YEAR(date) = $tahun " . $prodi )->num_rows();

		$data['prestasi'] = $this->db->query("SELECT * FROM v_prestasi 
			WHERE	status = 1 AND YEAR(tanggal) = $tahun " . $prodi
		)->num_rows();

		$data['nama_bulan'] = $this->pengajuan_model->getbulan();

		$data['jenis_pengajuan'] = $this->db->query(
			"SELECT 
			DISTINCT (Jenis_Pengajuan_Id), Jenis_Pengajuan
			FROM v_prestasi
			WHERE	status = 1 AND YEAR(tanggal) = $tahun " . $prodi. "
			ORDER BY Jenis_Pengajuan_Id ASC
			")->result_array();

		// echo '<pre>'; print_r($data['jenis_pengajuan']); echo '</pre>';exit;
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index';
		$data['menu'] = 'dashboard';
		$this->load->view('layout/layout', $data);
	}

}
