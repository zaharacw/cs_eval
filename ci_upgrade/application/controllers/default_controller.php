<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Default_controller extends MY_Controller
{
    public function index()
    {
        $array = $this->session->all_userdata();

        if (!isset($array['default_account']))
        {
            $this->session->set_userdata('url', base_url());
            redirect(base_url() . 'auth/redirect');
        }

        redirect(base_url() . $this->homeControllers[$array['default_account']]);
    }
}