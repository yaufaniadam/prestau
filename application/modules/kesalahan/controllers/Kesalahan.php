<?php defined('BASEPATH') or exit('No direct script access allowed');
class Kesalahan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	//--------------------------------------------------------------
	public function index()
	{
		$data['title'] = 'Error';
		$data['error'] = 'Anda tidak memiliki hak akses pada laman ini.';
		$data['view'] = '/error';
		$this->load->view('layout/layout', $data);
	}
}  // end class
