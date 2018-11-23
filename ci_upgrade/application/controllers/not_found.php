<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/*
    This is just a message that you don't have permissions
*/

class Not_found extends CI_Controller
{
    public function index()
    {
        $this->load->view('error_view', array('message' => 'The page you requested cannot be found.'));
    }
}