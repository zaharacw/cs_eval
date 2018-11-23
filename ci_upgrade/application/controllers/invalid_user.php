<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/*
	This is just a message that you don't have permissions
*/

class Invalid_user extends CI_Controller
{
    public function index()
    {
        $error = '<p>Sorry for the inconvenience, but you do not currently have appropriate access for '
            . 'this web application. You may have been away from the web page for too long '
            . 'without any user input activity. <strong>Please try logging in again.</strong></p>'
            . '<p>If that does not solve your problem, please contact the '
            . 'department corresponding to the affected course(s).';

        $this->load->view('error_view', array('message' => $error));
    }
}