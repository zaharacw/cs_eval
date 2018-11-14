<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Sample_evaluations extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Course_model');
    }

    public function index()
    {
        $data['courses'] = $this->Course_model->getCurrent();
        $this->load->view('sample_eval_view', $data);
    }
}