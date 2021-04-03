<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prodi extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('prodi_model', 'prodi_model');
	}

	public function index()
	{
		$data['query'] = $this->prodi_model->get_prodi();
		$data['title'] = 'Program Studi';
		$data['view'] = 'prodi/index';
		$this->load->view('layout/layout', $data);
	}

	public function edit($id_prodi)
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules(
				'prodi',
				'Prodi',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'singkatan',
				'Singkatan',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'admin_prodi[]',
				'Admin Prodi',
				'required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'ka_prodi[]',
				'Kepala Prodi',
				'required',
				array('required' => '%s wajib diisi.')
			);


			if ($this->form_validation->run() == FALSE) {
				$data['prodi'] = $this->prodi_model->get_detail_prodi($id_prodi);
				$data['admin_prodi'] = $this->prodi_model->get_admin_prodi($id_prodi, ($id_prodi == 11) ? 3 : 2);
				$data['ka_prodi'] = $this->prodi_model->get_admin_prodi($id_prodi, ($id_prodi == 11) ? 5 : 6);
				$data['title'] =  ($id_prodi != 11) ? 'Edit Program Studi' : 'Edit Program';
				$data['view'] = 'prodi/edit';
				$this->load->view('layout/layout', $data);
			} else {

				$data = array(
					'prodi' => $this->input->post('prodi'),
					'singkatan' => $this->input->post('singkatan'),
					'admin_prodi' => implode(',', $this->input->post('admin_prodi[]')),
					'ka_prodi' => implode(',', $this->input->post('ka_prodi[]')),
				);
				// print_r($data);
				$data = $this->security->xss_clean($data);
				$result = $this->prodi_model->edit_prodi($data, $id_prodi);
				if ($result) {
					$this->session->set_flashdata('msg', 'data prodi berhasil diubah!');
					redirect(base_url('admin/prodi/edit/' . $id_prodi));
				}
			}
		} else {

			$data['prodi'] = $this->prodi_model->get_detail_prodi($id_prodi);
			$data['admin_prodi'] = $this->prodi_model->get_admin_prodi($id_prodi, ($id_prodi == 11) ? 1 : 2);
			$data['ka_prodi'] = $this->prodi_model->get_admin_prodi($id_prodi, ($id_prodi == 11) ? 5 : 6);
			$data['title'] = ($id_prodi != 11) ? 'Edit Program Studi' : 'Edit Program';
			$data['view'] = 'prodi/edit';
			$this->load->view('layout/layout', $data);
		}
	}
}
