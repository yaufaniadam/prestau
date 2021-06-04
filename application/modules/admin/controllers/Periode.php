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

			$this->db->where('id_periode', $id_periode);
			$this->db->update('Tr_Periode_Penerbitan', $data);

			// echo "<pre>";
			// echo "</pre>";

			// echo "<pre>";
			// print_r($this->input->post());
			// echo "</pre>";
			// die();

			$pengajuan = $this->input->post('pengajuan[]');

			// for ($i = 0; $i < count($pengajuan); $i++) {
			// }
			// $mahasiswa = array_merge($penerima, $pengajuan);
			// foreach ($penerima as $dummy) {
			// }

			$penerima = $this->input->post()['pengajuan_id'];
			$pengajuan = $this->input->post('pengajuan[]');

			for ($i = 0; $i < count($penerima); $i++) {
				$this_pengajuan = $this->db->get_where(
					'Tr_Penerbitan_Pengajuan',
					[
						'STUDENTID' => $penerima[$i],
						'id_periode' => $id_periode,
						'id_pengajuan' => $pengajuan[$i]
					]
				)->row_array();

				$data_for_notif = [
					'pengirim' => $_SESSION['user_id'],
					'penerima' => $penerima[$i],
					'id_pengajuan' => $this_pengajuan['id_pengajuan'],
					'role' => [3],
					'id_status_notif' => 10,
				];

				$this->notif_model->send_notif($data_for_notif);
			}
			// die();

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
			// die();
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

	public function reward($id_prestasi)
	{
		$prestasi = $this->db->get_where('Tr_Penerbitan_Pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])
			->row_object();

		$field_anggota = $this->db->get_where('Tr_Field_Value', [
			'pengajuan_id' => $prestasi->id_pengajuan,
			'field_id' => 77
		]);

		$queryp = $this->db->select('*')
			->from('Tr_Pengajuan p')
			->join('Mstr_Jenis_Pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id', 'left')
			->where([
				'p.pengajuan_id' => $prestasi->id_pengajuan
			])
			->get()
			->row_object();
		$tipe_reward = $queryp->fixed;

		if (($tipe_reward == 1) || ($tipe_reward == 3)) {
			$reward = $this->db->get_where('Mstr_Penghargaan_Rekognisi_Mahasiswa', [
				'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id
			])->row_object()->nominal;
		} elseif ($tipe_reward == 2) {
			if ($field_anggota->num_rows() > 0) {
				$anggota = explode(',', $field_anggota->row_object()->value);
				$urutan = array_search($prestasi->STUDENTID, $anggota);
				$reward = $this->db->get_where('Mstr_Penghargaan_Rekognisi_Mahasiswa', [
					'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
					'order' => $urutan > 0 ? 2 : 1
				])->row_object()->nominal;
			}
		} else {
			$reward = get_meta_value('biaya_pribadi', $prestasi->id_pengajuan, false);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($reward));

		// $query = $this->db->select('*')
		// 	->from('Tr_Penerbitan_Pengajuan pp')
		// 	->join('Tr_Pengajuan p', 'p.pengajuan_id = pp.id_pengajuan')
		// 	->join('Mstr_Jenis_Pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id')
		// 	->join('Mstr_Penghargaan_Rekognisi_Mahasiswa prm', 'prm.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id')
		// 	->where([
		// 		'pp.id_penerbitan_pengajuan' => $id_prestasi
		// 	])
		// 	->get()
		// 	->row_object();
	}

	public function set_nominal()
	{
		$id_prestasi = $this->input->post('id_prestasi');
		$id_periode = $this->db->get_where('Tr_Penerbitan_Pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])->row_object()->id_periode;

		$this->db->set('nominal', $this->input->post('nominal'));
		$this->db->where('id_penerbitan_pengajuan', $id_prestasi);
		$this->db->update('Tr_Penerbitan_Pengajuan');
		redirect(base_url('admin/periode/bulan/' . $id_periode));
	}

	
	public function hapus($id, $id_periode, $id_pengajuan)
	{
		$this->db->delete('Tr_Penerbitan_Pengajuan', array('id_penerbitan_pengajuan' => $id));
		$this->session->set_flashdata('msg', 'Data berhasil dihapus!');

		$this->db->delete('Tr_Pengajuan_Status', array('pengajuan_id' => $id_pengajuan, 'status_id'=> 9 ));
		redirect(base_url('admin/periode/bulan/' . $id_periode));
	}

}
