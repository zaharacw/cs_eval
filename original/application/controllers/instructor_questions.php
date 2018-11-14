<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/**
 * @deprecated
 */
class Instructor_questions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Questions_model', 'questions');
        $this->load->model('User_model');
    }

    /*
     * Name: index
     * Description: Gathers all of the admin/instructor questions for instructor questions pages
     * Parameters: $c_id course id, $subject, $number course number, $section number, $quarter, $year
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function index($c_id, $subject, $number, $section, $quarter, $year)
    {
        // show 'not implemented message'
        $data['message'] = 'Instructor functionality is not yet fully implemented.';
        $this->load->view('error_view', $data);

        return;

        //Information to display regarding particular course whose questions are gettting set
        $data['info'] = array('c_id'    => $c_id,
                              'number'  => $number,
                              'subject' => $subject,
                              'section' => $section,
                              'quarter' => $quarter,
                              'year'    => $year);

        $instructor_data = $this->session->userdata('instructor');
        $data['i_id'] = $instructor_data['username'];
        $data['instructor_name'] = $instructor_data['name'];

        // TODO: $optional_questions = $this->questions->getAdminOptionalQuestions($subject, $number);
        // TODO: $course_questions = $this->questions->getCourseSpecificQuestions($subject, $number);
        // TODO: $selected_questions = $this->questions->forSection($c_id);

        $i = 0;
        foreach ($optional_questions->result_array() as $row)
        {
            $data['admin_optional']['type' . $i] = $row['creator_type'];
            $data['admin_optional']['description' . $i] = $row['description'];
            $data['admin_optional']['q_id' . $i] = $row['q_id'];
            $data['admin_optional']['name' . $i] = 'admin';
            $i++;
        }
        $data['admin_optional']['count'] = $i;

        $i = 0;
        foreach ($course_questions->result_array() as $row)
        {
            $name = $this->User_model->getUser($row['creator_type']);
            $data['course_specific']['c_id' . $i] = $row['course_number'];
            $data['course_specific']['type' . $i] = $row['creator_type'];
            $data['course_specific']['description' . $i] = $row['description'];
            $data['course_specific']['q_id' . $i] = $row['q_id'];
            $data['course_specific']['name' . $i] = $name['name'];
            $i++;
        }
        $data['course_specific']['count'] = $i;

        $i = 0;
        foreach ($selected_questions->result_array() as $row)
        {
            if ($row['creator_type'] !== 'admin')
            {
                $name = $this->User_model->getUser($row['creator_type']);
                $data['selected']['type' . $i] = $row['creator_type'];
                $data['selected']['description' . $i] = $row['description'];
                $data['selected']['q_id' . $i] = $row['q_id'];
                $data['selected']['name' . $i] = $name['name'];
            }
            else
            {
                $data['selected']['type' . $i] = $row['creator_type'];
                $data['selected']['description' . $i] = $row['description'];
                $data['selected']['q_id' . $i] = $row['q_id'];
                $data['selected']['name' . $i] = 'admin';
            }
            $i++;
        }
        $data['selected']['count'] = $i;

        $this->load->view('instructor_questions_view', $data);
    }

    /*
     * Name: addQuestion
     * Description: Adds a new question for instructor who is currently logged in
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function add()
    {
        ob_clean();
        $calculated = 0;
        $type = 1; // optional
        $creator_type = $this->input->post('i_id');
        $description = $this->input->post('description');
        $subject = $this->input->post('subject');
        $course_number = $this->input->post('number');

        // TODO: $dupAdminQuestion = $this->questions->checkDupForAdminQuestion($description);
        // TODO: $dupValid = $this->questions->checkDupForSubject($description, $subject, $course_number);

        if ($dupValid && $dupAdminQuestion)
        {
            $qid = $this->questions->add($description, $type, $calculated, $creator_type);
            $this->questions->addCourseSpecific($qid, $subject, $course_number);
            echo $qid;
        }

        else
        {
            $notValid = 0;
            echo $notValid;
        }

    }

    /*
     * Name: modifyQuestion
     * Description: Modifies selected question for instructor who is currently logged in
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function modifyQuestion()
    {
        ob_clean();
        $calculated = 0;
        $type = 1; // optional
        $description = $this->input->post('descriptionMod');
        $qid = $this->input->post('q_id');
        $creator_type = $this->input->post('i_id');
        $subject = $this->input->post('subject');
        $course_number = $this->input->post('number');
        // TODO:
        if ($this->questions->isYourQuestion($qid, $creator_type))
        {
            // TODO: $dupAdminQuestion = $this->questions->checkDupForAdminQuestion($description);
            // TODO: $notDup = $this->questions->checkDupOnModForSubject($qid, $description, $subject, $course_number);

            if ($notDup && $dupAdminQuestion)
            {
                $this->questions->archive($qid);
                $newQid = $this->questions->add($description, $type, $calculated, $creator_type);
                // TODO: $this->questions->isInCourseQuestionTable($qid, $newQid);
                echo $newQid;
            }

            else
            {
                $notValid = 0;
                echo $notValid;
            }
        }

        else
        {
            $not_your_question = -1;
            echo $not_your_question;
        }
    }

    /*
     * Name: removeQuestion
     * Description: Removes selected question for instructor who is currently logged in
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function removeQuestion()
    {
        ob_clean();
        $q_id = $this->input->post('q_id');
        $c_id = $this->input->post('c_id');
        $creator_type = $this->input->post('i_id');

        // TODO:
        if ($this->questions->isYourQuestion($q_id, $creator_type))
        {
            $this->questions->archive($q_id);
            $this->questions->removeFromEvaluation($c_id, $q_id);
            // $this->questions->cleanupCourseSpecific($q_id);
            echo $q_id;
        }

        else
        {
            $not_your_question = 0;
            echo $not_your_question;
        }
    }

    /*
     * Name: addToEvaluation
     * Description: Adds selected questions to appear on evaluation form
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function addToEvaluation()
    {
        $q_id = $this->input->post('q_id');
        $c_id = $this->input->post('c_id');

        $this->questions->addToEvaluation($c_id, $q_id);
    }

    /*
     * Name: removeFromEvaluation
     * Description: Removes selected questions from evaluation form
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function removeFromEvaluation()
    {
        $q_id = $this->input->post('q_id');
        $c_id = $this->input->post('c_id');

        $this->questions->removeFromEvaluation($c_id, $q_id);
    }
}