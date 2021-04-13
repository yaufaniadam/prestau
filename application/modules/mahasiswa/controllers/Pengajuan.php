<?php defined('BASEPATH') or exit('No direct script access allowed');
class Pengajuan extends Mahasiswa_Controller
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->library('mailer');
		$this->load->model('pengajuan_model', 'pengajuan_model');
		$this->load->model('notif/Notif_model', 'notif_model');
		$this->load->helper('formulir');
	}

	public function detail($id_surat = 0)
	{
		// $data['surat'] = $this->pengajuan_model->get_detail_surat($id_surat);
		// $data['title'] = $data['surat']['id_mahasiswa'];
		// $data['view'] = 'surat/detail';
		// $this->load->view('layout/layout', $data);
	}

	public function pengajuan_saya()
	{
		$nim = $_SESSION['studentid'];
		$data['title'] = 'Pengajuan Saya';
		$data['view'] = 'pengajuan/pengajuan_saya';
		$data['query'] = $this->db->query(
			"SELECT 
			p.*,
			jp.Jenis_Pengajuan,
			m.FULLNAME,
			m.NAME_OF_FACULTY,
			m.DEPARTMENT_ID,
			ps.pic,
			ps.status_id,
			ps.date,
			s.status,
			s.status_id,
			s.badge,
			FORMAT (ps.date, 'dd/MM/yyyy ') as date,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM Tr_Pengajuan p 
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON p.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id
			LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			WHERE p.nim = $nim
			AND ps.status_pengajuan_id = (SELECT MAX(status_pengajuan_id) 
													FROM Tr_Pengajuan_Status  
													WHERE pengajuan_id = p.pengajuan_id)"
		)->result_array();

		// print_r($data);
		// die();

		$this->load->view('layout/layout', $data);
	}

	public function prestasi_saya()
	{
		$data['title'] = 'Prestasi Saya';
		$data['view'] = 'pengajuan/prestasi_saya';
		$user_nim = $_SESSION['studentid'];

		$data['prestasi'] =
			$this->db->select('*')
			->from('Tr_Penerbitan_Pengajuan pp')
			->join('Tr_Pengajuan p', 'pp.id_pengajuan = p.pengajuan_id', 'left')
			->join('Mstr_Jenis_Pengajuan jp', 'p.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id')
			->join('V_Mahasiswa m', 'm.STUDENTID = pp.STUDENTID')
			->join('Tr_Periode_Penerbitan per', 'per.id_periode = pp.id_periode')
			->where(['pp.STUDENTID' => $user_nim])
			->get()->result_array();

		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		// die();

		$this->load->view('layout/layout', $data);
	}

	public function detail_prestasi($id_penerbitan_pengajuan = 0)
	{
		$data['view'] = 'pengajuan/detail_prestasi';
		$data['title'] = 'Detail Prestasi';

		$query = $this->db->select('*')
			->from('Tr_Penerbitan_Pengajuan pp')
			->join('V_Mahasiswa m', 'm.STUDENTID = pp.STUDENTID')
			->join('Tr_Pengajuan p', 'p.pengajuan_id = pp.id_pengajuan')
			->join('Mstr_Jenis_Pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id')
			->where(
				[
					'pp.id_penerbitan_pengajuan' => $id_penerbitan_pengajuan
				]
			)
			->get()
			->row_array();

		// echo "<pre>";
		// print_r($query);
		// echo "</pre>";
		// die();

		$data['pengajuan'] = $query;

		$this->load->view('layout/layout', $data);
	}

	public function index($id_jenis_pengajuan = 0)
	{
		if ($id_jenis_pengajuan == 0) {
			// $data['jenis_pengajuan'] = $this->pengajuan_model->get_jenis_pengajuan($id_jenis_pengajuan);
			$data['rekognisi'] = $this->pengajuan_model->rekognisi();
			$data['jenis_pengajuan'] = $this->db->query(
				"SELECT * FROM Mstr_Jenis_Pengajuan WHERE parent IS NULL"
			)->result_array();
			$data['title'] = 'Ajukan Prestasi';
			$data['all'] = true;
			$data['view'] = 'pengajuan/index';
		} else {
			$data['jenis_pengajuan'] = $this->pengajuan_model->get_jenis_pengajuan($id_jenis_pengajuan);
			$data['title'] = 'Ajukan Prestasi';
			$data['all'] = false;
			$data['view'] = 'pengajuan/index';
		}
		$this->load->view('layout/layout', $data);
	}

	public function baru($id)
	{
		$data = array(
			'Jenis_Pengajuan_Id' => $id,
			'nim' => $this->session->userdata('studentid'),
		);

		$field = $this->db->select('*')->from('Tr_Pengajuan_Field')
			->join('Mstr_Fields', 'Mstr_fields.field_id=Tr_Pengajuan_Field.field_id', 'left')
			->where(array('Jenis_Pengajuan_Id' => $id))->get()
			->result_array();

		$data = $this->security->xss_clean($data);
		$result = $this->pengajuan_model->tambah($data);
		//ambil last id surat yg baru diinsert
		$insert_id = $this->db->insert_id();
		// set status surat
		$data_user = $this->session->userdata('user_id');
		$this->db->set('pengajuan_id', $insert_id)
			->set('status_id', 1)
			->set('pic', $data_user['STUDENTID'])
			->set('date', date('Y-m-d h:m:s'))
			->insert('Tr_Pengajuan_Status');

		// //ambil id surat berdasarkan last id status surat
		$inserted_id = $this->db->select('pengajuan_id')->from('Tr_Pengajuan_Status')->where('status_pengajuan_id=', $this->db->insert_id())->get()->row_array();
		// // ambil keterangan surat berdasar kategori surat
		// $kat_surat = $this->db->select('kat_keterangan_surat')->from('kategori_surat')->where('id=', $id)->get()->row_array();
		$field_id = $this->db->query(
			"SELECT Tr_Pengajuan_Field.field_id  FROM Mstr_Jenis_Pengajuan
			LEFT JOIN Tr_Pengajuan_Field ON Tr_Pengajuan_Field.Jenis_Pengajuan_Id = Mstr_Jenis_Pengajuan.Jenis_Pengajuan_Id
			WHERE Mstr_Jenis_Pengajuan.Jenis_Pengajuan_Id = $id AND Tr_Pengajuan_Field.terpakai = 1"
		)->result_array();

		// // explode kterangan surat

		// // foreach keterangan surat, lalu masukkan nilai awal (nilai kosong) berdasakan keterangan dari kategori surat kedalam field_value
		foreach ($field_id as $key => $id_kat) {
			$this->db->insert(
				'Tr_Field_Value',
				array(
					'value' => '',
					'pengajuan_id' =>  $inserted_id['pengajuan_id'],
					'field_id' => $id_kat['field_id'],
				)
			);
		}

		// foreach ($field_id as $ad) {
		// 	print_r($ad['field_id']);
		// }

		// $data_notif = array(
		// 	'id_surat' => $insert_id2['id_surat'],
		// 	'id_status' => 1,
		// 	'kepada' => $_SESSION['user_id'],
		// 	'role' => array(3)
		// );

		// $results = $this->notif_model->send_notif($data_notif);

		// if ($results) {
		// 	$this->session->set_flashdata('msg', 'Berhasil!');
		redirect(base_url('mahasiswa/pengajuan/tambah/' . $insert_id));
		// }
	}

	public function getAnggota()
	{
		$search = $this->input->post('search');
		$result_anggota = $this->pengajuan_model->getAnggota($search);

		foreach ($result_anggota as $anggota) {
			$selectajax[] = [
				'value' => $anggota['STUDENTID'],
				'id' => $anggota['STUDENTID'],
				'text' => $anggota['FULLNAME'] . " (" . $anggota['STUDENTID'] . ")"
			];
			$this->output->set_content_type('application/json')->set_output(json_encode($selectajax));
		}
	}

	public function getPengajuanSaya($id_jenis_pengajuan = 0)
	{
		// $search = $this->input->post('search');
		$result_anggota = $this->pengajuan_model->getPengajuanSaya($id_jenis_pengajuan);

		if (count($result_anggota) > 0) {
			foreach ($result_anggota as $anggota) {
				$selectajax[] = [
					'pengajuan_id' => $anggota['pengajuan_id'],
					'judul_karya' =>
					"<a href='"
						. base_url('mahasiswa/pengajuan/tambah/'
							. $anggota['pengajuan_id'])
						. "'>"
						. get_meta_value('judul', $anggota['pengajuan_id'], false)
						. "<br>"
						. $anggota['Jenis_Pengajuan']
						. "</a>",
					'Jenis_Pengajuan_Id' => $anggota['Jenis_Pengajuan_Id'],
					'nim' => $anggota['nim'],
					'Jenis_Pengajuan' => $anggota['Jenis_Pengajuan'],
					'FULLNAME' => $anggota['FULLNAME'],
					'NAME_OF_FACULTY' => $anggota['NAME_OF_FACULTY'],
					'DEPARTMENT_ID' => $anggota['DEPARTMENT_ID'],
					'pic' => $anggota['pic'],
					'status_id' => $anggota['status_id'],
					'date' => $anggota['date'],
					'status' => $anggota['status'],
					'badge' => $anggota['badge'],
					'time' => $anggota['time'],
				];
				$this->output->set_content_type('application/json')->set_output(json_encode($selectajax));
			}
		} else {
			$selectajax[] = [
				'pengajuan_id' => "data kosong",
				'judul_karya' => "data kosong",
				'Jenis_Pengajuan_Id' => "data kosong",
				'nim' => "data kosong",
				'Jenis_Pengajuan' => "data kosong",
				'FULLNAME' => "data kosong",
				'NAME_OF_FACULTY' => "data kosong",
				'DEPARTMENT_ID' => "data kosong",
				'pic' => "data kosong",
				'status_id' => "data kosong",
				'date' => "data kosong",
				'status' => "data kosong",
				'badge' => "data kosong",
				'time' => "data kosong",
			];
			$this->output->set_content_type('application/json')->set_output(json_encode($selectajax));
		}
	}

	public function getPrestasiSaya()
	{
		$query = $this->pengajuan_model->getPrestasiSaya();

		foreach ($query as $pengajuan) {
			$prestasi[] = $this->pengajuan_model->detailPrestasi($pengajuan);
		}

		if (count($prestasi) > 0) {
			foreach ($prestasi as $prestasi) {
				// echo "<pre>";
				// print_r($prestasi);
				// echo "</pre>";

				$selectajax[] = [
					'pengajuan_id' => $prestasi['pengajuan_id'],
					'Jenis_Pengajuan' => $prestasi['Jenis_Pengajuan'],
					// 'judul_karya' =>
					// "<a href='"
					// 	. base_url('mahasiswa/pengajuan/tambah/'
					// 		. $prestasi['pengajuan_id'])
					// 	. "'>"
					// 	. get_meta_value('judul', $prestasi['pengajuan_id'], false)
					// 	. "<br>"
					// . $prestasi['Jenis_Pengajuan']
					// 	. "asdf"
					// 	. "</a>",	
					'Jenis_Pengajuan_Id' => $prestasi['Jenis_Pengajuan_Id'],
					'nim' => $prestasi['nim'],
					'FULLNAME' => $prestasi['FULLNAME'],
					'NAME_OF_FACULTY' => $prestasi['NAME_OF_FACULTY'],
					'DEPARTMENT_ID' => $prestasi['DEPARTMENT_ID'],
					'pic' => $prestasi['pic'],
					'status_id' => $prestasi['status_id'],
					'date' => $prestasi['date'],
					'status' => $prestasi['status'],
					'badge' => $prestasi['badge'],
					// 'time' => $prestasi['time'],
				];
				$this->output->set_content_type('application/json')->set_output(json_encode($selectajax));
			}
		}

		// $search = $this->input->post('search');
		// $result_anggota = $this->pengajuan_model->getPengajuanSaya($id_jenis_pengajuan);

		// if (count($result_anggota) > 0) {
		// 	foreach ($result_anggota as $anggota) {
		// $selectajax[] = [
		// 	'pengajuan_id' => $anggota['pengajuan_id'],
		// 	'judul_karya' =>
		// 	"<a href='"
		// 		. base_url('mahasiswa/pengajuan/tambah/'
		// 			. $anggota['pengajuan_id'])
		// 		. "'>"
		// 		. get_meta_value('judul', $anggota['pengajuan_id'], false)
		// 		. "<br>"
		// 		. $anggota['Jenis_Pengajuan']
		// 		. "</a>",
		// 	'Jenis_Pengajuan_Id' => $anggota['Jenis_Pengajuan_Id'],
		// 	'nim' => $anggota['nim'],
		// 	'Jenis_Pengajuan' => $anggota['Jenis_Pengajuan'],
		// 	'FULLNAME' => $anggota['FULLNAME'],
		// 	'NAME_OF_FACULTY' => $anggota['NAME_OF_FACULTY'],
		// 	'DEPARTMENT_ID' => $anggota['DEPARTMENT_ID'],
		// 	'pic' => $anggota['pic'],
		// 	'status_id' => $anggota['status_id'],
		// 	'date' => $anggota['date'],
		// 	'status' => $anggota['status'],
		// 	'badge' => $anggota['badge'],
		// 	'time' => $anggota['time'],
		// ];
		// $this->output->set_content_type('application/json')->set_output(json_encode($selectajax));
		// 	}
		// } else {
		// 	$selectajax[] = [
		// 		'pengajuan_id' => "data kosong",
		// 		'judul_karya' => "data kosong",
		// 		'Jenis_Pengajuan_Id' => "data kosong",
		// 		'nim' => "data kosong",
		// 		'Jenis_Pengajuan' => "data kosong",
		// 		'FULLNAME' => "data kosong",
		// 		'NAME_OF_FACULTY' => "data kosong",
		// 		'DEPARTMENT_ID' => "data kosong",
		// 		'pic' => "data kosong",
		// 		'status_id' => "data kosong",
		// 		'date' => "data kosong",
		// 		'status' => "data kosong",
		// 		'badge' => "data kosong",
		// 		'time' => "data kosong",
		// 	];
		// 	$this->output->set_content_type('application/json')->set_output(json_encode($selectajax));
		// }
	}

	public function detail_prestasi_saya($id)
	{
		echo $id;
	}

	public function getPembimbing()
	{
		$search = $this->input->post('search');
		$result_anggota = $this->pengajuan_model->getPembimbing($search);

		foreach ($result_anggota as $anggota) {
			$selectajax[] = [
				'value' => $anggota['id_pegawai'],
				'id' => $anggota['id_pegawai'],
				'text' => $anggota['nama']
			];
			$this->output->set_content_type('application/json')->set_output(json_encode($selectajax));
		}
	}

	private function getNamaField($id_field)
	{
		$this->db->select('*');
		$this->db->from('Mstr_Fields');
		$this->db->where('field_id', $id_field);
		$result = $this->db->get()->row_array();
		return $result['field'];
	}

	public function tambah($pengajuan_id = 0)
	{
		$id_notif = $this->input->post('id_notif');

		$this->load->helper('formulir');
		$pengajuan_id;

		$pengajuan = $this->pengajuan_model->get_detail_pengajuan($pengajuan_id);
		$data['pengajuan'] = $pengajuan;

		$pengajuan_fields = $this->db->query(
			"SELECT * FROM Tr_Pengajuan_Field pf
			LEFT JOIN Mstr_Fields f ON f.field_id = pf.field_id
			WHERE pf.Jenis_Pengajuan_Id = $pengajuan->Jenis_Pengajuan_Id
			AND pf.terpakai = 1
			ORDER BY urutan ASC"
		)->result_array();

		$data['timeline'] = $this->db->query(
			"SELECT 
			*,
			FORMAT (ps.date, 'dd/MM') as date,
			FORMAT (ps.date, 'hh:mm:ss') as time 
			FROM Tr_Pengajuan_Status ps
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			WHERE ps.pengajuan_id = $pengajuan->pengajuan_id
			ORDER BY status_pengajuan_id DESC"
		)->result_array();

		// $data['pengajuan'] = $this->pengajuan_model->get_detail_pengajuan($pengajuan_id);
		$data['pengajuan_fields'] = $pengajuan_fields;
		$data['pengajuan_status'] = $pengajuan->status_id;
		$data['pengajuan_id'] = $pengajuan->pengajuan_id;
		$data['title'] =  $pengajuan->jenis_pengajuan;

		if ($this->input->post("submit")) {

			$id_status =	$this->input->post('status');

			if ($id_status == 1) {
				$next_status = 2;
			} elseif ($id_status == 4) {
				$next_status = 5;
			}

			// echo $id_status;
			// echo $next_status;

			$data_user = $this->session->userdata('user_id');

			foreach ($pengajuan_fields as $pengajuan_field) {
				$this->form_validation->set_rules(
					'dokumen[' . $pengajuan_field['field_id'] . ']',
					$this->getNamaField($pengajuan_field['field_id']),
					'trim|required',
					[
						'required' => '%s wajib diisi',
					]
				);
			}

			if ($this->form_validation->run() == false) {
				$data['pengajuan_fields'] = $pengajuan_fields;
				$data['pengajuan_status'] = $pengajuan->status_id;
				$data['pengajuan_id'] = $pengajuan->pengajuan_id;
				$data['title'] =  $pengajuan->jenis_pengajuan;
				$data['view'] = 'pengajuan/tambah';
				$this->load->view('layout/layout', $data);
			} else {

				if ($id_status == 1 || $id_status == 4) {
					$insert = $this->db->set('pengajuan_id', $pengajuan_id)
						->set('status_id', $next_status)
						->set('pic', $data_user['STUDENTID'])
						->set('date', date('Y-m-d h:m:s'))
						->insert('Tr_Pengajuan_Status');
				}

				foreach ($this->input->post('dokumen') as $id => $dokumen) {
					if (is_array($dokumen)) {
						$anggota = implode(",", $dokumen);
						$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id));
						$this->db->update(
							'Tr_Field_Value',
							array(
								'value' => $anggota
							)
						);
					} else {
						$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id));
						$this->db->update(
							'Tr_Field_Value',
							array(
								'value' => $dokumen
							)
						);
					}
				}

				$data_for_notif = [
					'pengirim' => '',
					'penerima' => '',
					'id_pengajuan' => $pengajuan_id,
					'role' => [2],
					'id_status_notif' => 3,
				];

				$this->notif_model->send_notif($data_for_notif);

				redirect(base_url('mahasiswa/pengajuan/tambah/' . $pengajuan_id));
			}
		} else {
			$data['view'] = 'pengajuan/tambah';
			$this->load->view('layout/layout', $data);
		}
	}

	public function doupload()
	{
		header('Content-type:application/json;charset=utf-8');
		$upload_path = 'uploads/dokumen';

		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0777, TRUE);
		}

		$config = array(
			'upload_path' => $upload_path,
			'allowed_types' => "jpg|png|pdf|docx",
			'overwrite' => FALSE,
		);

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			$error = array('error' => $this->upload->display_errors());

			echo json_encode(
				[
					'status' => 'error',
					'message' => $error
				]
			);
		} else {
			$data = $this->upload->data();

			$filename = $this->upload->data('file_ext');

			// // cek apakah gambar atau bukan

			if (($filename === '.jpg') || ($filename === '.jpeg') || ($filename === '.png') || ($filename === '.gif')) {

				// 	//$filename = 'gambar';
				// 	// jka gambar, buatkan thumbnail
				$this->_create_thumbs($data['file_name']);

				// $result = 
				$this->db->insert(
					'Tr_Media',
					array(
						'nim' => $this->session->userdata('studentid'),
						'file' =>  $upload_path . '/' . $data['file_name'],
						//	'file' =>  $filename,
						'thumb' =>  $upload_path . '/' . $data['raw_name'] . '_thumb' . $data['file_ext']

					)
				);

				$thumb = $data['raw_name'] . '_thumb' . $data['file_ext'];
			} else {
				$thumb = '';
				// $result = 
				$this->db->insert(
					'Tr_Media',
					array(
						'nim' => $this->session->userdata('studentid'),
						'file' =>  $upload_path . '/' . $data['file_name'],
						'thumb' => $thumb
					)
				);
			}

			echo json_encode(
				[
					'status' => 'Ok',
					'id' => $this->db->insert_id(),
					// 'path' => $upload_path . '/' . $data['file_name'],
					'thumb' => $thumb,
					'orig' => $upload_path . '/' . $data['file_name'],
					'filename' => $data['file_name']
				]
			);


			// $this->_create_thumbs($data['file_name']);

			// // $result = 
			// $this->db->insert(
			// 	'Tr_Media',
			// 	array(
			// 		'nim' => $this->session->userdata('studentid'),
			// 		'file' =>  $upload_path . '/' . $data['file_name'],
			// 		'thumb' =>  $upload_path . '/' . $data['raw_name'] . '_thumb' . $data['file_ext']
			// 	)
			// );


		}
	}

	public function hapus_file()
	{
		$id = $_POST['id'];
		$media = $this->db->get_where('Tr_Media', array('id' => $id))->row_array();
		$exist = is_file($media['thumb']);

		if ($media['thumb']) {
			if (is_file($media['thumb'])) {
				unlink($media['thumb']);
				$thumb = 'deleted';
			}
		}
		if ($media['file']) {
			if (is_file($media['file'])) {
				unlink($media['file']);
				$file = 'deleted';
			}
		}

		$hapus = $this->db->delete('Tr_Media', array('id' => $id));
		// if ($hapus) {
		echo json_encode(array(
			"statusCode" => 200,
			"id" => $id,
			'thumb' => ($media['thumb']) ? $thumb : 'gada',
			'file' => ($media['file']) ? $file : 'gada',
			'hapus' => $hapus
		));
		//}
	}

	public function get_used_file_name()
	{
		$used_file_id = $this->input->post('id');
		$query = $this->db->query("SELECT * FROM Tr_Media WHERE id = $used_file_id")->row_array();
		$file_dir = $query['file'];

		$file_name = explode("/", $file_dir);

		echo json_encode($file_name[2]);
	}

	public function tampil_pengajuan($id_surat)
	{

		$data['title'] = 'Tampil Surat';
		$data['pengajuan'] = $this->pengajuan_model->get_detail_pengajuan($id_surat);

		echo '<pre>';
		print_r($data['pengajuan']);
		echo '</pre>';

		// $data['no_surat'] = $this->pengajuan_model->get_no_surat($id_surat);
		// $kategori = $data['surat']['kategori_surat'];
		// $nim = $data['surat']['username'];

		//$this->load->view('admin/surat/tampil_surat', $data);

		// $mpdf = new \Mpdf\Mpdf([
		// 	'tempDir' => __DIR__ . '/pdfdata',
		// 	'mode' => 'utf-8',
		// 	// 'format' => [24, 24],
		// 	'format' => 'A4',
		// 	'margin_left' => 0,
		// 	'margin_right' => 0,
		// 	'margin_bottom' => 20,
		// 	'margin_top' => 30,
		// 	'float' => 'left'
		// ]);

		// $view = $this->load->view('mahasiswa/pengajuan/tampil_pengajuan', $data, TRUE);

		// $mpdf->SetHTMLHeader('
		// <div style="text-align: left; margin-left:2cm">
		// 		<img width="390" height="" src="' . base_url() . '/public/dist/img/logokop-lpka.jpg" />
		// </div>');
		// $mpdf->SetHTMLFooter('

		// <div style="text-align:center; background:red;">
		// 	<img width="" height="" src="' . base_url() . '/public/dist/img/footerkop-lpka.jpg" />
		// </div>');

		// $mpdf->WriteHTML($view);

		// //		$mpdf->Output('Surat-' . $kategori . '-' . $nim . '.pdf', 'D');
		// $mpdf->Output('Surat-.pdf', 'D');
	}

	function _create_thumbs($upload_data)
	{
		// Image resizing config
		$upload_data = $this->upload->data();
		$image_config["image_library"] = "gd2";
		$image_config["source_image"] = $upload_data["full_path"];
		$image_config['create_thumb'] = true;
		$image_config['maintain_ratio'] = TRUE;
		$image_config['thumb_marker'] = "_thumb";
		$image_config['new_image'] = $upload_data["file_path"];
		$image_config['quality'] = "100%";
		$image_config['width'] = 320;
		$image_config['height'] = 240;
		$dim = (intval($upload_data["image_width"]) / intval($upload_data["image_height"])) - ($image_config['width'] / $image_config['height']);
		$image_config['master_dim'] = ($dim > 0) ? "height" : "width";

		$this->load->library('image_lib');
		$this->image_lib->initialize($image_config);

		if (!$this->image_lib->resize()) { //Resize image
			redirect("errorhandler"); //If error, redirect to an error page
		}
	}

	function anggota_check($str)
	{
		if (is_array($str)) {
			$this->form_validation->set_message('Anggota', 'The {field} field can not be the word "test"');
			return true;
		} else {
			return false;
		}
	}

	// public function edit()
	// {
	//   $data['query'] = $this->pengajuan_model->get_surat();
	//   $data['title'] = 'Ajukan Surat';
	//   $data['view'] = 'surat/tambah';
	//   $this->load->view('layout/layout', $data);
	// }
	// public function hapus($id_surat = 0)
	// {
	//   $surat_exist = $this->pengajuan_model->get_detail_surat($id_surat);
	//   if ($surat_exist['id_status'] == 4) {
	//     $this->db->delete('surat', array('id' => $id_surat));
	//     $this->session->set_flashdata('msg', 'Surat berhasil dihapus');
	//     redirect(base_url('mahasiswa/surat'));
	//   } else {
	//     $this->session->set_flashdata('msg', 'Surat Gagal dihapus');
	//     redirect(base_url('mahasiswa/surat'));
	//   }
	// }

	// public function tampil_surat($id_surat)
	// {
	//   $data['title'] = 'Tampil Surat';
	//   $data['surat'] = $this->pengajuan_model->get_detail_surat($id_surat);
	//   $data['no_surat'] = $this->pengajuan_model->get_no_surat($id_surat);
	//   $kategori = $data['surat']['kategori_surat'];
	//   $nim = $data['surat']['username'];

	//   //$this->load->view('admin/surat/tampil_surat', $data);

	//   $mpdf = new \Mpdf\Mpdf([
	//     'tempDir' => __DIR__ . '/pdfdata',
	//     'mode' => 'utf-8',
	//     // 'format' => [24, 24],
	//     'format' => 'A4',
	//     'margin_left' => 0,
	//     'margin_right' => 0,
	//     'margin_bottom' => 20,
	//     'margin_top' => 30,
	//     'float' => 'left'
	//   ]);

	//   $view = $this->load->view('admin/surat/tampil_surat', $data, TRUE);

	//   $mpdf->SetHTMLHeader('
	// 	<div style="text-align: left; margin-left:2cm">
	// 			<img width="390" height="" src="' . base_url() . '/public/dist/img/logokop-pasca.jpg" />
	// 	</div>');
	//   $mpdf->SetHTMLFooter('

	// 	<div style="text-align:center; background:red;">
	// 		<img width="" height="" src="' . base_url() . '/public/dist/img/footerkop-pasca.jpg" />
	// 	</div>');

	//   $mpdf->WriteHTML($view);

	//   $mpdf->Output('Surat-' . $kategori . '-' . $nim . '.pdf', 'D');
	// }
}
