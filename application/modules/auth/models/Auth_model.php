<?php
class Auth_model extends CI_Model
{

	public function login($data)
	{
		$query = $this->db->select('*')->from('users')->where(array('username' => $data['username']))->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			//Compare the password attempt with the password we have stored.
			$result = $query->row_array();
			$validPassword = password_verify($data['password'], $result['password']);
			if ($validPassword) {
				return $result = $query->row_array();
			}
		}
	}
	public function user_exist($data)
	{
		$query = $this->db->select('*')->from('users')->where(array('email' => $data['username']))->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			return $query->row_array()['id'];
		}
	}

	//--------------------------------------------------------------------
	public function register($data)
	{
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	//--------------------------------------------------------------------
	public function email_verification($code)
	{
		$this->db->select('email, token');
		$this->db->from('users');
		$this->db->where('token', $code);
		$query = $this->db->get();
		$result = $query->result_array();
		$match = count($result);
		if ($match > 0) {
			$this->db->where('token', $code);
			$this->db->update('users', array('token' => ''));
			return true;
		} else {
			return false;
		}
	}

	//============ Check User Email ============
	function check_user_mail($email)
	{
		$result = $this->db->get_where('users', array('email' => $email));

		if ($result->num_rows() > 0) {
			$result = $result->row_array();
			return $result;
		} else {
			return false;
		}
	}

	//============ Update Reset Code Function ===================
	public function update_reset_code($reset_code, $user_id)
	{
		$data = array('password_reset_code' => $reset_code);
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);
	}

	//============ Activation code for Password Reset Function ===================
	public function check_password_reset_code($code)
	{

		$result = $this->db->get_where('users',  array('password_reset_code' => $code));
		if ($result->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	//============ Reset Password ===================
	public function reset_password($id, $new_password)
	{
		$data = array(
			'password_reset_code' => '',
			'password' => $new_password
		);
		$this->db->where('password_reset_code', $id);
		$this->db->update('users', $data);
		return true;
	}

	//--------------------------------------------------------------------
	public function get_admin_detail()
	{
		$id = $this->session->userdata('admin_id');
		$query = $this->db->get_where('users', array('id' => $id));
		return $result = $query->row_array();
	}

	//--------------------------------------------------------------------
	public function update_admin($data)
	{
		$id = $this->session->userdata('admin_id');
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		return true;
	}

	//--------------------------------------------------------------------
	public function change_pwd($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		return true;
	}
}
