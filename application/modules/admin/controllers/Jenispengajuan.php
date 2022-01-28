<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenispengajuan extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengajuan_model', 'pengajuan_model');
	}

	public function index()
	{
		$data['jenis_pengajuan'] = $this->pengajuan_model->get_jenis_pengajuan();

		$data['title'] = 'Jenis Pengajuan';
		$data['view'] = 'jenispengajuan/index';
		$data['menu'] = 'jenispengajuan';
		$this->load->view('layout/layout', $data);
	}

	public function tambah()
	{

		if ($this->input->post('submit')) {

			$this->form_validation->set_rules(
				'Jenis_Pengajuan',
				'Nama Jenis Pengajuan',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);

			if ($this->form_validation->run() == FALSE) {
			//	$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan();
				$data['title'] = 'Tambah Jenis Pengajuan';
				$data['view'] = 'jenispengajuan/tambah';
				$data['menu'] = 'jenispengajuan';
				$this->load->view('layout/layout', $data);
			} else {

				$data = array(
					// 'jenis_pengajuan' => $this->input->post('Jenis_Pengajuan')
					'Jenis_Pengajuan' => $this->input->post('Jenis_Pengajuan')
				);

				$result = $this->pengajuan_model->tambah_jenis_pengajuan($data);

				$id = $this->db->insert_id();
				if ($result) {

					$insdata_penghargaan = [
						"order" => 0,
						"Jenis_Pengajuan_Id" => $id,
					];
					$this->db->insert('Mstr_Penghargaan_Rekognisi_Mahasiswa', $insdata_penghargaan);

					$this->session->set_flashdata('msg', 'Kategori Pengajuan berhasil ditambah!');
					redirect(base_url('admin/jenispengajuan/edit/' . $id . '?id=&pos='));
				}
			}
		} else {
		//	$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan();
			$data['title'] = 'Tambah Jenis Pengajuan';
			$data['view'] = 'jenispengajuan/tambah';
			$data['menu'] = 'jenispengajuan';
			$this->load->view('layout/layout', $data);
		}
	}

	public function edit($id)
	{
		

		if ($this->input->post('submit')) {

			$this->form_validation->set_rules(
				'parent',
				'Kategori',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'deskripsinya',
				'Deskripsi',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'Jenis_Pengajuan',
				'Nama Jenis Pengajuan',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'fields',
				'Field formulir',
				'trim|required|min_length[6]',
				array('required' => '%s wajib diisi.', 'min_length' => 'Field tidak boleh kosong')
			);

			if ($this->form_validation->run() == FALSE) {

				$data['kategori_jenis_pengajuan'] = $this->pengajuan_model->get_kategori_jenis_pengajuan();

				$data['kategori'] = $this->pengajuan_model->get_jenis_pengajuan_byid($id);
				$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan($id, 0);
				// $data['fields_pengajuan'] = $this->pengajuan_model->getAllFieldsPengajuan(getAllFieldsPengajuan($id, 1);

				$data['view'] = 'jenispengajuan/edit';
				$data['menu'] = 'jenispengajuan';
				$this->load->view('layout/layout', $data);
			} else {

				$parent =$this->input->post('parent');
				$data = array(
					'parent' => $parent,
					'jenis_pengajuan' => $this->input->post('Jenis_Pengajuan'),
					'deskripsi' => $this->input->post('deskripsinya'),
				);

				$fields = $this->input->post('fields');
				$expl = explode('&', $fields);
				$arr = array();
				foreach ($expl as $key => $exp) {
					$exp = explode("=", $exp);
					$arr[] = $exp[1];
				}

				$dataFieldCheck = [
					'not_exist_fields_data' => implode(',', $arr),
					'sent_fields_data' => $arr,
				];

				$data = $this->security->xss_clean($data);
				$result = $this->pengajuan_model->edit_jenis_pengajuan($data, $id);

				if ($result) {

					$fields = $this->pengajuan_model->editFieldsPengajuan($dataFieldCheck, $id);

					$this->session->set_flashdata('msg', 'Jenis Pengajuan berhasil diubah!');
					redirect(base_url('admin/jenispengajuan/edit/' . $id . '?id=' . $parent . '&pos='. $parent));
				}
			}
		} else {
			$data['kategori'] = $this->pengajuan_model->get_jenis_pengajuan_byid($id);
			$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan($id, 0);

			$data['kategori_jenis_pengajuan'] = $this->pengajuan_model->get_kategori_jenis_pengajuan();

			$data['title'] = 'Edit Jenis Pengajuan';
			$data['view'] = 'jenispengajuan/edit';
			$data['menu'] = 'jenispengajuan';
			$this->load->view('layout/layout', $data);
		}
	}

	//tambah field baru
	public function tambah_field($id_kat)
	{
		//cek id terakhir
		$query =  $this->db->query('SELECT TOP 1 field_id FROM Mstr_Fields
		ORDER BY field_id DESC')->row_array();

		$data = [
			"field" => "Nama Field " . $query['field_id'],
			"key" => "key_" . $query['field_id'],
		];

		$this->db->insert('Mstr_Fields', $data);
		$last_id = $this->db->insert_id();
		if ($last_id) {

			$data = [
				"field_id" => $last_id,
				"Jenis_Pengajuan_Id" => $id_kat,
				"terpakai" => 0,
			];

			$this->db->insert('Tr_Pengajuan_Field', $data);

			echo json_encode(array("status" => "Sukses"));
		}
	}

	public function edit_field($id)
	{

		// if ($this->input->post()) {

		$this->form_validation->set_rules(
			'field',
			'Nama Field',
			'trim|required',
			array('required' => '%s wajib diisi')
		);
		
		$this->form_validation->set_rules(
			'type',
			'Jenis Field',
			'required',
			array('required' => '%s wajib diisi')
		);

		if ($this->form_validation->run() == FALSE) {
			$error = [
				'field' => form_error('field'),
				'type' => form_error('type')
			];
			echo json_encode(array("status" => "Error", "error" => $error));
		} else {

			
				$data = [
					"required" => $this->input->post('required'),
					"field" => $this->input->post('field'),
					"placeholder" => $this->input->post('placeholder'),
					"key" => ($this->input->post('type') == 'judul') ? 'judul' : $this->input->post('key') ,
					"deskripsi" => $this->input->post('deskripsi'),
					"type" => $this->input->post('type'),
				];
			

			$query = $this->pengajuan_model->edit_form_field($data, $id);

			if ($query) {
				echo json_encode(array("status" => 'Success', 'data' => $data['field']));
			} else {
				echo json_encode(array("status" => "Error Updating"));
			}
		}


		// }
	}

	public function edit_nominal($id)
	{
		$tipe_reward = $this->input->post('tipe_reward');

		$this->form_validation->set_rules(
			'tipe_reward',
			'Tipe Reward',
			'trim|required',
			array('required' => '%s wajib diisi')
		);

		if ($tipe_reward != '') {

			if ($tipe_reward != 4) {

				$this->form_validation->set_rules(
					'nominal1',
					'Nominal 1',
					'trim|required',
					array('required' => '%s wajib diisi')
				);

				if ($tipe_reward == 2) {
					$this->form_validation->set_rules(
						'nominal2',
						'Nominal 2',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
				}
			}
		}

		if ($this->form_validation->run() == FALSE) {

			$error = [
				'nominal1' => form_error('nominal1'),
				'nominal2' => form_error('nominal2'),
				'tipe_reward' => form_error('tipe_reward')
			];
			
			echo json_encode(array("status" => "Error", "error" => $error));

		} else {
			//ubah jenis nominal (column:fixed) pada tabel Mstr_Jenis_Pengajuan
			$this->db->update('Mstr_Jenis_Pengajuan', ["fixed" => $tipe_reward], array('Jenis_Pengajuan_Id' => $id));

			$new_nominal = array(
				"0" => $this->input->post('nominal1'),
				"1" => $this->input->post('nominal2')
			);

			if ($tipe_reward == 2) {

					//cek order awal apkah ada yg nilainya 1
					$nominal_exist = $this->db->select('nominal')->from('Mstr_Penghargaan_Rekognisi_Mahasiswa')->where([
						"Jenis_Pengajuan_Id" => $id,
						"order" => 1
					])->get()->result_array();

					if ($nominal_exist) {

										
						foreach ($new_nominal as $key => $value) {
							$this->db->where([
								"Jenis_Pengajuan_Id" => $id,
								"order" => $key
							]);
							$data_nominal = [
								"nominal" => $value
							];

							$this->db->update('Mstr_Penghargaan_Rekognisi_Mahasiswa', $data_nominal);
						}

					} else {

					
						$insdata_penghargaan = [
							"order" => 1,
							"Jenis_Pengajuan_Id" => $id,
						];

						$this->db->insert('Mstr_Penghargaan_Rekognisi_Mahasiswa', $insdata_penghargaan);
						foreach ($new_nominal as $key => $value) {
							$this->db->where([
								"Jenis_Pengajuan_Id" => $id,
								"order" => $key
							]);
							$data_nominal = [
								"nominal" => $value
							];

							$this->db->update('Mstr_Penghargaan_Rekognisi_Mahasiswa', $data_nominal);
						}
					}
				
			} else {

				$new_nominal = $this->input->post('nominal1');

				$data_penghargaan = [
					"nominal" => $new_nominal,
					"order" => 0,
				];
			}

			if ($tipe_reward == 1 || $tipe_reward == 3) {

				

				$this->db->where(array(
					'Jenis_Pengajuan_Id' => $id, 
					'order'=>'0'
				));

				$this->db->update('Mstr_Penghargaan_Rekognisi_Mahasiswa', $data_penghargaan);
				

			}

			echo json_encode(array("status" => "sukses"));
		}
	}


	public function store_kategori()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('nama', 'Nama Kategori', 'trim|required');
			$this->form_validation->set_rules('singkatan', 'Singkatan', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				$data['view'] = 'admin/borang/kategori/tambah_kategori';
				$this->load->view('admin/layout', $data);
			} else {

				$data = array(
					'kategori_dokumen' => $this->input->post('nama'),
					'singkatan' => $this->input->post('singkatan'),
				);

				print_r($data);

				$data = $this->security->xss_clean($data);
				$result = $this->apt_model->add_kategori($data);
				if ($result) {
					$this->session->set_flashdata('msg', 'Kategori baru berhasil ditambahkan!');
					redirect(base_url('admin/kategori'));
				}
			}
		} else {
			$data['view'] = 'admin/borang/kategori/tambah_kategori';
			$this->load->view('admin/layout', $data);
		}
	}

	public function nominal_penghargaan()
	{
		$penghargaan = $this->db->select('*')
			->from('Mstr_Jenis_Pengajuan jp')
			->join('Mstr_Penghargaan_Rekognisi_Mahasiswa penghargaan', 'penghargaan.Jenis_Pengajuan_Id=jp.Jenis_Pengajuan_Id')
			->where([
				'parent' => 12
			])
			->get()
			->result_array();

		$data = [
			'penghargaan' => $penghargaan,
			'view' => 'jenispengajuan/nominal'
		];

		$this->load->view('layout/layout', $data);

		// echo "<pre>";
		// print_r($penghargaan);
		// echo "<pre/>";
	}

	function alpha_dash_space($fullname)
	{
		if (!preg_match('/^[a-z\_]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'Hanya huruf kecil dan tanpa spasi, spasi boleh diganti underscore (_)');
			return FALSE;
		} else {
			return TRUE;
		}
	}


	// //kategori isian rules
	// public function kat_keterangan_pengajuan_check()
	// {
	// 	//if (isset($_POST['accept_terms_checkbox']))
	//         if ($this->input->post('kat_keterangan_surat'))
	// 	{
	// 		return TRUE;
	// 	}
	// 	else
	// 	{
	// 		$error = 'Please read and accept our terms and conditions.';
	// 		$this->form_validation->set_message('kat_keterangan_surat', $error);
	// 		return FALSE;
	// 	}
	// }
}
