<?php
class Notif_model extends CI_Model
{
	public function get_notif()
	{
		if ($_SESSION['role'] == 1) {
			$where = "n.role = 1";
		} else if ($_SESSION['role'] == 2) {
			$where = "n.role = 2 AND n.id_prodi = " . $_SESSION['id_prodi'];
		} else if ($_SESSION['role'] == 3) {
			$where = "n.role = 3 AND n.kepada = " . $_SESSION['user_id'];
		} else if ($_SESSION['role'] == 4) {
			$where = "n.role = 4 AND n.kepada = " . $_SESSION['user_id'];
		} else if ($_SESSION['role'] == 5) {
			$where = "n.role = 5";
		} else if ($_SESSION['role'] == 6) {
			$where = "n.role = 6 AND n.id_prodi = " . $_SESSION['id_prodi'];
		}
		$query = $this->db->select("n.*, sp.*, n.id as notif_id, DATE_FORMAT(n.tanggal, '%H:%i') as time,  DATE_FORMAT(n.tanggal, '%d %M %Y') as date_full")->from("notif n")
			->join('status_pesan sp', 'n.id_status_pesan = sp.id', 'left')
			->where($where)
			->order_by('n.id', 'desc')
			->get();

		return $query;
	}

	public function send_notif($data)
	{
		// $id_status = $data['id_status'];
		date_default_timezone_set('Asia/Jakarta');
		$date = date("Y/m/d h:i:s");

		$notif = array();
		foreach ($data['role'] as $role) {
			$notif[] = array(
				"role" => $role,
				"id_pengajuan" => $data['id_pengajuan'],
				"pengirim" => $_SESSION['user_id'],
				"penerima" => $data['penerima'],
				"tanggal_masuk" => $date,
				'id_status_notif' => $data['id_status_notif']
				// "id_prodi" => $_SESSION['id_prodi'],
				// "id_status_pesan" => $this->get_status_pesan($role, $id_status),
			);
		}
		$result = $this->db->insert_batch('Tr_Notif', $notif);
		return $result;
	}

	//get status pesan by role dan status
	private function get_status_pesan($role, $id_status)
	{
		$status = $this->db->get_where('Tr_Pengajuan_Status', array('id_status' => $id_status))->row_array();
		return $status['status_pengajuan_id'];
	}
}
