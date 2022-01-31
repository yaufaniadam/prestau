<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Style\Alignment; 

class Periode extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('periode_model', 'periode_model');
		$this->load->model('pengajuan_model', 'pengajuan_model');
		$this->load->model('notif/Notif_model', 'notif_model');
		$this->load->library('excel');
		$this->load->library('mailer');
	}

	public function index($status = '')
	{
		$data['daftar_periode'] = $this->periode_model->getPeriode($status);
		$data['title'] = 'Periode Penerbitan';
		$data['view'] = 'admin/periode/index';
		$data['menu'] = 'periode';
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
				$data['menu'] = 'periode';
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
			$data['menu'] = 'periode';
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
			$this->db->update('tr_periode_penerbitan', $data);

			$pengajuan = $this->input->post('pengajuan[]');

			$penerima = $this->input->post(['pengajuan_id']);
			$pengajuan = $this->input->post('pengajuan[]');

			for ($i = 0; $i < count($penerima); $i++) {
				$this_pengajuan = $this->db->get_where(
					'tr_penerbitan_pengajuan',
					[
						'STUDENTID' => $penerima[$i],
						'id_periode' => $id_periode,
						'id_pengajuan' => $pengajuan[$i]
					]
				)->row_array();

					//data utk kirim email & notif ke pegawai
					$data_for_notif = [
						'STUDENTID' => $penerima[$i],
						'STUDENTNAME' => $penerima[$i],
						'penerima' => '',
						'id_pengajuan' => $pengajuan[$i],
						'judul_pengajuan' => $data['title'],
						'role' => [3],
						'link' => base_url('admin/pengajuan/detail/'. $pengajuan[$i]),
						'subjek' => 'Ada Pengajuan Prestasi Baru dari ' . $pengajuan[$i],
						'isi' => 'Ada Pengajuan Prestasi Baru dari <strong>' . $pengajuan[$i] . '</strong> kategori <strong>' . $data['title'] . '</strong> yang perlu diperiksa.',
						'id_status_notif' => 10,
					];
	
					//sendmail & notif
					$this->mailer->send_mail($data_for_notif);			

			}
			// die();

			$this->db->select('id_pengajuan');
			$this->db->distinct();
			$this->db->from('tr_penerbitan_pengajuan');
			$this->db->where(['id_periode' => $id_periode]);
			$this->db->group_by('id_pengajuan');
			$pengajuan = $this->db->get()->result_array();

			foreach ($pengajuan as $pengajuan) {
				$this->db->set('status_id', 10)
					->set('pic', $this->session->userdata('user_id'))
					->set('date', 'getdate()', FALSE)
					->set('pengajuan_id', $pengajuan['id_pengajuan'])
					->insert('tr_pengajuan_status');
			}


			redirect(base_url('/admin/periode/bulan/' . $id_periode));
		} else {
			// die();
			$nama_periode = $this->db->get_where('tr_periode_penerbitan', ['id_periode' => $id_periode])->row_object()->nama_periode;
			$status_periode = $this->db->get_where('tr_periode_penerbitan', ['id_periode' => $id_periode])->row_object()->status;
			$data['daftar_pengajuan'] = $this->pengajuan_model->getPengajuanPerPeriode($id_periode);
			$data['title'] = 'Daftar Pengajuan Periode ' . $nama_periode;
			$data['status_periode'] = $status_periode;
			$data['id_periode'] = $id_periode;
			$data['view'] = 'admin/penerbitan_pengajuan/index';

			$this->load->view('layout/layout', $data);
		}
	}

public function export_excel($id_periode = 0)
{
	$nama_periode = $this->db->get_where('tr_periode_penerbitan', ['id_periode' => $id_periode])->row_object()->nama_periode;
	$status_periode = $this->db->get_where('tr_periode_penerbitan', ['id_periode' => $id_periode])->row_object()->status;

	$daftar_pengajuan = $this->pengajuan_model->getPengajuanPerPeriode($id_periode);


	foreach ($daftar_pengajuan as $pengajuan) {
		$new_daftar_pengajuan[] = [
			'judul_kegiatan' => get_meta_value('judul', $pengajuan['id_pengajuan'], false),
			'jenis_pengajuan' => $pengajuan['Jenis_Pengajuan'],
			'penerima' => $pengajuan['FULLNAME'],
			'nim' => $pengajuan['STUDENTID'],
			'prodi' => $pengajuan['NAME_OF_DEPARTMENT'],
			'nominal' => $pengajuan['nominal'],
			'file' => $this->ngambil_field_file($pengajuan['id_pengajuan'])
		];
	}
	// echo "<pre>";
	// print_r($new_daftar_pengajuan);
	// echo "</pre>";
	// die();

	$data['nama_periode'] = $nama_periode;
	$data['pengajuan'] = $new_daftar_pengajuan;
	$data['title'] = 'Daftar Pengajuan Periode ' . $nama_periode;
	$data['view'] = 'admin/penerbitan_pengajuan/cetak';
	$this->load->view('admin/penerbitan_pengajuan/cetak', $data);
}


	public function reward($id_prestasi)
	{
		$prestasi = $this->db->get_where('tr_penerbitan_pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])
			->row_object();

		$field_anggota = $this->db->get_where('tr_field_value', [
			'pengajuan_id' => $prestasi->id_pengajuan,
			'field_id' => 77
		]);

		$queryp = $this->db->select('*')
			->from('tr_pengajuan p')
			->join('mstr_jenis_pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id', 'left')
			->where([
				'p.pengajuan_id' => $prestasi->id_pengajuan
			])
			->get()
			->row_object();
		$tipe_reward = $queryp->fixed;

		if (($tipe_reward == 1) || ($tipe_reward == 3)) {
			$reward = $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa', [
				'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id
			])->row_object()->nominal;
		} elseif ($tipe_reward == 2) {
			if ($field_anggota->num_rows() > 0) {
				$anggota = explode(',', $field_anggota->row_object()->value);
				$urutan = array_search($prestasi->STUDENTID, $anggota);
				$reward = $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa', [
					'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
					'urutan' => $urutan > 0 ? 2 : 1
				])->row_object()->nominal;
			}
		} else {
			$reward = get_meta_value('biaya_pribadi', $prestasi->id_pengajuan, false);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($reward));

		// $query = $this->db->select('*')
		// 	->from('tr_penerbitan_pengajuan pp')
		// 	->join('tr_pengajuan p', 'p.pengajuan_id = pp.id_pengajuan')
		// 	->join('mstr_jenis_pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id')
		// 	->join('mstr_penghargaan_rekognisi_mahasiswa prm', 'prm.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id')
		// 	->where([
		// 		'pp.id_penerbitan_pengajuan' => $id_prestasi
		// 	])
		// 	->get()
		// 	->row_object();
	}

	public function set_nominal()
	{
		$id_prestasi = $this->input->post('id_prestasi');
		$id_periode = $this->db->get_where('tr_penerbitan_pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])->row_object()->id_periode;

		$this->db->set('nominal', $this->input->post('nominal'));
		$this->db->where('id_penerbitan_pengajuan', $id_prestasi);
		$this->db->update('tr_penerbitan_pengajuan');
		redirect(base_url('admin/periode/bulan/' . $id_periode));
	}

	public function hapus()
	{
		if ($this->input->post('command') == "DELETE") {
			$id_penerbitan_pengajuan = $this->input->post('id_penerbitan_pengajuan');
			$id_pengajuan = $this->input->post("id_pengajuan");
			$id_periode = $this->input->post("id_periode");

			$this->db->delete('tr_penerbitan_pengajuan', array('id_penerbitan_pengajuan' => $id_penerbitan_pengajuan));
			$this->db->delete('tr_pengajuan_status', array('pengajuan_id' => $id_pengajuan, 'status_id' => 9));

			$this->session->set_flashdata('msg', 'Data berhasil dihapus!');
			redirect(base_url('admin/periode/bulan/' . $id_periode));
		}

		
	}

	public function pencairan_reward()
	{
		date_default_timezone_set('Asia/Jakarta');

		$id_penerbitan_pengajuan = $this->input->post("id_penerbitan_pengajuan");
		$pegawai = $this->input->post("pegawai");
		$penerima = $this->input->post("penerima");

		$this->db->set([
			'pegawai' => $pegawai,
			'penerima' => $penerima,
			'status_pencairan' => 1,
			'tanggal_pencairan' => date("Y/m/d")
		]);
		$this->db->where('id_penerbitan_pengajuan', $id_penerbitan_pengajuan);
		$this->db->update('tr_penerbitan_pengajuan');
		redirect(base_url('admin/periode/bulan/' . $this->input->post('id_periode')));
	}
	
	public function ngambil_field_file($pengajuan_id)
	{
		$data = $this->db->select('*')->from("tr_pengajuan p")
			->join("mstr_jenis_pengajuan jp", "jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id", "left")
			->join("mstr_pengajuan_field pf", "pf.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id", "left")
			->join("mstr_fields mf", "mf.field_id=pf.field_id", "left")
			// ->join("tr_field_value fv", "fv.field_id=pf.field_id", "left")
			->where([
				"mf.type" => "file",
				"p.pengajuan_id" => $pengajuan_id
			])->get()
			->result_array();
		
			$files= array();
		foreach ($data as $data) {
			$files[] = get_meta_value($data['key'], $data['pengajuan_id'], true);
		}
		return $files;
		// print_r($data);
	}


}


