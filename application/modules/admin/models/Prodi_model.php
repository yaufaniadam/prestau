<?php
class Prodi_model extends CI_Model
{
    public function get_prodi()
    {

        $query = $this->db->query("SELECT * FROM prodi");
        return $result = $query->result_array();
    }

    public function get_detail_prodi($id_prodi)
    {
        $query = $this->db->query("SELECT * FROM prodi WHERE id='$id_prodi'");
        return $result = $query->row_array();
    }

    public function get_admin_prodi($id_prodi, $role)
    {
        $query = $this->db->query("SELECT users.* FROM users WHERE users.role=$role AND id_prodi = $id_prodi");
        return $result = $query->result_array();
    }

    public function edit_prodi($data, $id)
    {
        return $this->db->update('prodi', $data, array('id' => $id));
    }
}
