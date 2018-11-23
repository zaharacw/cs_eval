<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Questions_model extends CI_model
{
    public $q_id = null;
    public $archived = null;
    public $q_type = null;
    public $creator_type = null;
    public $description = null;
    public $instructor = null;

    private static $typeInfo = array(
        0 => 'required',
        1 => 'other',
        2 => 'instructor',
        3 => 'departmental'
    );

    const REQUIRED = 0;
    const AVAILABLE = 0;
    const ARCHIVED = 1;

    public function __construct()
    {
        $this->load->database();
        $this->load->model('Info_upload_model');
        $this->load->model('Evaluation_model', 'evaluations');
    }

    /**
     * English name of the question type
     * @return string
     */
    public function type()
    {
        $type = intval($this->q_type);

        return array_key_exists($type, self::$typeInfo) ? self::$typeInfo[$type] : 'BAD_TYPE';
    }

    public function isRequired()
    {
        return $this->q_type == self::REQUIRED;
    }

    /**
     * Gets all currently-active required questions.
     * @return mixed
     */
    public function getRequired()
    {
        return $this->getMultiple(true);
    }

    /**
     * Gets all required questions that have been answered for a section regardless of archival status.
     * @param $section_id
     * @return mixed
     */
    public function getRequiredLossless($section_id)
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where('q_type', self::REQUIRED);
        $this->db->where('q_id in (SELECT q_id from answer JOIN submission on answer.sub_id = submission.sub_id WHERE section_id = ' . $this->db->escape($section_id) . ')', null, false);

        return $this->db->get()->result('Questions_model');
    }

    /**
     * Gets a single question by description.
     * @param $description
     * @return mixed
     */
    public function getSingle($description)
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where('description', $description);

        return $this->db->get()->row(0, 'Questions_model');
    }

    /**
     * Gets all active course-specific questions.
     * @param string $creator_type
     * @return mixed
     */
    public function getCourseSpecific($creator_type = 'admin')
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->join('course_question', 'question.q_id = course_question.q_id');
        $this->db->join('section', 'course_question.section_id = section.section_id');
        $this->db->where('creator_type', $creator_type);
        $this->db->where('archived', self::AVAILABLE);
        $this->db->order_by('course_question.section_id', 'desc');

        return $this->db->get()->result('Questions_model');
    }

    private function getMultiple($required = true)
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where('archived', self::AVAILABLE);

        if ($required)
        {
            $this->db->where('q_type', self::REQUIRED);
        }
        else
        {
            $this->db->where('q_type <>', self::REQUIRED);
        }

        return $this->db->get()->result('Questions_model');
    }

    /**
     * Retrieves all course-specific questions for a specific course.
     * @param $section_id
     * @return mixed
     */
    public function forSection($section_id, $includeArchived = false)
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->join('course_question', 'question.q_id = course_question.q_id');
        $this->db->where('section_id', $section_id);

        if (!$includeArchived)
        {
            $this->db->where('archived', self::AVAILABLE);
        }

        return $this->db->get()->result('Questions_model');
    }

    /**
     * Archives a question in the database by ID.
     * @param $q_id
     */
    public function archive($q_id)
    {
        $this->cleanupCourseSpecific($q_id);

        // archive
        $this->db->update('question', array('archived' => self::ARCHIVED), "q_id = $q_id");

        // cleanup
        $sql = 'DELETE FROM question WHERE q_id = ? AND NOT EXISTS (SELECT sub_id FROM answer WHERE q_id = ?) AND q_id NOT IN (SELECT q_id FROM course_question)';
        $this->db->query($sql, array($q_id, $q_id));
    }

    private function cleanupCourseSpecific($q_id)
    {
        $sql = 'DELETE FROM course_question WHERE q_id = ? AND section_id NOT IN (SELECT section_id FROM answer JOIN submission ON answer.sub_id = submission.sub_id WHERE q_id = ?) AND section_id NOT IN (SELECT section_id FROM section WHERE CURDATE() between eval_start AND eval_end)';
        $this->db->query($sql, array($q_id, $q_id));
    }

    /**
     * Adds a new question to the database.
     * @param $description
     * @param $type
     * @param $creator_type
     * @return mixed
     */
    public function add($description, $type, $creator_type)
    {
        $row = array(
            'description'  => $description,
            'q_type'       => $type,
            'archived'     => self::AVAILABLE,
            'creator_type' => $creator_type);

        $this->db->insert('question', $row);

        return $this->db->insert_id();
    }

    /**
     * Checks if an active question with the same description exists. Can optionally provide
     * a question ID to exclude from the check.
     * @param      $description
     * @param null $prior_question_id
     * @return bool
     */
    public function duplicateExists($description, $prior_question_id = null)
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where('description', $description);
        $this->db->where('archived', self::AVAILABLE);

        if ($prior_question_id != null)
        {
            $this->db->where('q_id <>', $prior_question_id);
        }

        $result = $this->db->get();

        return ($result->num_rows() !== 0);
    }

    /**
     * Adds a new question to a specific course.
     * @param $q_id
     * @param $section_id
     */
    public function addCourseSpecific($q_id, $section_id)
    {
        $row = array('q_id' => $q_id, 'section_id' => $section_id);
        $this->db->insert('course_question', $row);
    }
}