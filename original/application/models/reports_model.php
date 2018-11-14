<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Reports_model extends CI_model
{
    const SUBMITTED = 1;

    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Gets an answer histogram for a given section and question.
     * @param $section_id
     * @param $q_id
     * @return mixed
     */
    public function getAllAnswers($section_id, $q_id)
    {
        $this->db->select('answer, COUNT(*) as num_answers');
        $this->db->from('answer');
        $this->db->join('submission', 'answer.sub_id = submission.sub_id');
        $this->db->where('q_id', $q_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('submitted', self::SUBMITTED);
        $this->db->group_by('answer');

        return $this->db->get()->result_array();
    }

    /**
     * Gets a list of all general comments for a course section.
     * @param $section_id
     * @return mixed
     */
    public function getGeneralComments($section_id)
    {
        $this->db->select('general_comment');
        $this->db->from('submission');
        $this->db->where('section_id', $section_id);
        $this->db->where('general_comment is not null');
        $this->db->where('LENGTH(general_comment) !=', 0);
        $this->db->where('submitted', self::SUBMITTED);

        return $this->db->get()->result_array();
    }

    /**
     * Gets a list of question-specific comments for a given section and question.
     * @param $section_id
     * @param $q_id
     * @return mixed
     */
    public function getComments($section_id, $q_id)
    {
        $this->db->select('comments');
        $this->db->from('submission');
        $this->db->join('answer', 'answer.sub_id = submission.sub_id');
        $this->db->where('section_id', $section_id);
        $this->db->where('q_id', $q_id);
        $this->db->where('comments is not null');
        $this->db->where('submitted', self::SUBMITTED);

        return $this->db->get()->result_array();
    }
}