<?php defined('BASEPATH') or exit('No direct script access allowed');
class Admin_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('is_login')) {
            redirect('admin/login/prodi', 'refresh');
        } else {
            if ($this->session->userdata('role') == 3) {
                redirect('kesalahan', 'refresh');
            }
        }
    }
}
