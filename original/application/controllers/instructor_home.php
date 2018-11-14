<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/**
 * @deprecated
 */
class Instructor_home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Course_model');
        $this->load->model('Info_upload_model');
    }

    public function index()
    {
        $instructor_data = $this->session->userdata('instructor');
        $courses = $this->Course_model->getCurrent(array('instructor' => $instructor_data['inst_id_hashed']));

        $data['message'] = 'Instructor functionality is not yet fully implemented.';
        $data['courses'] = $courses;

        $this->load->view('instructor_home_view', $data);
    }
}