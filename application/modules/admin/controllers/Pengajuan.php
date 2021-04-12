<?php defined('BASEPATH') or exit('No direct script access allowed');
class Pengajuan extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('periode_model', 'periode_model');
		$this->load->model('pengajuan_model', 'pengajuan_model');
		$this->load->model('data_pengajuan_model', 'data_pengajuan_model');
		$this->load->model('notif/Notif_model', 'notif_model');
	}

	public function index($role = 0)
	{
		$data['query'] = $this->pengajuan_model->get_pengajuan($role);
		$data['title'] = 'Semua Pengajuan';
		$data['view'] = 'pengajuan/index';
		$this->load->view('layout/layout', $data);
	}

	public function verified()
	{
		if ($this->input->post('submit')) {

			$daftar_pengajuan_id = $this->input->post('pengajuan_id[]');
			$periode_id = $this->input->post('periode_id');

			foreach ($daftar_pengajuan_id as $pengajuan_id) {

				$jenis_pengajuan_id = $this->db->get_where('Tr_Pengajuan', ['pengajuan_id' => $pengajuan_id])->row_object()->Jenis_Pengajuan_Id;
				$is_field_anggota_exist = $this->db->get_where('Tr_Pengajuan_Field', ['Jenis_Pengajuan_Id' => $jenis_pengajuan_id, 'field_id' => 77])->num_rows();

				if ($is_field_anggota_exist > 0) {
					$result = $this->db->get_where('Tr_Field_Value', ['pengajuan_id' => $pengajuan_id, 'field_id' => 77])->row_object()->value;
					$anggota = explode(',', $result);
					foreach ($anggota as $mahasiswa) {
						$data = [
							'id_periode' => $periode_id,
							'id_pengajuan' => $pengajuan_id,
							'pic' => $_SESSION['user_id'],
							'STUDENTID' => $mahasiswa
						];
						$this->db->insert('Tr_Penerbitan_Pengajuan', $data);
					}

					$this->db->set('status_id', 9)
						->set('pic', $this->session->userdata('user_id'))
						->set('date', 'getdate()', FALSE)
						->set('pengajuan_id', $pengajuan_id)
						->insert('Tr_Pengajuan_Status');
				
				} else {
					$nim = $this->db->get_where('Tr_Pengajuan', ['pengajuan_id' => $pengajuan_id])->row_object()->nim;
					$data = [
						'id_periode' => $periode_id,
						'id_pengajuan' => $pengajuan_id,
						'pic' => $_SESSION['user_id'],
						'STUDENTID' => $nim
					];
					$this->db->insert('Tr_Penerbitan_Pengajuan', $data);

					$this->db->set('status_id', 9)
						->set('pic', $this->session->userdata('user_id'))
						->set('date', 'getdate()', FALSE)
						->set('pengajuan_id', $pengajuan_id)
						->insert('Tr_Pengajuan_Status');
				}
			}
			// $this->db->set('status_id', 9)
			// 	->set('pic', $this->session->userdata('user_id'))
			// 	->set('date', 'getdate()', FALSE)
			// 	->set('pengajuan_id', $pengajuan_id)
			// 	->insert('Tr_Pengajuan_Status');

			redirect(base_url('admin/periode/bulan/'.$periode_id));
		} else {
			$data['query'] = $this->pengajuan_model->getVerifiedPengajuan();
			$data['title'] = 'Pengajuan yang Lolos Verifikasi';
			$data['view'] = 'pengajuan/verified';
			$data['verified'] = true;
			$data['daftar_periode'] = $this->periode_model->getPeriode('0');
			$this->load->view('layout/layout', $data);
		}
	}

	public function detail_prestasi($id_penerbitan_pengajuan = 0)
	{
		$data['view'] = 'pengajuan/detail_prestasi';

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

	public function arsip($DEPARTMENT_ID = 0, $ID_JENIS_PENGAJUAN = 0)
	{
		$department_data = $this->db->query("SELECT * FROM Mstr_Department")->result_array();
		$kategori_data = $this->db->query("SELECT * FROM Mstr_Jenis_Pengajuan WHERE Jenis_Pengajuan_Id != 12")->result_array();

		$data['query'] = $this->pengajuan_model->get_arsip_pengajuan($DEPARTMENT_ID, $ID_JENIS_PENGAJUAN);
		$data['departments'] = $department_data;
		$data['kategories'] = $kategori_data;

		$data['button_text'] = $DEPARTMENT_ID == 0 ? 'Semua Prodi' : $this->db->query(
			"SELECT NAME_OF_DEPARTMENT 
			FROM Mstr_Department 
			WHERE DEPARTMENT_ID = $DEPARTMENT_ID"
		)->row_object()->NAME_OF_DEPARTMENT;

		$data['button_text_2'] = $ID_JENIS_PENGAJUAN == 0 ? 'Semua Kategori' : $this->db->query(
			"SELECT Jenis_Pengajuan 
			FROM Mstr_Jenis_Pengajuan 
			WHERE Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN"
		)->row_object()->Jenis_Pengajuan;

		$data['title'] = 'Semua Pengajuan';
		$data['view'] = 'pengajuan/arsip';
		$this->load->view('layout/layout', $data);
	}

	public function detail($pengajuan_id = 0)
	{
		$this->load->helper('formulir');

		// $pengajuan_id = $this->pengajuan_model->get_detail_pengajuan($spengajuan_id)['pengajuan_id'];
		$jenis_pengajuan_id = $this->pengajuan_model->get_detail_pengajuan($pengajuan_id)['Jenis_Pengajuan_Id'];

		$pengajuan = $this->pengajuan_model->get_detail_pengajuan($pengajuan_id);

		
		$data['pengajuan'] = $pengajuan;
		$data['timeline'] = $this->db->query(
			"SELECT 
			*,
			FORMAT (ps.date, 'dd/MM/yyyy') as date,
			FORMAT (ps.date, 'hh:mm:ss') as time 
			FROM Tr_Pengajuan_Status ps
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			WHERE ps.pengajuan_id = $pengajuan_id
			ORDER BY status_pengajuan_id DESC"

		)->result_array();

		$data['fields'] = $this->db->query(
			"SELECT * FROM Mstr_Jenis_Pengajuan jp 
			LEFT JOIN Tr_Pengajuan_Field pf ON pf.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id
			LEFT JOIN Mstr_Fields f ON f.field_id = pf.field_id
			WHERE jp.Jenis_Pengajuan_Id = $jenis_pengajuan_id
			AND pf.terpakai = 1
			ORDER BY urutan ASC"
		)->result_array();

		$data['title'] =  $pengajuan['Jenis_Pengajuan'];
		$data['view'] = 'pengajuan/detail';

		$this->load->view('layout/layout', $data);
	}

	public function proses_pengajuan($id_pengajuan = 0)
	{
		$this->db->set('id_status', 2)
			->set('date', 'NOW()', FALSE)
			->set('id_pengajuan', $id_pengajuan)
			->insert('pengajuan_status');

		redirect(base_url('admin/pengajuan/detail/' . $id_pengajuan));
	}

	public function verifikasi()
	{
		if ($this->input->post('submit')) {
			$verifikasi = $this->input->post('verifikasi'); //ambil nilai 
			$pengajuan_id = $this->input->post('pengajuan_id');
			$id_notif = $this->input->post('id_notif');
			//set status
			$this->db->set('status_id', $this->input->post('rev2'))
				->set('pic', $this->session->userdata('user_id'))
				->set('date', 'getdate()', FALSE)
				->set('pengajuan_id', $pengajuan_id)
				->insert('Tr_Pengajuan_Status');

			foreach ($verifikasi as $id => $value_verifikasi) {
				$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id))
					->update(
						'Tr_Field_Value',
						array(
							'verifikasi' =>  $value_verifikasi,
						)
					);
			}

			if ($this->input->post('rev2') == 6) {
				$role = array(3, 2);
			} else if ($this->input->post('rev2') == 4) {
				$role = array(3, 2);
			} else if ($this->input->post('rev2') == 7) {
				$role = array(3, 6);
			}

			//buat notifikasi
			$this_pengajuan = $this->db->get_where('Tr_Pengajuan', ['pengajuan_id' => $pengajuan_id])->row_array();
			$data_for_notif = [
				'pengirim' => $_SESSION['userid'],
				'penerima' => $this_pengajuan['nim'],
				'id_pengajuan' => $pengajuan_id,
				'role' => [3],
				'id_status_notif' => $this->input->post('rev2'),
			];
			$this->notif_model->send_notif($data_for_notif);

			// if ($result) {
		//	$this->session->set_flashdata('msg', 'Pengajuan diperiksa oleh TU!');
			redirect(base_url('admin/pengajuan/detail/' . $pengajuan_id));
			// }
		} else {
			$data['title'] = 'Forbidden';
			$data['view'] = 'restricted';
			$this->load->view('layout/layout', $data);
		}
	}

	public function selesai()
	{
		if ($this->input->post('submit')) {

			$verifikasi = $this->input->post('verifikasi'); //ambil nilai 
			$pengajuan_id = $this->input->post('pengajuan_id');
			$id_notif = $this->input->post('id_notif');
			//set status
			$this->db->set('status_id', 10)
				->set('pic', $this->session->userdata('user_id'))
				->set('date', 'getdate()', FALSE)
				->set('pengajuan_id', $pengajuan_id)
				->insert('Tr_Pengajuan_Status');

			foreach ($verifikasi as $id => $value_verifikasi) {

				$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id))
					->update(
						'Tr_Field_Value',
						array(
							'verifikasi' =>  $value_verifikasi,
						)
					);
			}

			if ($this->input->post('rev2') == 6) {
				$role = array(3, 2);
			} else if ($this->input->post('rev2') == 4) {
				$role = array(3, 2);
			} else if ($this->input->post('rev2') == 7) {
				$role = array(3, 6);
			}

			// $result = $this->notif_model->send_notif($data_notif);

			// if ($result) {
			$this->session->set_flashdata('msg', 'Surat sudah diperiksa oleh TU!');
			redirect(base_url('admin/pengajuan/detail/' . $pengajuan_id));
			// }
		} else {
			$data['title'] = 'Forbidden';
			$data['view'] = 'restricted';
			$this->load->view('layout/layout', $data);
		}
	}

	public function disetujui()
	{
		if ($this->input->post('submit')) {

			if ($this->session->userdata('role') == 5) { // direktur
				$id_pengajuan = $this->input->post('id_pengajuan');
				$result = $this->db->set('id_status', 9)
					->set('date', 'NOW()', FALSE)
					->set('id_pengajuan', $id_pengajuan)
					->set('pic', $this->session->userdata('user_id'))
					->insert('pengajuan_status');

				if ($result) {
					$data_notif = array(
						'id_pengajuan' => $id_pengajuan,
						'id_status' => 9,
						'kepada' => $this->input->post('user_id'),
						'role' => array(3, 1)
					);

					$result = $this->notif_model->send_notif($data_notif);

					$this->session->set_flashdata('msg', 'Surat sudah diberi persetujuan oleh Direktur Pascasarjana!');
					redirect(base_url('admin/pengajuan/detail/' . $id_pengajuan));
				}
			} elseif ($this->session->userdata('role') == 6 && $this->session->userdata('id_prodi') == $this->input->post('prodi')) { // kaprodi
				$id_pengajuan = $this->input->post('id_pengajuan');
				$result = $this->db->set('id_status', 8)
					->set('date', 'NOW()', FALSE)
					->set('id_pengajuan', $id_pengajuan)
					->set('pic', $this->session->userdata('user_id'))
					->insert('pengajuan_status');

				if ($result) {
					$data_notif = array(
						'id_pengajuan' => $id_pengajuan,
						'id_status' => 8,
						'kepada' => $this->input->post('user_id'),
						'role' => array(3, 5)
					);

					$result = $this->notif_model->send_notif($data_notif);
					$this->session->set_flashdata('msg', 'Surat sudah diberi persetujuan oleh Kaprodi!');
					redirect(base_url('admin/pengajuan/detail/' . $id_pengajuan));
				}
			}
		}
	}

	public function terbitkan_pengajuan()
	{
		if ($this->input->post('submit')) {
			$id_pengajuan = $this->input->post('id_pengajuan');

			$this->form_validation->set_rules(
				'no_pengajuan',
				'Nomor Surat',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'kat_tujuan_pengajuan',
				'Kategori Tujuan Surat',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'tujuan_pengajuan',
				'Tujuan Surat',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'urusan_pengajuan',
				'Urusan Surat',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'instansi',
				'Instansi',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);

			if ($this->form_validation->run() == FALSE) {
				$data['status'] = $this->pengajuan_model->get_pengajuan_status($id_pengajuan);
				$data['pengajuan'] = $this->pengajuan_model->get_detail_pengajuan($id_pengajuan);
				$data['timeline'] = $this->pengajuan_model->get_timeline($id_pengajuan);

				$data['title'] = 'Detail Surat';
				$data['view'] = 'pengajuan/detail';
				$this->load->view('layout/layout', $data);
			} else {
				$data = array(
					'id_pengajuan' => $id_pengajuan,
					'id_kategori_pengajuan' => $this->input->post('id_kategori_pengajuan'),
					'no_pengajuan' => $this->input->post('no_pengajuan'),
					'kat_tujuan_pengajuan' => $this->input->post('kat_tujuan_pengajuan'),
					'tujuan_pengajuan' => $this->input->post('tujuan_pengajuan'),
					'urusan_pengajuan' => $this->input->post('urusan_pengajuan'),
					'instansi' => $this->input->post('instansi'),
					'tanggal_terbit' => date('Y-m-d'),
				);

				$insert = $this->db->insert('no_pengajuan', $data);
				if ($insert) {
					$this->db->set('id_status', 10)
						->set('date', 'NOW()', FALSE)
						->set('id_pengajuan', $id_pengajuan)
						->set('pic', $this->session->userdata('user_id'))
						->insert('pengajuan_status');

					$data_notif = array(
						'id_pengajuan' => $id_pengajuan,
						'id_status' => 10,
						'kepada' => $this->input->post('user_id'),
						'role' => array(3, 1, 2, 5, 6)
					);

					$result = $this->notif_model->send_notif($data_notif);

					$this->session->set_flashdata('msg', 'Surat berhasil diterbitkan!');
					redirect(base_url('admin/pengajuan/detail/' . $id_pengajuan));
				}
			}
		} else {
			$data['status'] = $this->pengajuan_model->get_pengajuan_status($id_pengajuan);
			$data['pengajuan'] = $this->pengajuan_model->get_detail_pengajuan($id_pengajuan);
			$data['timeline'] = $this->pengajuan_model->get_timeline($id_pengajuan);

			$data['title'] = 'Detail Surat';
			$data['view'] = 'pengajuan/detail';
			$this->load->view('layout/layout', $data);
		}
	}

	public function tampil_pengajuan($id_pengajuan)
	{
		$data['title'] = 'Tampil Surat';
		$data['pengajuan'] = $this->pengajuan_model->get_detail_pengajuan($id_pengajuan);
		$data['no_pengajuan'] = $this->pengajuan_model->get_no_pengajuan($id_pengajuan);
		$kategori = $data['pengajuan']['kategori_pengajuan'];
		$nim = $data['pengajuan']['username'];

		//$this->load->view('admin/pengajuan/tampil_pengajuan', $data);

		$mpdf = new \Mpdf\Mpdf([
			'tempDir' => __DIR__ . '/pdfdata',
			'mode' => 'utf-8',
			// 'format' => [24, 24],
			'format' => 'A4',
			'margin_left' => 0,
			'margin_right' => 0,
			'margin_bottom' => 20,
			'margin_top' => 30,
			'float' => 'left'
		]);

		$view = $this->load->view('admin/pengajuan/tampil_pengajuan', $data, TRUE);

		$mpdf->SetHTMLHeader('
		<div style="text-align: left; margin-left:2cm">
				<img width="390" height="" src="' . base_url() . '/public/dist/img/logokop-pasca.jpg" />
		</div>');
		$mpdf->SetHTMLFooter('

		<div style="text-align:center; background:red;">
			<img width="" height="" src="' . base_url() . '/public/dist/img/footerkop-pasca.jpg" />
		</div>');

		$mpdf->WriteHTML($view);

		$mpdf->Output('Surat-' . $kategori . '-' . $nim . '.pdf', 'D');
	}

	public function get_tujuan_pengajuan()
	{
		$kat_tujuan = $this->input->post('kat_tujuan_pengajuan');
		$data = $this->db->query("SELECT * FROM tujuan_pengajuan WHERE id_kat_tujuan_pengajuan = $kat_tujuan")->result_array();
		echo json_encode($data);
	}

	public function ajukan($id_kategori = 0)
	{
		$data['kategori_pengajuan'] = $this->pengajuan_model->get_kategori_pengajuan('p');
		$data['title'] = 'Buat Surat';
		$data['view'] = 'pengajuan/ajukan';
		$this->load->view('layout/layout', $data);
	}

	public function buat_pengajuan($id)
	{
		$data = array(
			'id_kategori_pengajuan' => $id,
			'id_mahasiswa' => $this->session->userdata('user_id'),
		);

		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';

		$data = $this->security->xss_clean($data);
		$result = $this->pengajuan_model->tambah($data);
		//ambil last id pengajuan yg baru diinsert
		$insert_id = $this->db->insert_id();
		// set status pengajuan
		$this->db->set('id_pengajuan', $insert_id)
			->set('id_status', 1)
			->set('pic', $this->session->userdata('user_id'))
			->set('date', 'NOW()', FALSE)
			->insert('pengajuan_status');

		//ambil id pengajuan berdasarkan last id status pengajuan
		$insert_id2 = $this->db->select('id_pengajuan')->from('pengajuan_status')->where('id=', $this->db->insert_id())->get()->row_array();
		// ambil keterangan pengajuan berdasar kategori pengajuan
		$kat_pengajuan = $this->db->select('kat_keterangan_pengajuan')->from('kategori_pengajuan')->where('id=', $id)->get()->row_array();

		// explode kterangan pengajuan
		$kat_pengajuan = explode(',', $kat_pengajuan['kat_keterangan_pengajuan']);

		// foreach keterangan pengajuan, lalu masukkan nilai awal (nilai kosong) berdasakan keterangan dari kategori pengajuan
		foreach ($kat_pengajuan as $key => $id_kat) {
			$this->db->insert(
				'keterangan_pengajuan',
				array(
					'value' => '',
					'id_pengajuan' =>  $insert_id2['id_pengajuan'],
					'id_kat_keterangan_pengajuan' => $id_kat,
				)
			);
		}

		// // $data_notif = array(
		// // 	'id_pengajuan' => $insert_id2['id_pengajuan'],
		// // 	'id_status' => 1,
		// // 	'kepada' => $_SESSION['user_id'],
		// // 	'role' => array(3)
		// // );

		// $results = $this->notif_model->send_notif($data_notif);


		$this->load->library('email');


		$this->email->from('yaufani@gmail.com', 'yaufani Adam');
		$this->email->to('yaufani@gmail.com');

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');

		$this->email->send();

		// if ($results) {
		$this->session->set_flashdata('msg', 'Berhasil!');
		redirect(base_url('admin/pengajuan/tambah/' . $insert_id));
		// }
	}


	public function getDataPengajuan()
	{
		$results = $this->data_pengajuan_model->getDataPengajuan();
		$number_of_data = $this->data_pengajuan_model->_count_filtered_data();

		$no = $_POST['start'];
		$data = [];
		$row = array();
		foreach ($results as $result) {
			// $row[] = ++$no;
			$data[] = [
				$result['Jenis_Pengajuan'],
				'<td class=' . 'table-' . $result['badge'] . '>' . $result['status_id'] . '-' . $result['status'] . '</td>',
				$result['FULLNAME'],
				$result['date'],
			];
		}
		$data_output = $data;

		$output = [
			"draw" => $_POST["draw"],
			"totalRecords" => $this->data_pengajuan_model->_count_all_data(),
			"filteredRecords" => $this->data_pengajuan_model->_count_filtered_data(),
			"data" => $data_output
		];

		$this->output->set_content_type('application/json')->set_output(json_encode($output));



		// var_dump($data);
		// die();
		// echo "<pre>";
		// print_r($results);
		// echo "</pre>";
		// echo $number_of_data;
	}
}
