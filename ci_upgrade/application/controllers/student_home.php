<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Student_home extends MY_Controller
{
    const STUDENT_COURSE_KEY = 'student-course-';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Evaluation_model', 'evaluation');
        $this->load->model('Course_model');
        $this->load->model('Settings_model');
    }

    /**
     * Gathers all information about this student's courses.
     */
    public function index()
    {
        $student_data = $this->session->userdata('student');
        $courseCount = $student_data['count'];
        $userHash = $student_data['username'];

        $courses = array();
        $sections = array();

        for ($i = 0; $i < $courseCount; $i++)
        {
            $tempData = $this->session->userdata(self::STUDENT_COURSE_KEY . $i);
            $courseData = array(
                'course_subject'   => $tempData['subject'],
                'course_num'       => $tempData['number'],
                'course_section'   => $tempData['section'],
                'term'             => $tempData['quarter'],
                'YEAR(start_date)' => $tempData['year'],
            );

            $dbCourses = $this->Course_model->getCurrent($courseData);

            foreach ($dbCourses as $row)
            {
                if ($row->inst_id_hashed == $userHash)
                {
                    continue;
                }
                
                $courses[] = $row;
                $sections[] = $row->section_id;
            }
        }

        $this->session->set_userdata('sections', $sections);

        $data['courses'] = $courses;
        $data['student_id'] = $student_data['username'];

        $settings = $this->Settings_model->loadSettings();
        $data['message'] = $settings->mainMessage;

        $data['alert'] = $this->session->flashdata('alert');
        $data['alert_type'] = $this->session->flashdata('alert_type');
        $this->load->view('student_home_view', $data);
    }
}