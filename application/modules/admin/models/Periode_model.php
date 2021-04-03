<?php
class Periode_model extends CI_Model
{
	public function getPeriode($status)
	{
		$this->db->select('*');
		$this->db->from('Tr_Periode_Penerbitan');
		if ($status != '' || $status !== NULL) {
			$this->db->where('status', $status);
		}
		return $this->db->get()->result_array();
	}

	public function tambahPeriode($data)
	{
		return $this->db->insert('Tr_Periode_Penerbitan', $data);
	}
}
