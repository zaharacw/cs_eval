<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
require_once dirname(__FILE__) . '/../libraries/odataphp/framework/CourseEnrollmentEntities.php';

class Info_upload_model extends CI_model
{
    const COURSES = 'courses';
    const SERVICE_URL = 'https://webapps.eastern.ewu.edu/datainterfaces/CourseEnrollment.svc';

    private static $memberFields = array('FIRST_NAME' => 'first_name',
                                         'LAST_NAME'  => 'last_name',
                                         'ID'         => 'id');

    private static $courseFields = array('TERM'       => 'term',
                                         'SUBJ'       => 'subject',
                                         'TITLE'      => 'title',
                                         'CRSENUMB'   => 'number',
                                         'SECTION'    => 'section',
                                         'START_DATE' => 'start_date',
                                         'END_DATE'   => 'end_date',
                                         'CRN'        => 'crn');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Course_model', 'courses');
        $this->load->model('User_model');
    }

    /**
     * Loads course data into the session.
     * @return bool
     */
    public function LoadCourseData()
    {
        $entities = $this->RetrieveEntities();

        if (!isset($entities) || count($entities) <= 0)
        {
            return false;
        }

        $courses = $this->ParseEntities($entities);
        $this->courses->sortCourses($courses);
        $this->session->set_userdata(self::COURSES, $courses);

        return true;
    }

    /**
     * Removes course data from the session.
     */
    public function UnloadCourseData()
    {
        $this->session->unset_userdata(self::COURSES);
    }

    /**
     * Uploads the courses corresponding to the given indices.
     * @param $course_indices
     */
    public function UploadCourses($course_indices)
    {
        $allCourses = $this->session->userdata(self::COURSES);
        $instructors = array();
        $courses = array();

        foreach ($course_indices as $index)
        {
            $courses[] = $allCourses[$index];
        }

        unset($allCourses);

        if (count($course_indices) < 6)
        {
            foreach ($courses as $course)
            {
                $course->student_count = $this->GetStudentCount($course->crn);
                if (!array_key_exists($course->instructor, $instructors))
                {
                    $instructors[$course->instructor] = array(
                        'fname' => $course->first_name,
                        'lname' => $course->last_name
                    );
                }
            }
        }
        else
        {
            $compareArray = $this->LoadAllStudents();

            foreach ($courses as $course)
            {
                $count = 0;
                for ($i = 0; $i < count($compareArray); $i++)
                {
                    if ($compareArray[$i] == $course->crn)
                    {
                        $count++;
                    }
                }

                $course->student_count = $count;
                if (!array_key_exists($course->instructor, $instructors))
                {
                    $instructors[$course->instructor] = array(
                        'fname' => $course->first_name,
                        'lname' => $course->last_name
                    );
                }
            }

            unset($compareArray);
        }

        // add any missing instructors
        foreach ($instructors as $key => $info)
        {
            $user = $this->User_model->getInstructor($key);

            if (!$user['valid'])
            {
                $this->User_model->addInstructor($info['fname'], $info['lname'], $key, '');
            }
        }

        $this->courses->addCourses($courses);
    }

    private function RetrieveEntities()
    {
        $entities = array();

        try
        {
            $proxy = null;
            $entityResponse = null;
            $nextEntityToken = null;

            $proxy = new CourseEnrollmentEntities(self::SERVICE_URL);
            $query = $proxy->MEMBERS()->Expand('COURSES')->AddQueryOption('$filter', "ROLE eq 'faculty'")->IncludeTotalCount();
            $entityResponse = $query->Execute();

            $coursesQuery = $proxy->COURSES();
            $coursesResponse = $coursesQuery->Execute();
            $visitedCourses = array();

            do
            {
                $attributes = array();

                if ($nextEntityToken != null)
                {
                    $entityResponse = $proxy->Execute($nextEntityToken);
                }

                // loop through entities
                foreach ($entityResponse->Result as $entityObject)
                {
                    $course = $entityObject->COURSES[0];
                    $visitedCourses[self::entityKey($course)] = 1;

                    $attributes['members'] = array();
                    $attributes['members'][0] = array();

                    // go through course fields
                    foreach (self::$courseFields as $field => $attr)
                    {
                        $attributes[$attr] = $course->$field;
                    }

                    // go through member fields
                    foreach (self::$memberFields as $field => $attr)
                    {
                        $attributes['members'][0][$attr] = $entityObject->$field;
                    }

                    $entities[] = $attributes;
                }
            }
            while (($nextEntityToken = $entityResponse->GetContinuation()) != null);

            $nextEntityToken = null;

            // we check for unprocessed TBA-instructor courses
            do
            {
                $attributes = array();

                if ($nextEntityToken != null)
                {
                    $coursesResponse = $proxy->Execute($nextEntityToken);
                }

                // loop through entities
                foreach ($coursesResponse->Result as $course)
                {
                    if ($visitedCourses[self::entityKey($course)] != 1)
                    {
                        foreach (self::$courseFields as $field => $attr)
                        {
                            $attributes[$attr] = $course->$field;
                        }

                        $attributes['members'] = array(array('first_name' => 'TBA', 'last_name' => 'TBA', 'id' => '00000000'));
                        $entities[] = $attributes;
                    }
                }
            }
            while (($nextEntityToken = $coursesResponse->GetContinuation()) != null);

        }
        catch (DataServiceRequestException $ex)
        {
            // ignore? :S
        }
        catch (ODataServiceException $e)
        {
            echo 'Error:' . $e->getError() . '<br>' . 'Detailed Error:' . $e->getDetailedError();
        }

        return $entities;
    }

    private function ParseEntities($entities)
    {
        $courses = array();

        foreach ($entities as $attributes)
        {
            $_course = $this->courses->create();
            $_course->title = $attributes['title'];
            $_course->course_subject = $attributes['subject'];
            $_course->course_num = $attributes['number'];
            $_course->course_section = $attributes['section'];
            $_course->crn = $attributes['crn'];

            $member = $attributes['members'][0];
            $_course->first_name = $member['first_name'];
            $_course->last_name = $member['last_name'];
            $_course->instructor = do_hash($member['id'], 'md5');

            // get term
            $pattern = '/(?P<year>[[:digit:]]{4})(?P<quarter>[[:digit:]]{2})/';
            if (preg_match($pattern, $attributes['term'], $matches))
            {
                $_course->term = $matches['quarter'];
            }

            // dates
            $pattern = '/([[:digit:]]{4}-[[:digit:]]{2}-[[:digit:]]{2})/';
            if (preg_match($pattern, $attributes['start_date'], $matches))
            {
                $_course->start_date = $matches[1];
            }

            if (preg_match($pattern, $attributes['end_date'], $matches))
            {
                $_course->end_date = $matches[1];
            }

            $this->calculateEvalDates($_course);
            $courses[] = $_course;
        }

        return $courses;
    }

    private static function entityKey($course)
    {
        return $course->TERM . $course->SUBJ . $course->CRSENUMB . $course->SECTION;
    }

    private function GetStudentCount($crn)
    {
        try
        {
            $proxy = new CourseEnrollmentEntities(self::SERVICE_URL);

            return $proxy->MEMBERS()->AddQueryOption('$filter', "CRN eq '$crn' and ROLE ne 'faculty'")->Count();
        }
        catch (ODataServiceException $e)
        {
            echo 'Error:' . $e->getError() . '<br>' . 'Detailed Error:' . $e->getDetailedError();
        }
    }

    private function LoadAllStudents()
    {
        try
        {
            $result = array();
            $proxy = new CourseEnrollmentEntities(self::SERVICE_URL);
            $res = $proxy->MEMBERS()
                ->AddQueryOption('$select', 'CRN')
                ->Filter("ROLE ne 'faculty'")->Execute();

            foreach ($res->Result as $entityObject)
            {
                $result[] = $entityObject->CRN;
            }

            return $result;
        }
        catch (ODataServiceException $e)
        {
            echo 'Error:' . $e->getError() . '<br>' . 'Detailed Error:' . $e->getDetailedError();
        }
    }

    /**
     * Sets the eval start date to the course's 75% mark.
     * @param $course
     */
    private function calculateEvalDates($course)
    {
        $start = new DateTime($course->start_date);
        $end = new DateTime($course->end_date);

        $numberOfDays = $start->diff($end)->format('%a');
        $daysFromStart = (int)($numberOfDays / 100 * 75);

        $start->add(DateInterval::createFromDateString($daysFromStart . ' days'));
        $evalDays = $start->diff($end)->format('%a');

        if ($evalDays < 7)
        {
            $diff = 7 - $evalDays;
            $start->sub(DateInterval::createFromDateString($diff . ' days'));
        }

        $course->eval_start = $start->format('Y-m-d');
        $course->eval_end = $end->format('Y-m-d');
    }
}