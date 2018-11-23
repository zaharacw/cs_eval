<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Auth extends CI_Controller
{
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

    public $account_priority = array('none' => 0, 'student' => 1, 'instructor' => 2, 'admin' => 3);
    public $data_keys = array('subject', 'number', 'year', 'section', 'quarter');

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Log a user out of the SSO CAS system.
     */
    public function logout()
    {
        $this->cas->logout();
    }

    /**
     * Attempt to redirect the user back to the page that they had
     * originally requested before hitting the authentication wall.
     */
    public function redirect()
    {
        // Authenticate User
        $this->cas->force_authenticate();
        // Get information concerning the authenticated user
        $userAccounts = $this->user->getUserInfo();

        if (count($userAccounts) > 0)
        {
            $class = $this->session->userdata('class');
            $this->session->unset_userdata('class');
            $url = $this->session->userdata('url');
            $this->session->unset_userdata('url');
            $default_account = 'none';
            $repackaged_data = array();
            $groups = array();
            foreach ($userAccounts as $userAccount)
            {
                $group = $userAccount['group'];
                $groups[] = $group;

                if ($this->account_priority[$group] > $this->account_priority[$default_account])
                {
                    $default_account = $group;
                }

                $repackaged_data = array_merge($repackaged_data, $this->repackageUserData($userAccount));
            }
            $this->session->set_userdata('groups', $groups);
            $this->session->set_userdata($repackaged_data);
            if ($url == base_url() || in_array($class, $this->homeControllers))
            {
                redirect(base_url() . $this->homeControllers[$default_account]);
            }

            else
            {
                $acceptableControllers = array();
                foreach ($groups as $g)
                {
                    $acceptableControllers = array_merge($acceptableControllers, $this->nonHomeControllers[$g]);
                }

                if (in_array($class, $acceptableControllers))
                {
                    redirect($url);
                }
                else
                {
                    redirect(base_url() . 'invalid_user');
                }
            }
        }
        else
        {
            redirect(base_url() . 'invalid_user');
        }
    }

    // A function that is necessary for packaging the properties related
    // to respective courses into arrays. This is an optional function.
    private function repackageUserData($data)
    {
        $student_course_key = 'student-course-';
        $repackaged_data = array();
        $group = $data['group'];
        if ($group == 'student')
        {
            $course_count = $data['count'];
            for ($i = 0; $i < $course_count; $i++)
            {
                $student_course_key_sub_i = $student_course_key . $i;
                $repackaged_data[$student_course_key_sub_i] = $data[$student_course_key_sub_i];
                unset($data[$student_course_key_sub_i]);
            }
        }
        $repackaged_data[$group] = $data;
        $repackaged_data[$group]['controller'] = $this->homeControllers[$group];

        return $repackaged_data;
    }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */