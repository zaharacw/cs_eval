<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/**
 * @deprecated
 */
class Admin_instructor_questions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Questions_model', 'questions');
        $this->load->model('Course_model');
        $this->load->model('User_model');
    }

    /*
     * Name: index
     * Description: uses getInstructorQuestions and puts them into an array.
     * Puts that array to the view.
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function index()
    {
        // show 'not implemented message'
        $data['message'] = 'Instructor functionality is not yet fully implemented.';
        $this->load->view('error_view', $data);

        return;


        $courseNums = '';
        // TODO
        // $temp = $this->questions->getInstructorQuestions();
        $i = 0;
        foreach ($temp as $row)
        {
            $data['question']['description' . $i] = $row['description'];
            $data['question']['q_id' . $i] = $row['q_id'];

            $name = $this->User_model->getUser($row['creator_type']);
            // TODO:
            // $cNums = $this->Course_model->getCourseNumber($row['q_id']);
            foreach ($cNums as $c)
            {
                $courseNums = $courseNums . $c['subject'] . $c['course_number'];
            }
            $data['question']['course_number' . $i] = $courseNums;
            $data['question']['instructorName' . $i] = $name['name'];
            $i++;
            $courseNums = '';
        }

        $data['question']['numQuestions'] = $i;
        // TODO:
        // $data['results2'] = $this->Course_model->getSubjectAndNumber();
        $this->load->view('admin_instructor_questions_view', $data);
    }

    /*
     * Name: addQuestion
     * Description: allows the admin to add a question.
     * Uses the Javascript to do this.
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function add()
    {
        ob_clean();
        $desc = $this->input->post('description');
        $name = $this->input->post('instructor');
        $instructor = $this->User_model->getInstructorID($name);
        $course = $this->input->post('courses');

        for ($i = 0; $i < count($course); $i++) // For each selected course...add the question
        {
            $subject = substr($course[$i], 0, 4);
            $number = substr($course[$i], 4);

            // TODO: $dupAdminQuestion = $this->questions->checkDupForAdminQuestion($desc);
            // TODO: $dupValid = $this->questions->checkDupForSubject($desc, $subject, $number);

            if ($dupValid && $dupAdminQuestion)
            {
                $qid = $this->questions->add($desc, 1, 0, $instructor[0]['username']);
                $this->questions->addCourseSpecific($qid, $subject, $number);
                $this->addQuestionToEvaluations($qid, $name, $subject, $number);
                echo $qid;
            }

            else
            {
                $notValid = 0;
                echo $notValid;
            }
        }
    }

    /*
     * Name: addQuestionToEvaluations
     * Description: Function to add the questions to the evaluations
     * Parameters: $qid quarter id, $instructor name, $subject subject name, $number course number
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function addQuestionToEvaluations($qid, $instructor, $subject, $number)
    {
        $term = $this->Course_model->getCurrentTerm();
        $year = date('Y');
        // TODO:
        // $course_ids = $this->Course_model->getCourseIDs($subject, $number, $year, $term, $instructor);
        for ($i = 0; $i < count($course_ids); $i++)
        {
            $this->questions->addCourseSpecific($qid, $course_ids[$i]['c_id']);
        }

    }

    /*
     * Name: ModifyQuestion
     * Description: Function that uses javascript to allow you to modify the questions
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function ModifyQuestion()
    {
        ob_clean();

        $description = $this->input->post('descriptionMod');
        $q_id = $this->input->post('q_id');
        // TODO: $temp = $this->questions->getSubjectAndCourse($q_id);
        $notValid = 0;

        // TODO: $notDupAdminQuestion = $this->questions->checkDupForAdminQuestion($description);

        foreach ($temp as $c)
        {
            // TODO: $notDup = $this->questions->checkDupForSubject($description, $c['subject'], $c['course_number']);
            if (!$notDup)
            {
                break;
            }
        }

        if ($notDupAdminQuestion == false)
        {
            $notValid = -1;
            echo $notValid;
        }

        else
        {
            if ($notDup == false)
            {
                $notValid = 0;
                echo $notValid;
            }

            else
            {
                if ($notDup && $notDupAdminQuestion)
                {
                    $this->questions->archive($q_id);
                    // TODO: $creator_type = $this->questions->getCreator($q_id);

                    $newQid = $this->questions->add($description, 1, 0, $creator_type);

                    // TODO: $this->questions->isInCourseQuestionTable($q_id, $newQid);
                    // TODO: $this->questions->isInCourseQuestionTable($q_id, $newQid);

                    echo $newQid;
                }

                else
                {
                    echo $notValid;
                }
            }
        }
    }

    /*
     * Name: RemoveQuestion
     * Description: Function that uses javascript to allow you to remove the questions
     * Parameters: None
     * Return: None
     * Documentation Modified: 4/5/14 (MW)
     */
    public function RemoveQuestion()
    {
        ob_clean();
        $q_id = $this->input->post('q_id');

        $this->questions->archive($q_id);
        // TODO: $this->questions->removeFromEvaluationByAdmin($q_id);
        // $this->questions->cleanupCourseSpecific($q_id);
        echo $q_id;

    }
}