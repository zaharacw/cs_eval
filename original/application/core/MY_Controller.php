<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller
{
    const SHOW_EDITABLE_ONLY = 0;
    const SHOW_ALL = 1;

    public $nonHomeControllers = array(
        'open'       => array('auth'),
        'admin'      => array('about', 'required_questions', 'admins', 'instructors', 'reports', 'course_questions', 'admin_instructor_questions', 'upload', 'sample_evaluations', 'evaluations', 'settings', 'manage_course', 'evaluation_period', 'admin_help'),
        'student'    => array('evaluations', 'about'),
        'instructor' => array('instructor_questions', 'about'));

    public $homeControllers = array(
        'student'    => 'student_home',
        'admin'      => 'admin_home',
        'instructor' => 'instructor_home',
        'default'    => 'default_controller');

    public $account_view = 'account_view';
    public $user_groups = array('student', 'instructor', 'admin');

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $array = $this->session->all_userdata();

        if (!isset($array['groups']))
        {
            $query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $myUrl = base_url() . $this->uri->uri_string() . $query;

            $this->session->set_userdata('url', $myUrl);
            $this->session->set_userdata('class', $this->router->class);
            redirect(base_url() . 'auth/redirect');
        }

        // Interrupt flow to authenticate user
        $this->cas->authenticate();
    }

    protected function hasNull($ara)
    {
        foreach ($ara as $val)
        {
            if ($val == null)
            {
                return true;
            }
        }

        return false;
    }
}