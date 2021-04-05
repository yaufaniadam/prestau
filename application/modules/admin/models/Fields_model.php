<?php
class fields_model extends CI_Model
{
	public function index()
	{
		return $this->db->get('Mstr_Fields')->result_array();
	}

	public function insert($data)
	{
		$this->db->insert('Mstr_Fields', $data);
		return true;
	}

	public function detail($id_field)
	{
		return $this->db->get_where('Mstr_Fields', array('field_id' => $id_field))->row_object();
	}

	public function update($data, $id_field)
	{
		$this->db->update('Mstr_Fields', $data, array('field_id' => $id_field));
		return true;
	}
}
