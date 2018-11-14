<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Evaluations extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Evaluation_model', 'evaluation');
        $this->load->model('Questions_model', 'questions');
        $this->load->model('Course_model');
        $this->load->model('Settings_model');
        $this->load->helper('url');
    }

    /*
     * Name: index
     * Description: gets all of the questions for selected course and passes it to new/saved view
     * This is done like every other one, SQL used looped into a data array and that is pushed
     * to the view.
     * Parameters: course id, user type ex admin
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function index($section_id = false, $userType = 'student')
    {
        if (!$section_id)
        {
            $this->load->view('error_view', array('message' => 'No course specified.'));

            return;
        }

        $student_data = $this->session->userdata('student');
        $userInstId = '';

        try
        {
            $instructor_data = $this->session->userdata('instructor');
            $userInstId = $instructor_data['inst_id_hashed'];
        }
        catch (Exception $e)
        {
            $userInstId = '';
        }


        $data['s_id'] = $student_data['username'];
        $data['section_id'] = $section_id;
        $data['viewingType'] = $userType;

        $eval = $this->evaluation->get($section_id);

        if (!$eval->is_submitted($data['s_id']) || $userType == 'admin' || $userType == 'instructor')
        {
            $course = $this->Course_model->getSection($section_id);

            if ($course == null)
            {
                $this->load->view('error_view', array('message' => 'Course does not exist.'));

                return;
            }

            // make sure they have permission
            if ($userType == 'instructor')
            {
                if ($userInstId != $course->instructor)
                {
                    $this->load->view('error_view', array('message' => 'You are not the instructor of this course.'));
                    return;
                }
            }
            elseif ($userType != 'admin')
            {
                $userHasPermission = $this->isEnrolled($section_id);

                if ($course->inst_id_hashed == $data['s_id'])
                {
                    $userHasPermission = false;
                }
                
                if (!$userHasPermission || !$course->isAvailableForEvaluation(false))
                {
                    // we want cryptic messages... they don't need to know it DOES exist
                    $this->load->view('error_view', array('message' => 'Course does not exist.'));

                    return;
                }
            }

            $required_questions = $this->questions->getRequired();
            $course_questions = $this->questions->forSection($section_id, true);

            $i = 1;
            foreach ($required_questions as $row)
            {
                $data['question']['description' . $i] = $row->description;
                $data['question']['q_id' . $i] = $row->q_id;
                $i++;
            }

            foreach ($course_questions as $row)
            {
                $data['question']['description' . $i] = $row->description;
                $data['question']['q_id' . $i] = $row->q_id;
                $i++;
            }

            $data['num_questions'] = $i - 1;
            $data['course'] = $course;

            $settings = $this->Settings_model->loadSettings();
            $data['message'] = $settings->evalMessage;

            if ($eval->is_saved($data['s_id']))
            {
                $this->loadSaved($data);
            }
            else
            {
                $this->load->view('evaluation_view', $data);
            }
        }
        else
        {
            $this->load->view('error_view', array('message' => 'You are not currently authorized to submit an evaluation for this course.'));

            return;
        }
    }

    /*
     * Name: loadSaved
     * Description: Loads saved evaluation form
     * Parameters: a 2d array of questions and answers.
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    private function loadSaved($data)
    {
        $eval = $this->evaluation->get($data['section_id']);

        for ($i = 1; $i <= $data['num_questions']; $i++)
        {
            $answer = $eval->get_answer($data['question']['q_id' . $i], $data['s_id']);

            $data['question']['answer' . $i] = $answer->answer;
            $data['question']['comment' . $i] = $answer->comments;
        }
        $data['comments'] = $eval->get_general_comment($data['s_id']);

        $this->load->view('evaluation_view', $data);
    }

    private function isEnrolled($section_id)
    {
        $sections = $this->session->userdata('sections');
        
        if ($sections != null)
        {
            foreach ($sections as $section)
            {
                if ($section_id == $section)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /*
     * Name: postback
     * Description: Detects postbacks from evaluation form
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function postback()
    {
        $student_data = $this->session->userdata('student');
        $s_id = $student_data['username'];

        $section_id = $this->input->post('section_id');
        $num_questions = $this->input->post('num_questions');

        if (!$this->isEnrolled($section_id))
        {
            $this->load->view('error_view', array('message' => 'You are not enrolled in this course.'));

            return;
        }

        $eval = $this->evaluation->get($section_id);
        $postback['comments'] = $this->input->post('comments');

        if ($eval->is_submitted($s_id))
        {
            $this->load->view('error_view', array('message' => 'You have already submitted an evaluation for this course.'));

            return;
        }

        if ($this->input->post('submit'))
        {
            $postback['submitted'] = 1;
            $this->session->set_flashdata('alert', 'Your evaluation has been submitted successfully. Thank you!');
            $this->session->set_flashdata('alert_type', 'alert-success');
        }
        elseif ($this->input->post('save'))
        {
            $postback['submitted'] = 0;
            $this->session->set_flashdata('alert', 'Your changes have been saved, but to complete the evaluation you must click the <strong>Submit</strong> button.');
            $this->session->set_flashdata('alert_type', 'alert-info');
        }

        for ($i = 1; $i <= $num_questions; $i++)
        {
            if ($this->input->post('question' . $i) == 0)
            {
                $postback['question' . $i] = null;
            }
            else
            {
                $postback['question' . $i] = $this->input->post('question' . $i);
            }

            $postback['comment' . $i] = $this->input->post('comment' . $i);
            $postback['q_id' . $i] = $this->input->post('q_id' . $i);
        }

        if ($this->input->post('submit') || $this->input->post('save'))
        {
            $eval->add_entry($postback, $num_questions, $s_id);
        }

        redirect(base_url('student_home'));
    }
}