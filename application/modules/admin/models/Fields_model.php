<?php
class fields_model extends CI_Model
{
	public function index()
	{
		return $this->db->get('mstr_fields')->result_array();
	}

	public function insert($data)
	{
		$this->db->insert('mstr_fields', $data);
		return true;
	}

	public function detail($id_field)
	{
		return $this->db->get_where('mstr_fields', array('field_id' => $id_field))->row_object();
	}

	public function update($data, $id_field)
	{
		$this->db->update('mstr_fields', $data, array('field_id' => $id_field));
		return true;
	}
}
