<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Evaluation_model extends CI_model
{
    public $SectionId = null;

    const SUBMITTED = 1;
    const SAVED = 0;

    public function __construct()
    {
        $this->load->database();
    }

    public function get($section_id)
    {
        $obj = new Evaluation_model;
        $obj->SectionId = $section_id;

        return $obj;
    }

    public function submitted_count()
    {
        return $this->get_count(self::SUBMITTED);
    }

    public function saved_count()
    {
        return $this->get_count(self::SAVED);
    }

    private function get_count($submitted)
    {
        $this->db->select('*');
        $this->db->from('submission');
        $this->db->where('section_id', $this->SectionId);
        $this->db->where('submitted', $submitted);
        $result = $this->db->get();

        return $result->num_rows();
    }

    // Adds completed evaluation to database
    public function add_entry($results, $num_questions, $s_id)
    {
        $isSaved = $this->is_saved($s_id, $this->SectionId);

        // Do we have a submission entry already?
        $sub_id = $this->get_submission_id($s_id);
        $submission_row = array('s_id_hashed'     => $s_id,
                                'section_id'      => $this->SectionId,
                                'submitted'       => $results['submitted'],
                                'general_comment' => $results['comments']);

        // Nope, we don't.
        if ($sub_id == -1)
        {
            $this->db->insert('submission', $submission_row);
            $sub_id = $this->get_submission_id($s_id);
        }
        else
        {
            $this->db->where('sub_id', $sub_id);
            $this->db->update('submission', $submission_row);
        }

        if ($results['submitted'] == self::SUBMITTED)
        {
            // add to the 'already_submitted' table
            $already_submitted_row = array('s_id_hashed' => $s_id,
                                           'section_id'  => $this->SectionId);

            $this->db->insert('already_submitted', $already_submitted_row);

            // wipe user ID from submission itself
            $submission_row['s_id_hashed'] = null;
            $this->db->where('sub_id', $sub_id);
            $this->db->update('submission', $submission_row);
        }

        // Answers!
        for ($i = 1; $i <= $num_questions; $i++)
        {
            if ($results['question' . $i] == null)
            {
                $results['question' . $i] = null;
            }

            $answerComment = $results['comment' . $i];
            $answerComment = strlen($answerComment) > 0 ? $answerComment : null;

            // Prepare the answer
            $answer_table_row = array('sub_id'   => $sub_id,
                                      'q_id'     => $results['q_id' . $i],
                                      'answer'   => $results['question' . $i],
                                      'comments' => $answerComment
            );

            if ($isSaved)
            {
                $this->db->select('*');
                $this->db->from('answer');
                $this->db->where('sub_id', $sub_id);
                $this->db->where('q_id', $results['q_id' . $i]);
                $answerExists = $this->db->get()->num_rows() > 0;

                if ($answerExists)
                {
                    $this->db->where('sub_id', $sub_id);
                    $this->db->where('q_id', $results['q_id' . $i]);
                    $this->db->update('answer', $answer_table_row);
                }
                else
                {
                    // create new answer if it doesn't exist due to some issue
                    $this->db->insert('answer', $answer_table_row);
                }
            }
            else
            {
                $this->db->insert('answer', $answer_table_row);
            }
        }
    }

    // Checks if student already submitted evaluation for a course
    public function is_submitted($s_id)
    {
        $this->db->select('*');
        $this->db->from('already_submitted');
        $this->db->where('s_id_hashed', $s_id);
        $this->db->where('section_id', $this->SectionId);
        $result = $this->db->get();

        return ($result->num_rows() != 0);
    }

    // Checks if student saved evaluation for a course
    public function is_saved($s_id)
    {
        $this->db->select('*');
        $this->db->from('submission');
        $this->db->where('s_id_hashed', $s_id);
        $this->db->where('section_id', $this->SectionId);
        $this->db->where('submitted', self::SAVED);
        $result = $this->db->get();

        return ($result->num_rows() != 0);
    }

    private function get_submission_id($s_id)
    {
        $this->db->select('*');
        $this->db->from('submission');
        $this->db->where('s_id_hashed', $s_id);
        $this->db->where('section_id', $this->SectionId);
        $result = $this->db->get();

        if ($result->num_rows() == 0)
        {
            return -1;
        }
        else
        {
            return $result->first_row()->sub_id;
        }
    }

    public function get_answer($q_id, $s_id)
    {
        $this->db->select('answer, comments');
        $this->db->from('answer');
        $this->db->join('submission', 'answer.sub_id = submission.sub_id');
        $this->db->where('s_id_hashed', $s_id);
        $this->db->where('section_id', $this->SectionId);
        $this->db->where('q_id', $q_id);

        return $this->db->get()->row();
    }

    // Retrieves general comment for a course from database
    public function get_general_comment($s_id)
    {
        $this->db->select('general_comment');
        $this->db->from('submission');
        $this->db->where('s_id_hashed', $s_id);
        $this->db->where('section_id', $this->SectionId);
        $result = $this->db->get()->row();

        return $result->general_comment;
    }
}