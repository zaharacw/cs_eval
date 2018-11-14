<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Required_questions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Questions_model', 'questions');
    }

    /**
     * Display list of required questions
     */
    public function index()
    {
        $data['required'] = true;
        $data['results'] = $this->questions->getRequired();
        $this->load->view('admin_required_questions_view', $data);
    }

    /**
     * POST: description
     */
    public function add()
    {
        ob_clean();
        $description = $this->input->post('description');

        if ($description == null)
        {
            return;
        }

        if (!$this->questions->duplicateExists($description))
        {
            $qid = $this->questions->add($description, 0, 'admin');
            echo $qid;
        }
        else
        {
            $question = $this->questions->getSingle($description);
            if ($question->isRequired())
            {
                echo -1;
            }
            else
            {
                $notValid = 0;
                echo $notValid;
            }
        }
    }

    /**
     * POST: description, qid
     */
    public function modify()
    {
        ob_clean();
        $description = $this->input->post('description');
        $qid = $this->input->post('qid');

        if ($this->hasNull(array($description, $qid)))
        {
            return;
        }

        if (!$this->questions->duplicateExists($description, $qid))
        {
            $this->questions->archive($qid);
            $newQid = $this->questions->add($description, 0, 'admin');
            echo $newQid;
        }
        else
        {
            $question = $this->questions->getSingle($description);
            if ($question->isRequired())
            {
                echo -1;
            }
            else
            {
                $notValid = 0;
                echo $notValid;
            }
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