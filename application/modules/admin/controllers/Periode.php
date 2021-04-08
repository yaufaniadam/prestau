<?php defined('BASEPATH') or exit('No direct script access allowed');

class Periode extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('periode_model', 'periode_model');
		$this->load->model('pengajuan_model', 'pengajuan_model');
		$this->load->model('notif/Notif_model', 'notif_model');
	}

	public function index($status = '')
	{
		$data['daftar_periode'] = $this->periode_model->getPeriode($status);
		$data['title'] = 'Periode Penerbitan';
		$data['view'] = 'admin/periode/index';

		$this->load->view('layout/layout', $data);
	}

	public function tambah()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules(
				'nama_periode',
				'Nama Periode',
				'trim|required',
				[
					'required' => '%s Wajib Diisi'
				]
			);

			if ($this->form_validation->run() == FALSE) {
				$data['title'] = 'Tambah Periode';
				$data['view'] = 'admin/periode/tambah';
				$this->load->view('layout/layout', $data);
			} else {
				$data = [
					'nama_periode' => $this->input->post('nama_periode'),
					'tanggal' => '',
					'status' => '0'
				];
				if ($this->periode_model->tambahPeriode($data)) {
					redirect(base_url('admin/periode/index/0'));
				}
			}
		} else {
			$data['title'] = 'Tambah Periode';
			$data['view'] = 'admin/periode/tambah';
			$this->load->view('layout/layout', $data);
		}
	}

	public function tambah_nominal($id_periode)
	{
		echo "tambah nomina";
	}

	public function bulan($id_periode = 0)
	{
		if ($this->input->post('submit')) {

			date_default_timezone_set('Asia/Jakarta');
			$tanggal = date("Y/m/d h:i:s");
			$id_periode = $this->input->post('id_periode');
			$data = [
				'tanggal' => $tanggal,
				'status' => 1
			];

			// $this->db->where('id_periode', $id_periode);
			// $this->db->update('Tr_Periode_Penerbitan', $data);

			$mahasiswa = $this->input->post()['pengajuan_id'];
			foreach ($mahasiswa as $penerima) {
				$this_pengajuan = $this->db->get_where('Tr_Penerbitan_Pengajuan', ['STUDENTID' => $penerima, 'id_periode' => $id_periode])->row_array();

				$data_for_notif = [
					'pengirim' => $_SESSION['user_id'],
					'penerima' => $penerima,
					'id_pengajuan' => $this_pengajuan['id_pengajuan'],
					'role' => [3],
					'id_status_notif' => 10,
				];

				// echo "<pre>";
				// print_r($data_for_notif);
				// echo "</pre>";
				// die();

				$this->notif_model->send_notif($data_for_notif);
			}

			$this->db->select('id_pengajuan');
			$this->db->distinct();
			$this->db->from('Tr_Penerbitan_Pengajuan');
			$this->db->where(['id_periode' => $id_periode]);
			$this->db->group_by('id_pengajuan');
			$pengajuan = $this->db->get()->result_array();

			foreach ($pengajuan as $pengajuan) {
				$this->db->set('status_id', 10)
					->set('pic', $this->session->userdata('user_id'))
					->set('date', 'getdate()', FALSE)
					->set('pengajuan_id', $pengajuan['id_pengajuan'])
					->insert('Tr_Pengajuan_Status');
			}

			redirect(base_url('/admin/periode/bulan/' . $id_periode));
		} else {
			$nama_periode = $this->db->get_where('Tr_Periode_Penerbitan', ['id_periode' => $id_periode])->row_object()->nama_periode;
			$status_periode = $this->db->get_where('Tr_Periode_Penerbitan', ['id_periode' => $id_periode])->row_object()->status;
			$data['daftar_pengajuan'] = $this->pengajuan_model->getPengajuanPerPeriode($id_periode);
			$data['title'] = 'Daftar Pengajuan Periode ' . $nama_periode;
			$data['status_periode'] = $status_periode;
			$data['id_periode'] = $id_periode;
			$data['view'] = 'admin/penerbitan_pengajuan/index';

			$this->load->view('layout/layout', $data);
		}
	}
}
