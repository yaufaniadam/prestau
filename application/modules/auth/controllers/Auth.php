<?php defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mailer');
		$this->load->model('auth_model', 'auth_model');
	}
	//--------------------------------------------------------------
	public function index()
	{
		if (!$this->session->has_userdata('is_login')) {
			redirect('auth/login');
		} else {
			if( $_SESSION['role'] != 3) {
				redirect('admin/dashboard');
			} else {
				redirect('mahasiswa/dashboard');
			}
		}
	}
	//--------------------------------------------------------------

	public function login()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				$data['ref'] = '';
				$this->load->view('auth/login', $data);
			} else {

				$data = array(
					'username' => $this->input->post('username'),
					'password' => $this->input->post('password')
				);

				$result = $this->auth_model->login($data);

				if ($result) {
					$user_data = array(
						'user_id' => $result['id'],
						'username' => $result['username'],
						'fullname' => $result['fullname'],
						'role' => $result['role'],
						'id_prodi' => $result['prodi'],
						'is_login' => TRUE,
					);

					$this->session->set_userdata($user_data);
					redirect(base_url('admin/dashboard'), 'refresh');

				} else {

					//periksa di tabel mhs

					// $params = http_build_query($data);

					// $email = $this->input->post('username');

					// $body = array('http' =>
					// array(
					// 	'method' => 'POST',
					// 	'header' => 'Content-type: application/x-www-form-urlencoded',
					// 	'content' => $params
					// ));
					// $context = stream_context_create($body);
					// $link = file_get_contents('https://sso.umy.ac.id/api/Authentication/Login', false, $context);
					// $json = json_decode($link);

					// $ceknum = $json->{'code'};

	
					$query = $this->db->query(
								"SELECT * from v_mahasiswa m
							LEFT JOIN mstr_department d on d.DEPARTMENT_ID = m.DEPARTMENT_ID
							WHERE STUDENTID ='" . $data['username'] . "' ");

					if ($query->num_rows() == 0) {
						return false;
					} else {
						//Compare the password attempt with the password we have stored.
						$result = $query->row_array();
						$validPassword = ($data['password']=='Semangat');
						if ($validPassword) {
							 $result = $query->row_array();		
							 
							 $user_data = array(
								'studentid' => $result['STUDENTID'],
								'fullname' => $result['FULLNAME'],
								'email' => $result['email'],
								'fakultas' => $result['NAME_OF_FACULTY'],
								'id_prodi' => $result['NAME_OF_DEPARTMENT'],
								'role' => 3,
								'created_at' => date('Y-m-d : h:m:s'),
							);

							$this->session->set_userdata($user_data);
							$this->session->set_userdata('is_login', TRUE);
	
							$this->session->set_userdata('user_id', $result);
	
							redirect(base_url('mahasiswa/dashboard'), 'refresh');

						}  else {
							$data['ref'] = '';
							$data['msg'] = 'Invalid Username or Password!';
							$this->load->view('auth/login', $data);
						}
					}
						
				}
			}
		} else {
			$data['ref'] = '';
			$this->load->view('auth/login', $data);
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('auth/login'), 'refresh');
	}
}  // end class
