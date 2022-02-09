<?php defined('BASEPATH') or exit('No direct script access allowed');


class Prestasi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();     
    }

    public function index($DEPARTMENT_ID = 0, $ID_JENIS_PENGAJUAN = 0)
    {

        $department_data = $this->db->query("SELECT * FROM mstr_department")->result_array();
		$kategori_data = $this->db->query("SELECT * FROM mstr_jenis_pengajuan WHERE Jenis_Pengajuan_Id != 12")->result_array();

		$data['departments'] = $department_data;
		$data['kategories'] = $kategori_data;

		$data['button_text'] = $DEPARTMENT_ID == 0 ? 'Semua Prodi' : $this->db->query(
			"SELECT NAME_OF_DEPARTMENT 
			FROM mstr_department 
			WHERE DEPARTMENT_ID = $DEPARTMENT_ID"
		)->row_object()->NAME_OF_DEPARTMENT;

		$data['button_text_2'] = $ID_JENIS_PENGAJUAN == 0 ? 'Semua Kategori' : $this->db->query(
			"SELECT Jenis_Pengajuan 
			FROM mstr_jenis_pengajuan 
			WHERE Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN"
		)->row_object()->Jenis_Pengajuan;

        $prestasi = $this->db->query('SELECT * FROM v_prestasi 
            WHERE status = 1 '
            	. ($DEPARTMENT_ID == 0 ? "" : "AND DEPARTMENT_ID = '$DEPARTMENT_ID'")
				. ($ID_JENIS_PENGAJUAN == 0 ? "" : " AND Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN") 
            )->result_array();
        
        $data['daftar_prestasi'] = $prestasi;
        $data['title'] = 'Daftar Prestasi & Rekognisi';
        $data['view'] = 'admin/prestasi/index';
        $data['menu'] = 'prestasi';    
        $this->load->view('layout/layout', $data);
		//masa beda?
    }

    public function detail($id_penerbitan_pengajuan = 0)
	{
		

		$query = $this->db->select('*')
			->from('v_prestasi')
		
			->where(
				[
					'id_penerbitan_pengajuan' => $id_penerbitan_pengajuan
				]
			)
			->get()
			->row_array();

		$data['prestasi'] = $query;
        $data['title'] = 'Prestasi & Rekognisi';
        $data['view'] = 'admin/prestasi/detail_prestasi';
        $data['menu'] = 'prestasi'; 

		$this->load->view('layout/layout', $data);
	}

}
