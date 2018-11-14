<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Manage_course extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Course_model', 'courses');
        $this->load->model('User_model', 'users');
    }

    public function index()
    {
        $data['fluid'] = true;
        $data['instructors'] = $this->Course_model->getAllInstructors();
        $data['courses'] = $this->Course_model->getEditable();
        $this->Course_model->sortCourses($data['courses']);

        $this->load->view('manage_course_view', $data);
    }

    public function cleanup()
    {
        ob_clean();
        $cid = $this->input->post('cid');

        if ($cid == null)
        {
            return;
        }

        $this->courses->removeSection($cid);
        echo $cid;
    }

    public function duplicate_course()
    {
        ob_clean();
        $inst = $this->input->post('newInst');
        $cid = $this->input->post('cid');

        if ($this->hasNull(array($inst, $cid)))
        {
            echo 0;
            return;
        }

        if ($this->users->instructorExists($inst))
        {
            $course = $this->courses->getSection($cid);
            $course->instructor = $inst;
            echo $course->forceAdd() ? 1 : 0;
            return;
        }

        echo 0;
    }

    public function update_instructor()
    {
        ob_clean();
        $inst = $this->input->post('newInst');
        $cid = $this->input->post('cid');
        if ($this->hasNull(array($inst, $cid)))
        {
            return;
        }

        if ($this->users->instructorExists($inst))
        {            
            $course = $this->courses->getSection($cid);
            $prevInstr = $course->instructor;
            if($prevInstr != $inst){
                $course->modified();
            }
            $course->updateInstructor($inst);

            $instData = $this->users->getInstructor($inst);
            // TO DO, change below formatting ??
            //echo $instData['first_name'] . ' ' . $instData['last_name'];
            echo $instData['last_name'] . ', ' . $instData['first_name'];
        }
    }
}