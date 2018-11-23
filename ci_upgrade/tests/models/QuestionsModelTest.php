<?php

/**
 * @group Model
 */
class QuestionsModelTest extends CIUnit_TestCase
{
    protected $tables = array(
        'question'        => 'question',
        'answer'          => 'answer',
        'course_question' => 'course_question',
        'submission'      => 'submission'
    );

    private $_pcm;
    private $db;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();

        $this->CI->load->model('Questions_model');
        $this->_pcm = $this->CI->Questions_model;

        $this->CI->load->database();
        $this->db = $this->CI->db;
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    // ------------------------------------------------------------------------

    /**
     * @dataProvider ArchiveProvider
     */
    public function testArchive($qid, $expected_questions, $expected_course_questions)
    {
        $this->_pcm->archive($qid);

        $this->db->where('q_id', $qid);
        $this->db->from('question');
        $this->assertEquals($expected_questions, $this->db->count_all_results());

        $this->db->where('q_id', $qid);
        $this->db->from('course_question');
        $this->assertEquals($expected_course_questions, $this->db->count_all_results());
    }

    public function ArchiveProvider()
    {
        return array(
            array(1, 1, 0),
            array(2, 0, 0),
            array(3, 1, 2)
        );
    }

    // ------------------------------------------------------------------------

}
