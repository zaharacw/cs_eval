<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Course_model extends CI_model
{
    public $section_id = null;
    public $course_subject = null;
    public $course_num = null;
    public $course_section = null;
    public $term = null;
    public $title = null;
    public $student_count = null;
    public $crn = null;

    public $start_date = null;
    public $end_date = null;
    public $eval_start = null;
    public $eval_end = null;

    public $instructor = null;
    public $last_name = null;
    public $first_name = null;
    public $email = null;

    public $modified = null;
    public $modified_date = null;

    private static $termInfo = array(
        10 => 'Winter',
        15 => 'Fall Semester',
        20 => 'Spring',
        25 => 'Spring Semester',
        30 => 'Summer',
        35 => 'Summer Semester',
        40 => 'Fall'
    );

    public function __construct()
    {
        $this->load->database();
        $this->load->model('User_model');
        $this->load->model('Evaluation_model', 'evaluation');
        $this->load->model('Settings_model', 'settings');
    }

    /**
     * English name of the term.
     * @return string
     */
    public function termName()
    {
        return $this->termNumToName($this->term);
    }

    /**
     * Full name of the instructor.
     * @return string
     */
    public function instructorName()
    {
        return $this->last_name . ', ' . $this->first_name;
    }

    /**
     * Zero-padded section number.
     * @return string
     */
    public function niceSection()
    {
        return sprintf('%02d', $this->course_section);
    }

    /**
     * Composite tag of the course. For example, "CSCD 495". Can optionally include the section number.
     * @param bool $showSection
     * @return string
     */
    public function tag($showSection = false)
    {
        if ($showSection)
        {
            return $this->course_subject . ' ' . $this->course_num . '&ndash;' . $this->niceSection();
        }

        return $this->course_subject . ' ' . $this->course_num;
    }

    /**
     * Year of the course.
     * @return string
     */
    public function year()
    {
        return DateTime::createFromFormat('Y-m-d', $this->start_date)->format('Y');
    }

    /**
     * The evaluation status for a given student.
     * @param $student_id
     * @return string
     */
    public function status($student_id)
    {
        $eval = $this->evaluation->get($this->section_id);

        if (!$this->isAvailableForEvaluation(false))
        {
            return 'Unavailable';
        }
        elseif ($eval->is_submitted($student_id))
        {
            return 'Submitted';
        }
        elseif ($eval->is_saved($student_id))
        {
            return 'Saved';
        }

        return 'Available';
    }

    /**
     * Gets the string value for a term.
     * @param $term
     * @return string
     */
    public function termNumToName($term)
    {
        $term = intval($term);

        return array_key_exists($term, self::$termInfo) ? self::$termInfo[$term] : 'BAD_TERM';
    }

    /**
     * Gets the numeric value for a term.
     * @param $term
     * @return int
     */
    public function termNameToNum($term)
    {
        $termInfoReversed = array_flip(self::$termInfo);

        return array_key_exists($term, $termInfoReversed) ? $termInfoReversed[$term] : -1;
    }

    public function getEvaluation()
    {
        return $this->evaluation->get($this->section_id);
    }

    /**
     * Determines whether or not a course is currently available to be evaluated by students.
     * @return bool
     */
    public function isAvailableForEvaluation($asAdmin = true)
    {
        // allow validation 
        if ($asAdmin && $this->settings->isDeveloperMode())
        {
            return true;
        }

        $this->db->select('section_id');
        $this->db->from('section');
        $this->db->where('section_id', $this->section_id);
        $this->db->where('CURDATE() between eval_start and eval_end');
        $result = $this->db->get();

        return ($result->num_rows() > 0);
    }

    /**
     * Determines whether or not a course is past the evaluation period.
     * @return bool
     */
    public function isFinishedEvaluating()
    {
        $this->db->select('section_id');
        $this->db->from('section');
        $this->db->where('section_id', $this->section_id);
        $this->db->where('CURDATE() > eval_end'); // TODO: > or >= (?)
        $result = $this->db->get();

        return ($result->num_rows() > 0);
    }

    /**
     * Gets all sections matching an optional data array.
     * @param null $data
     * @return mixed
     */
    public function getAll($data = null)
    {
        $this->prepareGet($data);

        return $this->db->get()->result('Course_model');
    }

    /**
     * Gets currently-available sections that match an optional data array.
     * @param null $data
     * @return mixed
     */
    public function getCurrent($data = null)
    {
        $data['start_date <='] = $this->curdate();
        $data['eval_end >='] = $this->curdate();

        return $this->getAll($data);
    }

    /**
     * Gets future sections that match an optional data array.
     * @param null $data
     * @return mixed
     */
    public function getEditable($data = null)
    {
        if (!$this->settings->isDeveloperMode())
        {
            $data['eval_start >'] = $this->curdate();
        }       

        return $this->getAll($data);
    }

    /**
     * Gets current sections and sections 
     * that have ended within the past 30 days.
     * If converting the DateTime object to a string
     * fails, the current date is used instead.
     * @param null $data
     * @return mixed
     */
    public function getEvalEditable($data = null)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P30D')); 
        $result = $date->format('Y-m-d');

        if (!$this->settings->isDeveloperMode())
        {
            if($result)
            {
               $data['eval_end >'] = $result; 
            }
            else
            {
                $data['eval_end >'] = $this->curdate();
            }
        }

        return $this->getAll($data);
    }

    /**
     * Gets the first section matching an optional data array.
     * @param null $data
     * @return mixed
     */
    public function getSingle($data = null)
    {
        $this->prepareGet($data);

        return $this->db->get()->row(0, 'Course_model');
    }

    /**
     * Gets a single section by ID.
     * @param $section_id
     * @return mixed
     */
    public function getSection($section_id)
    {
        return $this->getSingle(array('section_id' => $section_id));
    }

    /**
     * Removes a single section by ID.
     * @param $section_id
     */
    public function removeSection($section_id)
    {
        $this->db->where('section_id', $section_id);
        $this->db->delete('section');
    }

    private function prepareGet($data)
    {
        $this->db->select('*');
        $this->db->from('section');
        $this->db->join('instructor', 'section.instructor = instructor.inst_id_hashed');

        if ($data != null)
        {
            $this->db->where($data);
        }

        $this->db->order_by('YEAR(start_date) DESC, term DESC, course_subject, course_num');
    }

    /**
     * Gets distinct tuples containing the given columns. Can optionally be filtered by future courses.
     * @param      $columns
     * @param bool $editableOnly
     * @return mixed
     */
    public function getDistinct($columns, $editableOnly = false)
    {
        $this->db->distinct();
        $this->db->select(implode(',', $columns));
        $this->db->from('section');
        $this->db->join('instructor', 'section.instructor = instructor.inst_id_hashed');

        if ($editableOnly && !$this->settings->isDeveloperMode())
        {
            $this->db->where('eval_start > CURDATE()');
        }

        foreach ($columns as $col)
        {
            $this->db->order_by($col, 'asc');
        }

        return $this->db->get()->result('Course_model');
    }

    /**
     * Add a "LIKE" constraint to the next query. Useful for string comparisons.
     * @param $column
     * @param $value
     */
    public function addLikeConstraint($column, $value)
    {
        $this->db->like($column, $value);
    }

    /**
     * Retrieves full names of all instructors in the system regardless of their teaching status.
     * @return mixed
     */
    public function getAllInstructors()
    {
        $this->db->distinct();
        $this->db->select('CONCAT(last_name, ", ", first_name) as name, inst_id_hashed as id', false);
        $this->db->from('instructor');
        $this->db->order_by('last_name, first_name');

        return $this->db->get()->result();
    }

    /**
     * Gets a list of all distinct years that courses were offered.
     * @param $editableOnly
     * @return mixed
     */
    public function getDistinctYears($editableOnly)
    {
        $this->db->distinct();
        $this->db->select('YEAR(start_date) as year');

        if ($editableOnly)
        {
            $this->db->where('eval_start > CURDATE()');
        }

        return $this->db->get('section')->result();
    }

    /**
     * Adds one or more courses to the database.
     * @param $courses
     */
    public function addCourses($courses)
    {
        foreach ($courses as $course)
        {
            $course->add();
        }
    }

    private function add()
    {
        $origSection = $this->getOriginal();

        // remove unmappable data
        unset($this->first_name);
        unset($this->last_name);
        unset($this->email);
        unset($this->crn);
        unset($this->section_id);

        if ($origSection == null)
        {
            $this->db->insert('section', $this);
        }
        else
        {
            $this->db->where('section_id', $origSection->section_id);
            $this->db->update('section', $this);
        }
    }

    public function forceAdd()
    {
        // remove unmappable data
        unset($this->first_name);
        unset($this->last_name);
        unset($this->email);
        unset($this->crn);
        unset($this->section_id);
        unset($this->inst_id_hashed);
        if ($this->db->select('*')->from('section')->where((array)$this)->get()->num_rows() != 0)
        {
            return false;
        }

        $this->db->insert('section', $this);

        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /**
     * Retrieves any course in the database that matches the current course.
     * @return mixed
     */
    public function getOriginal()
    {
        $checkData = array(
            'course_subject'   => $this->course_subject,
            'course_num'       => $this->course_num,
            'course_section'   => $this->course_section,
            'term'             => $this->term,
            'YEAR(start_date)' => $this->year()
        );

        return $this->getSingle($checkData);
    }

    /**
     * Updates the instructor.
     * @param $inst
     */
    public function updateInstructor($inst)
    {
        $this->instructor = $inst;
        $this->db->where('section_id', $this->section_id);
        $this->db->update('section', array('instructor' => $inst));
    }

       /** marks a modified row for courses  */
    public function modified(){
        $this->db->where('section_id', $this->section_id);
        $this->db->update('section', array('modified' => 1));
    }

        public function modifiedDate(){
        $this->db->where('section_id', $this->section_id);
        $this->db->update('section', array('modified_date' => 1));
    }

    public function updateEvalPeriod($start, $end)
    {
        $this->eval_start = $start;
        $this->eval_end = $end;
        
        $this->db->where('section_id', $this->section_id);
        $this->db->update('section', array('eval_start' => $start, 
                                            'eval_end' => $end));
    }

    /**
     * Pulls the current term based on the latest start data.
     * @return mixed
     * @todo: make less wonky since any courses added that start at a later date will break this
     * @todo: base on this and curdate might be at least somewhat a better solution
     */
    public function getCurrentTerm()
    {
        $this->db->limit(1); // pull the top data
        $this->db->select('term');
        $this->db->from('section');
        $this->db->order_by('start_date', 'desc');
        $result = $this->db->get()->result_array();

        return $result[0]['term'];
    }

    /**
     * Gets the name of the current term.
     * @return string
     */
    public function getCurrentTermName()
    {
        return $this->termNumToName($this->getCurrentTerm());
    }

    /**
     * Gets a list of accepted subjects.
     * @return array
     */
    public function getCurrentSubjects()
    {
        $this->db->distinct();
        $this->db->select('course_subject');
        $this->db->from('section');
        $result = $this->db->get();
        $subjects = array();

        foreach ($result->result_array() as $row)
        {
            $subjects[] = $row['course_subject'];
        }

        return $subjects;
    }

    public function create()
    {
        return new Course_model();
    }

    public function sortCourses(&$courses)
    {
        $comparator = function ($a, $b)
        {
            $criteria = array('course_subject', 'course_num', 'course_section');
            $diff = 0;

            foreach ($criteria as $prop)
            {
                if ($a->$prop < $b->$prop)
                {
                    $diff = -1;
                    break;
                }
                else
                {
                    if ($a->$prop > $b->$prop)
                    {
                        $diff = 1;
                        break;
                    }
                }
            }

            return $diff;
        };

        usort($courses, $comparator);
    }

    private function curdate()
    {
        return date('Y-m-d');
    }
}