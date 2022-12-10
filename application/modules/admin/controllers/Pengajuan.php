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
		$this->load->library('mailer');
	}

	public function index($role = 0)
	{
		$data['query'] = $this->pengajuan_model->get_pengajuan($role);
		$data['title'] = 'Semua Pengajuan';
		$data['view'] = 'pengajuan/index';
		$data['menu'] = 'pengajuan';
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
			ps.date as date
			FROM tr_pengajuan_status ps
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			WHERE ps.pengajuan_id = $pengajuan_id
			ORDER BY status_pengajuan_id DESC"

		)->result_array();

		$data['fields'] = $this->db->query(
			"SELECT * FROM mstr_jenis_pengajuan jp 
			LEFT JOIN mstr_pengajuan_field pf ON pf.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id
			LEFT JOIN mstr_fields f ON f.field_id = pf.field_id
			WHERE jp.Jenis_Pengajuan_Id = $jenis_pengajuan_id
			AND pf.terpakai = 1
			ORDER BY urutan ASC"
		)->result_array();

		$data['title'] =  $pengajuan['Jenis_Pengajuan'];
		$data['view'] = 'pengajuan/detail';

		$this->load->view('layout/layout', $data);
	}

	public function verified()
	{
		if ($this->input->post('submit')) {

			$this->form_validation->set_rules(
				'periode_id',
				'Periode',
				'trim|required',
				array('required' => 'Pilih periode')
			);

			$this->form_validation->set_rules(
				'pengajuan_id[]',
				'Pengajuan',
				'trim|required',
				array('required' => 'Pilih minimal satu pengajuan')
			);

			if ($this->form_validation->run() == FALSE) {
				$data['query'] = $this->pengajuan_model->getVerifiedPengajuan();
				$data['title'] = 'Pengajuan yang Lolos Verifikasi';
				$data['view'] = 'pengajuan/verified';
				$data['verified'] = true;
				$data['daftar_periode'] = $this->periode_model->getPeriode('0');
				$data['menu'] = 'verified';
				$this->load->view('layout/layout', $data);
			} else {

				//ambil semua pengajuan dari tabel verified
				$daftar_pengajuan_id = $this->input->post('pengajuan_id[]');
				$periode_id = $this->input->post('periode_id');


				//lalu diforeach
				foreach ($daftar_pengajuan_id as $pengajuan_id) {

					$queryp = $this->db->get_where('tr_pengajuan', ['pengajuan_id' => $pengajuan_id])->row_object();
					$jenis_pengajuan_id = $queryp->Jenis_Pengajuan_Id;

					//cek apakah pengajuan ini memiliki field anggota
					$is_field_anggota_exist = $this->db->get_where(
						'mstr_pengajuan_field',
						[
							'Jenis_Pengajuan_Id' => $jenis_pengajuan_id,
							'field_id' => 77 // field anggota
						]
					)->num_rows();

					// mengambil tipe reward dari jenis pengajuan. ada 4 tipe reward			
					// 1. Individu (id = 1)
					// 2. Kelompok (Ketua dan anggota memperoleh nominal yang berbeda) (id = 2)
					// 3. Kelompok (Reward diberikan kepada kelompok, bukan kepada tiap anggota) (id = 3)
					// 4. Berdasarkan biaya yang dikeluarkan oleh mahasiswa (id = 0)

					$jenis_pengajuan = $this->db->get_where(
						'mstr_jenis_pengajuan',
						[
							'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id
						]
					)->row_object();

					$tipe_reward = 	$jenis_pengajuan->fixed;


					if ($tipe_reward == 1) {

						$nominal = $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa', [
							'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
						])->row_object()->nominal;


						$nim = $this->db->get_where('tr_pengajuan', ['pengajuan_id' => $pengajuan_id])->row_object()->nim;

						$data = [
							'id_periode' => $periode_id,
							'id_pengajuan' => $pengajuan_id,
							'pic' => $_SESSION['user_id'],
							'STUDENTID' => $nim,
							'nominal' => $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa', [
								'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
							])->row_object()->nominal
						];

						$this->db->insert('tr_penerbitan_pengajuan', $data);



						$this->db->set('status_id', 9)
							->set('pic', $this->session->userdata('user_id'))
							->set('date', 'getdate()', FALSE)
							->set('pengajuan_id', $pengajuan_id)
							->insert('tr_pengajuan_status');
							
							redirect(base_url('admin/periode/bulan/' . $periode_id));

					} elseif ($tipe_reward == 2) {


						$anggota = get_meta_value_by_type_field('select_anggota', $pengajuan_id, false);
						$anggota = explode(',', $anggota);

						$nominal = $this->db->select('nominal')->from('mstr_penghargaan_rekognisi_mahasiswa')->where([
							'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
						])->get()->result_array();

						foreach ($anggota as $key => $anggota) {

							$data = [
								'id_periode' => $periode_id,
								'id_pengajuan' => $pengajuan_id,
								'pic' => $_SESSION['user_id'],
								'STUDENTID' => $anggota,
								'nominal' => ($key < 1) ? $nominal[0]['nominal'] : $nominal[1]['nominal']
							];

							$this->db->insert('tr_penerbitan_pengajuan', $data);
						}

						$this->db->set('status_id', 9)
							->set('pic', $this->session->userdata('user_id'))
							->set('date', 'getdate()', FALSE)
							->set('pengajuan_id', $pengajuan_id)
							->insert('tr_pengajuan_status');

							redirect(base_url('admin/periode/bulan/' . $periode_id));

					} elseif ($tipe_reward == 3) {

						$nominal = $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa', [
							'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
						])->row_object()->nominal;


						$anggota = get_meta_value_by_type_field('select_anggota', $pengajuan_id, false);
						$anggota = explode(',', $anggota);

						foreach ($anggota as $key => $anggota) {

							// echo $anggota;
							$data = [
								'id_periode' => $periode_id,
								'id_pengajuan' => $pengajuan_id,
								'pic' => $_SESSION['user_id'],
								'STUDENTID' => $anggota,
								'nominal' => ($key < 1) ? $nominal : '0'
							];
							$this->db->insert('tr_penerbitan_pengajuan', $data);
						}

						$this->db->set('status_id', 9)
							->set('pic', $this->session->userdata('user_id'))
							->set('date', 'getdate()', FALSE)
							->set('pengajuan_id', $pengajuan_id)
							->insert('tr_pengajuan_status');

							redirect(base_url('admin/periode/bulan/' . $periode_id));

					} elseif ($tipe_reward == 4) {

						$nim = $this->db->get_where('tr_pengajuan', ['pengajuan_id' => $pengajuan_id])->row_object()->nim;

						$biaya = get_meta_value_by_type_field('biaya', $pengajuan_id, false);

						$data = [
							'id_periode' => $periode_id,
							'id_pengajuan' => $pengajuan_id,
							'pic' => $_SESSION['user_id'],
							'STUDENTID' => $nim,
							//ambil value dari field biaya
							'nominal' => $biaya,
						];
						$this->db->insert('tr_penerbitan_pengajuan', $data);

						$this->db->set('status_id', 9)
							->set('pic', $this->session->userdata('user_id'))
							->set('date', 'getdate()', FALSE)
							->set('pengajuan_id', $pengajuan_id)
							->insert('tr_pengajuan_status');

							redirect(base_url('admin/periode/bulan/' . $periode_id));

					} elseif ($tipe_reward == 5) {

						//ambil data kategori pengajuan
						$id_jenis_pengajuan = $jenis_pengajuan->Jenis_Pengajuan_Id;
						
						// ambil nominal jumlah reward yang sesuai, dengan cara mengambil data capaian prestasi yg diraih
						$capaian_prestasi = get_meta_value_by_type_field('select_prestasi', $pengajuan_id, false);

						// lalu cocokkan dengan $capaian_prestasi dengan jumlah nominal di tabel mstr_penghargaan_rekognisi_mahasiswa
						$nominal_exist = $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa',[
							'Penghargaan_Rekognisi_Mahasiswa_Id' => $capaian_prestasi,
						]);

						$nominal_numrows = $nominal_exist->num_rows();

						if ($nominal_numrows > 0) {
							$nominal = $nominal_exist->row_object()->nominal;
							//cek tipe jumlah anggota, apakah kelompok, beregu atau individu
						$jumlah_anggota = $jenis_pengajuan->jumlah_anggota;
						
						//jika individu, maka diambil nim mhs yng mengajukan
						if ($jumlah_anggota == 'individu') {
							$nim = $this->db->get_where('tr_pengajuan', ['pengajuan_id' => $pengajuan_id])->row_object()->nim;

							$data = [
								'id_periode' => $periode_id,
								'id_pengajuan' => $pengajuan_id,
								'pic' => $_SESSION['user_id'],
								'STUDENTID' => $nim,
								//ambil value dari field biaya
								'nominal' => $nominal,
							];
							$this->db->insert('tr_penerbitan_pengajuan', $data);

							//jika selain idnvidu berarti beregu /kelompok, 
						} else {
							//maka ambil data anggota kelompok menggunakan type field
							$anggota = get_meta_value_by_type_field('select_mahasiswa', $pengajuan_id, false);
							//data diexplode utk dimasukkan ke tabel 'tr_penerbitan_pengajuan'
							$anggota = explode(',', $anggota);

							foreach ($anggota as $key => $anggota) {

								// echo $anggota;
								$data = [
									'id_periode' => $periode_id,
									'id_pengajuan' => $pengajuan_id,
									'pic' => $_SESSION['user_id'],
									'STUDENTID' => $anggota,
									'nominal' => ($key < 1) ? $nominal : '0'
								];
								$this->db->insert('tr_penerbitan_pengajuan', $data);
							}
	
						}

						$this->db->set('status_id', 9)
							->set('pic', $this->session->userdata('user_id'))
							->set('date', 'getdate()', FALSE)
							->set('pengajuan_id', $pengajuan_id)
							->insert('tr_pengajuan_status');

							redirect(base_url('admin/periode/bulan/' . $periode_id));

						} else {

							echo "nominal tidak ditemukan karena form tidak diseting dengan benar . cek kolom capaian prestasi dan nominal reward";
						}
						
					}
				}
				
				
			
			}
		} else {
			$data['query'] = $this->pengajuan_model->getVerifiedPengajuan();
			$data['title'] = 'Pengajuan yang Lolos Verifikasi';
			$data['view'] = 'pengajuan/verified';
			$data['verified'] = true;
			$data['daftar_periode'] = $this->periode_model->getPeriode('0');
			$data['menu'] = 'verified';
			$this->load->view('layout/layout', $data);
		}
	}

	function reward($id_prestasi)
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
				$reward = $this->db->get_where(
					'mstr_penghargaan_rekognisi_mahasiswa',
					[
						'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
						'urutan' => $urutan > 0 ? 2 : 1
					]
				)->row_object()->nominal;
			}
		} else {
			$reward = get_meta_value('biaya_pribadi', $prestasi->id_pengajuan, false);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($reward));
	}

	public function prestasi_prodi()
	{
		$data['title'] = 'Prestasi Prodi';
		$data['view'] = 'pengajuan/prestasi_prodi';
		$prodi = $_SESSION['id_prodi'];

		$data['prestasi'] =
			$this->db->select('*')
			->from('tr_penerbitan_pengajuan pp')
			->join('tr_pengajuan p', 'pp.id_pengajuan = p.pengajuan_id', 'left')
			->join('mstr_jenis_pengajuan jp', 'p.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id')
			->join('v_mahasiswa m', 'm.STUDENTID = pp.STUDENTID')
			->join('tr_periode_penerbitan per', 'per.id_periode = pp.id_periode')
			->where(['m.DEPARTMENT_ID' => $prodi, 'per.status' => 1])
			->get()->result_array();

		$this->load->view('layout/layout', $data);
	}


	public function arsip($DEPARTMENT_ID = 0, $ID_JENIS_PENGAJUAN = 0)
	{
		$department_data = $this->db->query("SELECT * FROM mstr_department")->result_array();
		$kategori_data = $this->db->query("SELECT * FROM mstr_jenis_pengajuan WHERE Jenis_Pengajuan_Id != 12")->result_array();

		$data['query'] = $this->pengajuan_model->get_arsip_pengajuan($DEPARTMENT_ID, $ID_JENIS_PENGAJUAN);
		$data['departments'] = $department_data;
		$data['kategories'] = $kategori_data;

		$data['button_text'] = $DEPARTMENT_ID == 0 ? 'Semua Prodi' : $this->db->query(
			"SELECT NAME_OF_DEPARTMENT 
			FROM mstr_department 
			WHERE DEPARTMENT_ID = $DEPARTMENT_ID"
		)->row_object()->NAME_OF_DEPARTMENT;

		$data['button_text_2'] = $ID_JENIS_PENGAJUAN == 0 ? 'Semua Kategori' : $this->db->query(
			"SELECT Jenis_Pengajuan 
			FROM mstr_jenis_pengajuan 
			WHERE Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN"
		)->row_object()->Jenis_Pengajuan;

		$data['title'] = 'Semua Pengajuan';
		$data['view'] = 'pengajuan/arsip';
		$data['menu'] = 'arsip';
		$this->load->view('layout/layout', $data);
	}

	public function arsip_prestasi($DEPARTMENT_ID = 0, $ID_JENIS_PENGAJUAN = 0)
	{
		$department_data = $this->db->query("SELECT * FROM mstr_department")->result_array();
		$kategori_data = $this->db->query("SELECT * FROM mstr_jenis_pengajuan WHERE Jenis_Pengajuan_Id != 12")->result_array();

		$data['query'] = $this->pengajuan_model->get_arsip_pengajuan($DEPARTMENT_ID, $ID_JENIS_PENGAJUAN);
		$data['departments'] = $department_data;
		$data['kategories'] = $kategori_data;

		$data['button_text'] = $DEPARTMENT_ID == 0 ? 'Semua Prodi' : $this->db->query(
			"SELECT NAME_OF_DEPARTMENT 
			FROM mstr_department 
			WHERE DEPARTMENT_ID = $DEPARTMENT_ID"
		)->row_object()->NAME_OF_DEPARTMENT;

		$data['button_text_2'] = $ID_JENIS_PENGAJUAN == 0 ? 'Semua Kategori' : $this->db->query(
			"SELECT Jenis_Pengajuan 
			FROM mstr_jenis_pengajuan 
			WHERE Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN"
		)->row_object()->Jenis_Pengajuan;

		$data['title'] = 'Semua Pengajuan';
		$data['view'] = 'pengajuan/arsip';
		$data['menu'] = 'arsip';
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
			$catatan = $this->input->post('catatan'); //ambil nilai 
			$pengajuan_id = $this->input->post('pengajuan_id');
			$status_pengajuan = $this->input->post('rev2');
			//set status
			$this->db->set('status_id', $status_pengajuan)
				->set('pic', $this->session->userdata('user_id'))
				->set('date', 'getdate()', FALSE)
				->set('pengajuan_id', $pengajuan_id)
				->insert('tr_pengajuan_status');


			foreach ($verifikasi as $id => $value_verifikasi) {
				$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id))
					->update(
						'tr_field_value',
						array(
							'verifikasi' =>  $value_verifikasi,
							// 'value' => $dokumen
						)
					);
			}

			foreach ($catatan as $id => $value_catatan) {
				$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id))
					->update(
						'tr_field_value',
						array(
							'catatan' =>  $value_catatan,
							// 'value' => $dokumen
						)
					);
			}

			if ($status_pengajuan == 4) {

				//data utk kirim email & notif ke mhs
			// 	yg dikirimi notif saat ada revisi
			
				$data_for_notif = [

					'pengirim' => $_SESSION['user_id'],
					'penerima' => $this->input->post('user_id'),
					'id_pengajuan' => $pengajuan_id,
					'role' => [3],
					'link' => base_url('admin/pengajuan/detail/' . $pengajuan_id),
					'subjek' => 'Revisi Pengajuan prestasi',
					'isi' => 'Mohon periksa kembali pengajuan Saudara.',
					'id_status_notif' => 4,
				];

				//sendmail & notif
				$this->mailer->send_mail($data_for_notif);

			} else 	if ($status_pengajuan == 6) {

				//data utk kirim email & notif ke mhs
			// 	yg dikirimi notif saat ada revisi

				$data_for_notif = [

					'pengirim' => $_SESSION['user_id'],
					'penerima' => $this->input->post('user_id'),
					'id_pengajuan' => $pengajuan_id,
					'role' => [3],
					'link' => base_url('admin/pengajuan/detail/' . $pengajuan_id),
					'subjek' => 'Revisi Pengajuan prestasi',
					'isi' => 'Mohon periksa kembali pengajuan Saudara.',
					'id_status_notif' => 5,
				];

				//sendmail & notif
				$this->mailer->send_mail($data_for_notif);

			}

			redirect(base_url('admin/pengajuan/detail/' . $pengajuan_id));
		} else {
			$data['title'] = 'Forbidden';
			$data['view'] = 'restricted';
			$this->load->view('layout/layout', $data);
		}
	}

	public function editfield()
	{

		$id = 	$this->input->post('id');
		$pengajuan_id = 	$this->input->post('pengajuan_id');


		$update_field = $this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id))
			->update(
				'tr_field_value',
				array(
					'value' =>  $this->input->post('valfield'),
					'tanggal_edit' => date('Y-m-d h:m:s'),
					'diedit_oleh' =>  $this->session->userdata('user_id'),
				)
			);
		if ($update_field) {
			$data = [
				'status' => 'sukses',
			];
		}

		echo json_encode($data);
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
				->insert('tr_pengajuan_status');

			foreach ($verifikasi as $id => $value_verifikasi) {

				$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id))
					->update(
						'tr_field_value',
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
			$id_pengajuan = $this->input->post('id_pengajuan');

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

		$data_notif = array(
			'id_pengajuan' => $insert_id2['id_pengajuan'],
			'id_status' => 1,
			'kepada' => $_SESSION['user_id'],
			'role' => array(3)
		);

		$results = $this->notif_model->send_notif($data_notif);

		if ($results) {
			$this->session->set_flashdata('msg', 'Berhasil!');
			redirect(base_url('admin/pengajuan/tambah/' . $insert_id));
		}
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
	}

	private function getProdiByNIM($nim)
	{
		$prodi = substr($nim, 4, 3);
		return $prodi;
	}

	public function pencairan()
	{
		$id_penerbitan_pengajuan = $this->input->post('id_penerbitan_pengajuan');

		$data = array(
			'petugas' => $this->input->post('petugas'),
		);

		$this->db->where('id_penerbitan_pengajuan', $id_penerbitan_pengajuan);
		$this->db->update('tr_penerbitan_pengajuan', $data);
	}

	public function hapus($id)
	{

		$hapus = $this->db->set('status_id', '20')
			->set('date', 'getdate()', FALSE)
			->set('pengajuan_id', $id)
			->set('pic', $this->session->userdata('user_id'))
			->insert('tr_pengajuan_status');

		$this->session->set_flashdata('msg', 'Pengajuan berhasil dihapus!');
		redirect(base_url('admin/pengajuan/index'));
	}
}
