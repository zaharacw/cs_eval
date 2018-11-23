<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Upload extends MY_Controller
{
    var $data_keys = array('subject', 'number', 'section', 'title', 'instructor', 'quarter', 'year', 'eval_start_date', 'eval_end_date');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Info_upload_model');
        $this->load->model('Course_model', 'courses');
    }

    public function index()
    {
        $courses = $this->session->userdata('courses');
        $data['term'] = $this->courses->getCurrentTermName();
        $data['subjects'] = implode(', ', $this->courses->getCurrentSubjects());

        if ($courses != null)
        {
            $data['courses'] = $courses;
            $data['fluid'] = true;
            $this->load->view('infoUpload_view', $data);
        }
        else
        {
            $this->load->view('prepare_upload_view', $data);
        }
    }

    public function retrieval_postback()
    {
        ob_clean();
        $status = $this->Info_upload_model->LoadCourseData();

        if ($status == null)
        {
            $this->showError();
        }
        else
        {
            $this->index();
        }
    }

    public function submission_postback()
    {
        ob_clean();
        try
        {
            $parseIndex = function ($x)
            {
                $result = str_replace('upload-course-', '', $x);

                return intval($result);
            };

            $data = $this->input->post('course_list');

            if ($data != null)
            {
                $indices = explode(',', $data);
                $indices = array_map($parseIndex, $indices);
                $this->Info_upload_model->UploadCourses($indices);
            }

            $this->Info_upload_model->UnloadCourseData();
            $this->index();
        }
        catch (Exception $e)
        {
            $this->showError();
        }
    }

    private function showError()
    {
        $data['message'] = 'Something went wrong. Are you connected to a secure EWU network?';
        $this->load->view('error_view', $data);
    }
}