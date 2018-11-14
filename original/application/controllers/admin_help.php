<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Admin_help extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('admin_help_view');
    }
}