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
		$this->load->view('layout/layout', $data);
	}

	public function tambah()
	{

		if ($this->input->post('submit')) {

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

			// echo '<pre>';
			// print_r($this->input->post());
			// echo '</pre>';

			if ($this->form_validation->run() == FALSE) {
				$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan();
				$data['title'] = 'Tambah Jenis Pengajuan';
				$data['view'] = 'jenispengajuan/tambah';
				$this->load->view('layout/layout', $data);
			} else {

				$data = array(
					'jenis_pengajuan' => $this->input->post('Jenis_Pengajuan'),
					'deskripsi' => $this->input->post('deskripsinya'),
				);


				$result = $this->pengajuan_model->tambah_jenis_pengajuan($data);
				$id = $this->db->insert_id();
				if ($result) {
					$fields = $this->input->post('fields');
					$expl = explode('&', $fields);
					foreach ($expl as $key => $exp) {
						$exp = explode("=", $exp);
						echo $key . "=>" . $exp[1] . "<br>";

						$insert_field = array(
							'Jenis_Pengajuan_Id' => $id,
							'field_id' => $exp[1],
							'terpakai' => 1,
							'urutan' => $key
						);

						$this->pengajuan_model->tambah_field_pengajuan($insert_field);

						echo '<pre>';
						print_r($insert_field);
						echo '</pre>';
					}

					$this->session->set_flashdata('msg', 'Kategori Pengajuan berhasil ditambah!');
					redirect(base_url('admin/jenispengajuan/edit/' . $id));
				}
			}
		} else {
			$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan();
			$data['title'] = 'Tambah Jenis Pengajuan';
			$data['view'] = 'jenispengajuan/tambah';
			$this->load->view('layout/layout', $data);
		}
	}

	public function edit($id)
	{

		if ($this->input->post('submit')) {

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

			if ($this->form_validation->run() == FALSE) {
				$data['kategori'] = $this->pengajuan_model->get_jenis_pengajuan_byid($id);
				$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan();
				$data['fields_pengajuan'] = $this->pengajuan_model->getAllFieldsPengajuan();

				$data['view'] = 'jenispengajuan/edit';
				$this->load->view('layout/layout', $data);
			} else {

				$data = array(
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

				// print_r($data);
				$data = $this->security->xss_clean($data);
				$result = $this->pengajuan_model->edit_jenis_pengajuan($data, $id);
				if ($result) {
					// print_r($data);
					$this->pengajuan_model->editFieldsPengajuan($dataFieldCheck, $id);
					$this->session->set_flashdata('msg', 'Data kategory berhasil diubah!');
					redirect(base_url('admin/jenispengajuan/edit/' . $id));
				}
			}
		} else {
			$data['kategori'] = $this->pengajuan_model->get_jenis_pengajuan_byid($id);
			$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan();
			$data['fields_pengajuan'] = $this->pengajuan_model->getAllFieldsPengajuan();

			$data['title'] = 'Edit Jenis Pengajuan';
			$data['view'] = 'jenispengajuan/edit';
			$this->load->view('layout/layout', $data);
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
