<?php

/**
 * @group Model
 */
class EvaluationModelTest extends CIUnit_TestCase
{
    protected $tables = array(
        // 'answer' => 'answer',
        'submission' => 'submission'
    );

    private $_pcm;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();

        /*
        * this is an example of how you would load a product model,
        * load fixture data into the test database (assuming you have the fixture yaml files filled with data for your tables),
        * and use the fixture instance variable

        $this->CI->load->model('Product_model', 'pm');
        $this->pm=$this->CI->pm;
        $this->dbfixt('users', 'products');

        the fixtures are now available in the database and so:
        $this->users_fixt;
        $this->products_fixt;

        */

        $this->CI->load->model('Evaluation_model');
        $this->_pcm = $this->CI->Evaluation_model;
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    // ------------------------------------------------------------------------

    /**
     * @dataProvider GetCountProvider
     */
    public function testGetCount($section_id, $expected_submitted, $expected_saved)
    {
        $actual_submitted = $this->_pcm->get($section_id)->submitted_count();
        $actual_saved = $this->_pcm->get($section_id)->saved_count();

        $this->assertEquals($expected_submitted, $actual_submitted);
        $this->assertEquals($expected_saved, $actual_saved);
    }

    public function GetCountProvider()
    {
        return array(
            array(9, 3, 2),
            array(45, 1, 1)
        );
    }

    // ------------------------------------------------------------------------

}
