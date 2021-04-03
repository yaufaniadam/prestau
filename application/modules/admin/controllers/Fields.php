<?php defined('BASEPATH') or exit('No direct script access allowed');
class Fields extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('fields_model', 'fields_model');
	}

	public function index()
	{
		$data = [
			'view' => 'fields/index',
			'fields' => $this->fields_model->index()
		];
		$this->load->view('layout/layout', $data);
	}

	public function tambah()
	{
		$data = [
			'view' => 'fields/tambah',
		];

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('field', 'Field', 'required');
			$this->form_validation->set_rules('key', 'Key', 'required');
			$this->form_validation->set_rules('type', 'Type', 'required');
			$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('layout/layout', $data);
			} else {
				$data = [
					'field' => $this->input->post('field'),
					'key' => $this->input->post('key'),
					'type' => $this->input->post('type'),
					'deskripsi' => $this->input->post('deskripsi'),
				];

				$data = $this->security->xss_clean($data);

				if ($this->fields_model->insert($data)) {
					$this->session->set_flashdata('succeed', 'field baru berhasil ditambahkan');
					redirect(base_url('admin/fields'));
				}
			}
		} else {
			$this->load->view('layout/layout', $data);
		}
	}

	public function edit($field_id)
	{
		$data = [
			'view' => 'fields/edit',
			'field' => $this->fields_model->detail($field_id)
		];

		if ($this->input->post('submit')) {

			$this->form_validation->set_rules('field', 'Field', 'required');
			$this->form_validation->set_rules('key', 'Key', 'required');
			$this->form_validation->set_rules('type', 'Type', 'required');
			$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('layout/layout', $data);
			} else {
				$data = [
					'field' => $this->input->post('field'),
					'key' => $this->input->post('key'),
					'type' => $this->input->post('type'),
					'deskripsi' => $this->input->post('deskripsi'),
				];

				$data = $this->security->xss_clean($data);

				if ($this->fields_model->update($data, $field_id)) {
					$this->session->set_flashdata('succeed', 'data field berhasil dirubah');
					redirect(base_url('admin/fields'));
				}
			}
		} else {
			$this->load->view('layout/layout', $data);
		}
	}
}
