<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Reports extends MY_Controller
{
    const REQUIRED = 0;
    const OTHER = 1;
    const INSTRUCTOR = 2;
    const DEPARTMENTAL = 3;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model');
        $this->load->model('Evaluation_model', 'evaluation');
        $this->load->model('Questions_model', 'questions');
        $this->load->model('Course_model', 'courses');
        $this->load->helper('download');
        $this->load->library('mpdf');
        $this->load->library('zip');
    }

    /**
     * Displays listing of courses to generate reports on.
     */
    public function index()
    {
        $data['courses'] = $this->courses->getAll();
        $data['fluid'] = true;
        $this->courses->sortCourses($data['courses']);

        $this->load->view('admin_reports_view', $data);
    }

    /**
     * Downloads the requested reports.
     * POST: c_ids, file_type
     */
    public function download()
    {
        $c_ids = $this->input->post('c_ids');
        $file_type = $this->input->post('file_type');

        if ($this->hasNull(array($c_ids, $file_type)))
        {
            return;
        }

        $c_id_elements = explode(',', $c_ids);

        if (count($c_id_elements) == 0)
        {
            return;
        }

        if ($file_type == 'count')
        {
            $this->generateCountReport($c_id_elements);
        }
        else
        {
            $singleDownload = count($c_id_elements) == 1;

            foreach ($c_id_elements as $cid)
            {
                $this->generateDeluxeReport($cid, $singleDownload, $file_type);
            }

            if (!$singleDownload)
            {
                date_default_timezone_set('America/Los_Angeles');
                $zip_file = date('m-d-y') . '_Evaluations';
                $this->zip->download($zip_file);
            }
        }
    }

    private function generateCountReport($c_id_elements)
    {
        $keys = array('Section', 'Term', 'Title', 'Instructor', 'Student Count', 'Saved Evaluations', 'Submitted Evaluations', 'Percent Submitted');
        $output = implode(',', $keys);

        foreach ($c_id_elements as $cid)
        {
            $c = $this->courses->getSection($cid);

            $eval = $this->evaluation->get($cid);
            $count_submitted = $eval->submitted_count();

            $data = array(
                'Section'               => $c->tag() . '-' . $c->niceSection(),
                'Term'                  => $c->termName() . ' ' . $c->year(),
                'Title'                 => $c->title,
                'Instructor'            => '"'.$c->instructorName().'"',
                'Student Count'         => $c->student_count,
                'Saved Evaluations'     => $eval->saved_count(),
                'Submitted Evaluations' => $count_submitted,
                'Percent Submitted'     => '0%'
            );

            // prevent division by zero
            if ($c->student_count != 0)
            {
                $data['Percent Submitted'] = sprintf('%.2f', ($count_submitted / (float)$c->student_count) * 100) . '%';
            }

            $output .= "\n" . implode(',', array_values($data));
        }

        $file_name = date('m-d-Y') . '_CountReport.csv';
        force_download($file_name, $output);
    }

    private function generateDeluxeReport($cid, $singleDownload, $fileType = 'pdf')
    {
        ob_clean();
        $allQuestions = $this->pullAllCourseQuestions($cid);
        $course = $this->courses->getSection($cid);

        if ($course == null)
        {
            return;
        }

        // array to hold totals for each type
        $answerTotals = array(
            0         => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
            1         => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
            2         => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
            3         => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
            'overall' => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0)
        );

        // load the questions themselves
        $questions = array();
        foreach ($allQuestions as $q)
        {
            $questions[$q->q_id] = array(
                'description' => $q->description,
                'type'        => $q->q_type,
                'typeName'    => $q->type(),
                'comments'    => array(),
                'answers'     => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
                'average'     => 0
            );
        }

        // pull data for each question
        foreach ($questions as $qid => $q)
        {
            // load comments for question
            $questions[$qid]['comments'] = $this->getQuestionComments($cid, $qid);
            $answers = $this->Reports_model->getAllAnswers($cid, $qid);

            // aggregate numeric answers for question
            foreach ($answers as $a)
            {
                $aVal = $a['answer'] == null ? 0 : $a['answer']; // consider nulls as zeroes
                $aCount = $a['num_answers'];

                // TODO: can we make a helper object instead of answerTotals[]?
                $questions[$qid]['answers'][$aVal] = $aCount; // question-specific count
                $answerTotals[$q['type']][$aVal] += $aCount; // question type count
                $answerTotals['overall'][$aVal] += $aCount; // total count
            }

            // calculate average for question
            $questions[$qid]['average'] = $this->calculateAverage($questions[$qid]['answers']);
        }

        $averages = $this->calculateTotalAverages($answerTotals);
        $viewData['generalComments'] = $this->getCourseComments($cid);

        $viewData['info'] = $course;
        $viewData['totals'] = $answerTotals;
        $viewData['averages'] = $this->calculateTotalAverages($answerTotals);

        $viewData['qRequired'] = $this->filterQuestionsByType($questions, self::REQUIRED);
        $viewData['qInstructor'] = $this->filterQuestionsByType($questions, self::INSTRUCTOR);
        $viewData['qOther'] = $this->filterQuestionsByType($questions, self::OTHER);
        $viewData['qDepartmental'] = $this->filterQuestionsByType($questions, self::DEPARTMENTAL);

        $viewData['evalIncomplete'] = !$course->isFinishedEvaluating();

        if ($fileType == 'pdf')
        {
            $this->createPdf($viewData, $singleDownload, $this->generateFilename($course, 'pdf'));
        }
        elseif ($fileType == 'scores')
        {
            $this->createCsv('scores_report_view', $viewData, $singleDownload, 'SCORES_' . $this->generateFilename($course, 'csv'));
        }
        elseif ($fileType == 'comments')
        {
            $this->createCsv('comments_report_view', $viewData, $singleDownload, 'COMMENTS_' . $this->generateFilename($course, 'csv'));
        }
        else
        {
            die('Invalid report type');
        }
    }

    private function createPdf($viewData, $singleDownload, $fname)
    {
        $viewString = $this->load->view('main_report_view', $viewData, true);

        $this->mpdf = new mPDF();
        $this->mpdf->WriteHTML(utf8_decode($viewString));

        if ($singleDownload)
        {
            $this->mpdf->Output($fname, 'I');
        }
        else
        {
            $this->zip->add_data($fname, $this->mpdf->Output('', 'S'));
        }

        $this->mpdf = null;
    }

    private function createCsv($viewName, $viewData, $singleDownload, $fname)
    {
        $viewString = $this->load->view($viewName, $viewData, true);

        if ($singleDownload)
        {
            header('Content-Disposition: attachment; filename=' . $fname);
            header('Content-Type: text/csv');
            header('Content-Length: ' . strlen($viewString));
            header('Pragma: no-cache');
            header('Expires: 0');
            echo $viewString;
        }
        else
        {
            $this->zip->add_data($fname, $viewString);
        }
    }

    private function generateFilename($c, $extension = 'pdf')
    {
        return $c->termName() . $c->year() . '_' . $c->first_name . '_' . $c->last_name . "_" . $c->course_subject . $c->course_num . '-' . $c->course_section . '_EVAL.' . $extension;
    }

    private function calculateTotalAverages($answerTotals)
    {
        $averages = array();

        foreach ($answerTotals as $key => $total)
        {
            $averages[$key] = $this->calculateAverage($answerTotals[$key]);
        }

        return $averages;
    }

    private function calculateAverage($answers)
    {
        $count = 0;
        $sum = 0;

        // we start at 1 to discount all N/A (0) answers
        for ($i = 1; $i <= 5; $i++)
        {
            $count += $answers[$i];
            $sum += $answers[$i] * $i;
        }

        return ($count == 0) ? 0 : $sum * 1.0 / $count;
    }

    private function pullAllCourseQuestions($section_id)
    {
        $reqQuestions = $this->questions->getRequiredLossless($section_id);
        $optQuestions = $this->questions->forSection($section_id, true);
        $allQuestions = array_merge($reqQuestions, $optQuestions);

        return $allQuestions;
    }

    private function filterQuestionsByType($questions, $type)
    {
        $filter = function ($val) use ($type)
        {
            return $val['type'] == $type;
        };

        return array_filter($questions, $filter);
    }

    private function getQuestionComments($cid, $qid)
    {
        $map = function ($val)
        {
            return $val['comments'];
        };

        $commentResults = $this->Reports_model->getComments($cid, $qid);

        return array_map($map, $commentResults);
    }

    private function getCourseComments($cid)
    {
        $map = function ($val)
        {
            return $val['general_comment'];
        };

        $commentResults = $this->Reports_model->getGeneralComments($cid);

        return array_map($map, $commentResults);
    }
}