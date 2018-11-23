<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Course_questions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Questions_model', 'questions');
        $this->load->model('Course_model');
    }

    /**
     * Display list of course questions
     */
    public function index()
    {
        $rawQuestions = $this->questions->getCourseSpecific();

        $data['questions'] = $this->foldQuestions($rawQuestions);

        $data['results2'] = $this->Course_model->getDistinct(
            array('course_subject', 'course_num', 'title', 'course_section', 'section_id'),
            true
        );

        $this->load->view('admin_course_questions_view', $data);
    }

    /**
     * Gather relevant information into one place
     */
    private function foldQuestions($input)
    {
        $questions = array();
        foreach ($input as $row)
        {
            if (!array_key_exists($row->q_id, $questions))
            {
                // push new question to hashtable
                $questions[$row->q_id] = array('type'        => $row->type(),
                                               'description' => $row->description,
                                               'sections'    => array($row->section_id));
            }
            else
            {
                // question already exists, so push new section to it
                $questions[$row->q_id]['sections'][] = $row->section_id;
            }
        }

        return $questions;
    }

    /**
     * POST: courses, description, type
     */
    public function add()
    {
        ob_clean();
        $course = $this->input->post('courses');
        $description = $this->input->post('description');
        $type = $this->input->post('type');

        if ($this->hasNull(array($course, $description, $type)))
        {
            return;
        }

        if (!$this->questions->duplicateExists($description))
        {
            $qid = $this->questions->add($description, $type, 'admin');

            foreach ($course as $section)
            {
                $this->questions->addCourseSpecific($qid, $section);
            }
            echo $qid;
        }

        else
        {
            $notValid = 0;
            echo $notValid;
        }
    }

    /**
     * POST: courses, description, type, qid
     */
    public function modify()
    {
        ob_clean();
        $course = $this->input->post('courses');
        $description = $this->input->post('description');
        $type = $this->input->post('type');
        $q_id = $this->input->post('qid');

        if ($this->hasNull(array($course, $description, $type, $q_id)))
        {
            return;
        }

        if (!$this->questions->duplicateExists($description, $q_id))
        {
            $this->questions->archive($q_id);
            $newQid = $this->questions->add($description, $type, 'admin');

            foreach ($course as $c)
            {
                $this->questions->addCourseSpecific($newQid, substr($c, 0, 4), substr($c, 4, 8));
            }

            echo $newQid;
        }

        else
        {
            $notValid = 0;
            echo $notValid;
        }
    }

    /**
     * POST: qid
     */
    public function remove()
    {
        ob_clean();
        $q_id = $this->input->post('qid');

        if ($q_id == null)
        {
            return;
        }

        $this->questions->archive($q_id);
        echo $q_id;
    }
}