<?php defined('BASEPATH') or exit('No direct script access allowed');


class Prestasi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();     
    }

    public function index()
    {

        $prodinya = $this->session->userdata('id_prodi');

		if ($prodinya == 0) {
			$prodi = '';
		} else {
			$prodi = 'AND DEPARTMENT_ID = ' . $prodinya;
		}


        $department_data = $this->db->query("SELECT * FROM mstr_department")->result_array();
		$kategori_data = $this->db->query("SELECT * FROM mstr_jenis_pengajuan WHERE Jenis_Pengajuan_Id != 12")->result_array();

		$data['departments'] = $department_data;
		$data['kategories'] = $kategori_data;

		

        $prestasi = $this->db->query("SELECT * FROM v_prestasi 
            WHERE status = 1  " . $prodi )->result_array();
        
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
