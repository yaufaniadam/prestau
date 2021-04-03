<?php defined('BASEPATH') or exit('No direct script access allowed');
class Mahasiswa_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        if (($this->session->userdata('role') != 3)) {
            $data['view'] = 'layout/restricted';
            $this->load->view('layout/layout', $data);
        }
    }
}
