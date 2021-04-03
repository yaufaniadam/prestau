<?php
class Pengajuan_model extends CI_Model
{
	public function getVerifiedPengajuan()
	{
		$id_status = " AND ps.status_id = 7";

		$query = $this->db->query(
			"SELECT 
			*,
			FORMAT (ps.date, 'dd/MM/yyyy ') as date,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM Tr_Pengajuan p
			LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN Mstr_Department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM Tr_Pengajuan_Status ps WHERE ps.pengajuan_id = p.pengajuan_id) 
			$id_status"
		);
		return $query->result_array();
	}

	public function getPengajuanPerPeriode($id_periode)
	{
		$query = $this->db->query(
			"SELECT
			*,
			pp.STUDENTID AS mhs_id,
			FORMAT (ps.date, 'dd/MM/yyyy ') as date,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM Tr_Pengajuan p
			LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN Tr_Penerbitan_Pengajuan pp ON pp.id_pengajuan = p.pengajuan_id
			LEFT JOIN V_Mahasiswa m ON m.STUDENTID = pp.STUDENTID
			LEFT JOIN Mstr_Department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM Tr_Pengajuan_Status ps WHERE ps.pengajuan_id = p.pengajuan_id)
			AND pp.id_periode = '$id_periode' "
		);
		return $query->result_array();
	}

	public function get_pengajuan($role)
	{

		if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 5) {
			$prodi = '';
		} else {
			$prodi = "AND u.id_prodi = '" . $this->session->userdata('id_prodi') . "'";
		}

		if ($role == '') {
			$id_status = '';
		} else if ($role == 1) {
			$id_status = "AND ps.status_id =  9";
		} else if ($role == 2) {
			$id_status = "AND (ps.status_id =  2 OR ps.status_id = 5 OR ps.status_id = 7)";
		} else if ($role == 5) {
			$id_status = "AND ps.status_id =  8";
		} else if ($role == 6) {
			$id_status = "AND (ps.status_id =  3 OR ps.status_id = 7)";
		}

		$query = $this->db->query(
			"SELECT 
			*,
			FORMAT (ps.date, 'dd/MM/yyyy ') as date,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM Tr_Pengajuan p
			LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN Mstr_Department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM Tr_Pengajuan_Status ps WHERE ps.pengajuan_id = p.pengajuan_id) 
			$id_status"
		);
		return $result = $query->result_array();
	}

	public function pengajuan_perlu_diproses()
	{
		return $this->db->query("SELECT * FROM Tr_Pengajuan p 
		LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
		WHERE ps.status_id != 1 
		AND ps.status_id != 10")->num_rows();
	}

	public function pengajuan_selesai()
	{
		return $this->db->query("SELECT * FROM Tr_Pengajuan p 
		LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
		WHERE ps.status_id != 1 
		AND ps.status_id = 10")->num_rows();
	}

	public function get_arsip_pengajuan($DEPARTMENT_ID = 0, $ID_JENIS_PENGAJUAN = 0)
	{
		$query = $this->db->query(
			"SELECT 
			*,
			FORMAT (ps.date, 'dd/MM/yyyy ') as date,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM Tr_Pengajuan p
			LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
			-- LEFT JOIN Mstr_Department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			LEFT JOIN Mstr_Department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = 10"
				// . ($DEPARTMENT_ID == 0 ? "" : "AND d.DEPARTMENT_ID = $DEPARTMENT_ID")
				. ($DEPARTMENT_ID == 0 ? "" : "AND m.DEPARTMENT_ID = '$DEPARTMENT_ID'")
				. ($ID_JENIS_PENGAJUAN == 0 ? "" : " AND jp.Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN")
		);
		return $query->result_array();
	}

	public function getbulan()
	{
		return $this->db->query(
			"SELECT 
			distinct(FORMAT (ps.date, 'MMMM')) AS bulan 
			FROM Tr_Pengajuan_Status ps
			WHERE ps.status_id = 2 
			AND FORMAT (ps.date, 'yyyy') = YEAR(getdate())
			ORDER BY bulan DESC
			"
		)->result_array();

		// SELECT 
		// distinct(FORMAT (ps.date, 'MMMM')) AS bulan 
		// FROM Tr_Pengajuan_Status ps
		// LEFT JOIN Tr_Pengajuan p ON p.pengajuan_id = ps.pengajuan_id
		// LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
		// WHERE ps.status_id = 2 
		// AND FORMAT (ps.date, 'yyyy') = YEAR(getdate())
		// AND m.DEPARTMENT_ID = '1'
		// ORDER BY bulan DESC
	}

	public function get_detail_pengajuan($pengajuan_id)
	{
		return $this->db->query(
			"SELECT *
			FROM Tr_Pengajuan p
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id 		
			LEFT JOIN Tr_Pengajuan_Status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN Tr_Status s ON s.status_id = ps.status_id
			LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN Mstr_Department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE p.pengajuan_id = $pengajuan_id 
			AND s.status_id = (
				SELECT status_id FROM Tr_Pengajuan_Status 
				WHERE status_pengajuan_id = (
				SELECT MAX(status_pengajuan_id) FROM Tr_Pengajuan_Status 
				WHERE pengajuan_id = $pengajuan_id
				)
			)"
		)->row_array();
		// return $pengajuan_id;
	}

	public function get_no_pengajuan($id_pengajuan)
	{
		// $no_pengajuan = $this->db->query("select ns.no_pengajuan, ns.instansi, kts.kode, ts.kode_tujuan, us.kode as kode_us, DATE_FORMAT(tanggal_terbit, '%c') as bulan, DATE_FORMAT(tanggal_terbit, '%Y') as tahun, DATE_FORMAT(tanggal_terbit, '%c %M %Y') as tanggal_full from no_pengajuan ns 
		// 	LEFT JOIN kat_tujuan_pengajuan kts ON kts.id=ns.kat_tujuan_pengajuan
		// 	LEFT JOIN tujuan_pengajuan ts ON ts.id=ns.tujuan_pengajuan
		// 	LEFT JOIN urusan_pengajuan us ON us.id=ns.urusan_pengajuan
		// 	where ns.id_pengajuan= $id_pengajuan
		//     ")->row_array();

		// return $no_pengajuan;
	}

	public function get_jenis_pengajuan()
	{
		$query = $this->db->query("SELECT * FROM dbo.Mstr_Jenis_Pengajuan");

		return $result = $query->result_array();
	}

	public function get_jenis_pengajuan_byid($id)
	{
		$query1 = $this->db->query("SELECT * FROM dbo.Mstr_Jenis_Pengajuan where Jenis_Pengajuan_Id='$id'");
		$result1 = $query1->row_array();

		$query2 = $this->db->query("SELECT field_id FROM Tr_Pengajuan_Field where Jenis_Pengajuan_Id=$id AND terpakai = 1");
		$result2 = $query2->result_array();

		return array($result1, $result2);
	}

	public function edit_kategori_pengajuan($data, $id)
	{
		return $this->db->update('kategori_pengajuan', $data, array('id' => $id));
	}

	public function editFieldsPengajuan($dataFieldCheck, $id)
	{
		$not_exist_fields_data = $dataFieldCheck['not_exist_fields_data'];
		$sent_fields_data = $dataFieldCheck['sent_fields_data'];

		foreach ($sent_fields_data as $key => $field) {

			$data = [
				'Jenis_Pengajuan_Id' => $id,
				'field_id' => $field,
				'terpakai' => 1,
				'urutan' => $key
			];

			echo '<pre>';
			print_r($data);
			echo '</pre>';

			// menambahkan field yang belum ada
			$datafield_exist = $this->db->query(
				"SELECT field_id FROM Tr_Pengajuan_Field 
									WHERE Jenis_Pengajuan_Id = $id AND field_id IN (
										SELECT field_id FROM Tr_Pengajuan_Field 
										WHERE Jenis_Pengajuan_Id = $id AND field_id = " . $data['field_id'] . " )"
			)->num_rows();

			if ($datafield_exist == 0) {
				$this->db->insert('Tr_Pengajuan_Field', $data);
			} else {
				$field_property = [
					'terpakai' => 1,
					'urutan' => $key
				];

				$this->db->update(
					'Tr_Pengajuan_Field',
					$field_property,
					[
						'Jenis_Pengajuan_Id' => $id,
						'field_id' => $data['field_id']
					]
				);
			}
			//1,3,69,70,71,72,73

			//mengecek field yang tidak digunakan
			// $id_field = $data['field_id'];
		}

		$query_fields = $this->db->query(
			"SELECT field_id FROM Tr_Pengajuan_Field 
			WHERE Jenis_Pengajuan_Id = $id 
			-- AND field_id = $field
			 AND field_id NOT IN ($not_exist_fields_data)"
		);

		$non_exist_fields = $query_fields->result_array();

		$field_property = [
			'terpakai' => 0
		];

		if ($query_fields->num_rows() > 0) {
			foreach ($non_exist_fields as $field_tidak_dipakai) {
				$this->db->update(
					'Tr_Pengajuan_Field',
					$field_property,
					[
						'Jenis_Pengajuan_Id' => $id,
						'field_id' => $field_tidak_dipakai['field_id']
					]
				);
			}
		}

		// return $non_exist_fields;
		// return $datafield_exist;
	}

	public function tambah_field_pengajuan($data)
	{
		return $this->db->insert('Tr_Pengajuan_Field', $data);
	}

	public function tambah_jenis_pengajuan($data)
	{
		return $this->db->insert('Mstr_Jenis_Pengajuan', $data);
	}

	public function get_pengajuan_status($id_pengajuan)
	{
		return $this->db->select('ss.*, DATE_FORMAT(ss.date,"%d %M %Y") as date, st.status')
			->from('pengajuan_status ss')
			->join('status st', 'ss.id_status=st.id', 'left')
			->where(array('ss.id_pengajuan' => $id_pengajuan, 'ss.id_status !=' => '0', 'ss.id_status !=' => '1'))->get()->result_array();
	}

	//class Kategori
	public function get_kat_keterangan_pengajuan()
	{
		return $this->db->get('kat_keterangan_pengajuan')->result_array();
	}

	public function get_timeline($id_pengajuan)
	{
		$query = $this->db->query("SELECT ss.id_status, DATE_FORMAT(ss.date, '%d %M') as date,  DATE_FORMAT(ss.date, '%H:%i') as time,  DATE_FORMAT(ss.date, '%d %M %Y') as date_full, s.status, s.badge          
        FROM pengajuan_status ss
        LEFT JOIN status s ON s.id = ss.id_status  
        where ss.id_pengajuan='$id_pengajuan'
        ORDER BY ss.id DESC
        ");
		return $result = $query->result_array();
	}

	public function getAllFieldsPengajuan()
	{
		$query = $this->db->query("SELECT * FROM Mstr_Fields");
		return $result = $query->result_array();
	}

	public function edit_jenis_pengajuan($data, $id)
	{
		return $this->db->update('Mstr_Jenis_Pengajuan', $data, array('Jenis_Pengajuan_Id' => $id));
	}
}
