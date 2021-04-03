<?php defined('BASEPATH') or exit('No direct script access allowed');
class Layout extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    //--------------------------------------------------------------
    public function error404()
    {
        $data['title'] = 'Error!';

        if ($this->session->has_userdata('is_login')) {
            $data['view'] = 'error404';
            $this->load->view('layout/layout', $data);
        }
    }

    public function upload()
    {

        $data['title'] = 'Upload';
        $data['view'] = 'upload';
        $this->load->view('layout/layout', $data);
    }

    public function doupload()
    {
       
            header('Content-type:application/json;charset=utf-8');
            $upload_path = './uploads/dokumen';

            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }

            $config = array(
                'upload_path' => $upload_path,
                'allowed_types' => "jpg|png",
                'overwrite' => FALSE,
            );

            $this->load->library('upload', $config);


            if (!$this->upload->do_upload('file')) {
                $error = array('error' => $this->upload->display_errors());

                echo json_encode([
                    'status' => 'error',
                    'message' => $error
                ]);
            } else {
                $data = $this->upload->data();

                echo json_encode([
                    'status' => 'ok',
                    'path' => $upload_path . '/' . $data['file_name']
                ]);
            }
        }
    
}
