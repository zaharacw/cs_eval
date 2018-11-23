<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Evaluation_period extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['fluid'] = true;
        $data['courses'] = $this->Course_model->getEvalEditable();
        $this->Course_model->sortCourses($data['courses']);

        $this->load->view('evaluation_period_view', $data);
    }

    public function set_eval_period()
    {
        ob_clean();
        $start = $this->input->post('startEval');
        $end = $this->input->post('endEval');
        $cid = $this->input->post('cid');

        $startDate = new DateTime($start);
        $endDate = new DateTime($end);

        if ($this->hasNull(array($start, $end, $cid)) ||
            !$this->isValidDate($start) || !$this->isValidDate($end) ||
            $startDate >= $endDate)
        {
            echo 0;
            return;
        }

        $course = $this->courses->getSection($cid);
        $prevStartDate = $course->start_date;
        $prevEndDate = $course->end_date;
        if($prevStartDate != $start || $prevEndDate != $end){
                $course->modifiedDate();
        }
        $course->updateEvalPeriod($start, $end);

        echo 1;
    }

    private function isValidDate($dateString)
    {
        // First check for the pattern
        $datePattern = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
        if (preg_match($datePattern, $dateString) == 0)
        {
            return false;
        }

        // Parse the date parts to integers
        $parts = explode("-", $dateString);
        $day = intval($parts[2]);
        $month = intval($parts[1]);
        $year = intval($parts[0]);

        // Check the ranges of month and year
        if ($month == 0 || $month > 12)
        {
            return false;
        }

        $monthLength = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        // Adjust for leap years
        if ($year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0))
        {
            $monthLength[1] = 29;
        }

        // Check the range of the day
        return $day > 0 && $day <= $monthLength[$month - 1];
    }
}// end class