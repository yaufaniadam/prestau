<?php
class Notif_model extends CI_Model
{
	public function get_notif()
	{
		// if ($_SESSION['role'] == 1) {
		// 	$where = "n.role = 1";
		// } else if ($_SESSION['role'] == 2) {
		// 	$where = "n.role = 2 AND n.id_prodi = " . $_SESSION['id_prodi'];
		// } else if ($_SESSION['role'] == 3) {
		// 	$where = "n.role = 3 AND n.kepada = " . $_SESSION['user_id'];
		// } else if ($_SESSION['role'] == 4) {
		// 	$where = "n.role = 4 AND n.kepada = " . $_SESSION['user_id'];
		// } else if ($_SESSION['role'] == 5) {
		// 	$where = "n.role = 5";
		// } else if ($_SESSION['role'] == 6) {
		// 	$where = "n.role = 6 AND n.id_prodi = " . $_SESSION['id_prodi'];
		// }
		
		$query = $this->db->select('*')->from('v_notif')
			// ->where($where)
			->order_by('id_notif', 'desc')
			->get();

		return $query;
	}

	public function send_notif($data)
	{
		$id_status = $data['id_status'];


		$date = date("Y-m-d h:m:s");

		$notif = array();
		foreach ($data['role'] as $role) {
			$notif[] = array(
				"role" => $role,
				"id_pengajuan" => $data['id_pengajuan'],
				"pengirim" => '',
				"penerima" => $data['penerima'],
				"tanggal_masuk" => $date,
				'id_status_notif' => $data['id_status_notif'],
				'status' => 0,
			);
		}
		$result = $this->db->insert_batch('tr_notif', $notif);

		return $result;
	}

	//get status pesan by role dan status
	private function get_status_pesan($role, $id_status)
	{
		$status = $this->db->get_where('tr_pengajuan_status', array('id_status' => $id_status))->row_array();
		return $status['status_pengajuan_id'];
	}

	public function get_messages($role, $id_status)
	{
		$status = $this->db->get_where('mstr_status_pesan', array('id_status' => $id_status, 'role' => $role))->row_array();
		return $status;
	}
}
