<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class User
{
    var $CI;

    public function __construct($config = array())
    {
        $this->CI =& get_instance();
        if (count($config) > 0)
        {
            $this->initialize($config);
        }
        $this->CI->load->model('User_model');
        $this->CI->load->model('Info_upload_model');
        $this->CI->load->model('Course_model');
    }

    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $this->$key = $val;
            }
        }
    }

    public function getUserInfo()
    {
        $users = array();

        $adminUser = $this->isAdmin();
        if ($adminUser['valid'])
        {
            $users[] = $adminUser;
        }

        $instructorUser = $this->isInstructor();
        if ($instructorUser['valid'])
        {
            $users[] = $instructorUser;
        }

        $studentUser = $this->isStudent();
        if ($studentUser['valid'])
        {
            $users[] = $studentUser;
        }

        return $users;
    }

    public function isAdmin()
    {
        $user = null;
        $username = $this->CI->cas->get_user();

        if ($this->CI->User_model->isAdmin($username))
        {
            $user = $this->CI->User_model->getAdmin($username);
        }

        $user['group'] = 'admin';

        return $user;
    }

    public function isInstructor()
    {
        $user = null;
        $attributes = $this->CI->cas->get_attributes();
        $id = do_hash($attributes['Ewuid'], 'md5');

        if ($this->CI->User_model->instructorExists($id))
        {
            $user = $this->CI->User_model->getInstructor($id);
            $user['name'] = $user['first_name'] . ' ' . $user['last_name'];
        }

        return $user;
    }


    /*
     * Must get $current_year and $current_quarter from a feed.
     * And specific subjects. Can't be hardcoded.
     */
    public function isStudent()
    {
        $subject = 'subject';
        $number = 'number';
        $section = 'section';
        $year = 'year';
        $quarter = 'quarter';
        $user = null;

        $attributes = $this->CI->cas->get_attributes();
        $groups = $attributes['Groups'];

        $pattern = '/.+\-(?P<subject>[[:alpha:]]+)(?P<number>[[:digit:]]+)\-(?P<section>[[:digit:]]{2})\-(?P<year>[[:digit:]]{4})(?P<quarter>[[:digit:]]{2})/';

        $count = 0;

        foreach ($groups as $value)
        {
            $success = preg_match($pattern, $value, $matches);
            if ($success)
            {
                if (in_array($matches["$subject"], $this->CI->Course_model->getCurrentSubjects())
                    && $matches["$year"] == date('Y')
                    && $matches["$quarter"] == $this->CI->Course_model->getCurrentTerm())
                {
                    $user['student-course-' . $count] = array(
                        $subject => $matches[$subject],
                        $number  => $matches[$number],
                        $section => $matches[$section],
                        $quarter => $matches[$quarter],
                        $year    => $matches[$year]);

                    $count++;
                }
            }
        }

        if (!$count)
        {
            $user = array('username' => '', 'group' => '', 'valid' => false, 'count' => 0);
        }
        else
        {
            $user['username'] = md5($attributes['Ewuid']);
            $user['group'] = 'student';
            $user['valid'] = true;
            $user['count'] = $count;
        }

        return $user;
    }
}

?>