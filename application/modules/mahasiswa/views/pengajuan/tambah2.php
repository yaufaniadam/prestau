<?php
echo "<pre>";
print_r($pengajuan);
echo "<br>";
print_r($this->db->query(
	"SELECT * FROM Tr_Pengajuan_Field pf
	LEFT JOIN Mstr_Fields f ON f.field_id = pf.field_id
	WHERE pf.Jenis_Pengajuan_Id = $pengajuan->Jenis_Pengajuan_Id"
)->result_array());
echo "</pre>";
