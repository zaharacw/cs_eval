<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
    }

    public function index()
    {
        $settings = $this->Settings_model->loadSettings();

        $data['mainMessage'] = $this->replaceBreaks($settings->mainMessage);
        $data['evalMessage'] = $this->replaceBreaks($settings->evalMessage);
        $data['developerMode'] = $this->replaceBreaks($settings->developerMode);

        $data['success'] = $this->session->flashdata('success');

        $this->load->view('settings_view', $data);
    }

    public function modify_settings()
    {
        ob_clean();

        $mainMessage = $this->replaceNewlines($this->input->post('mainMessage'));
        $this->Settings_model->modifySetting('mainMessage', $mainMessage);

        $evalMessage = $this->replaceNewlines($this->input->post('evalMessage'));
        $this->Settings_model->modifySetting('evalMessage', $evalMessage);

        $developerMode = $this->input->post('developerMode') == 'enabled';
        $this->Settings_model->modifySetting('developerMode', $developerMode);

        $this->session->set_flashdata('success', 'Your settings have been saved.');
        redirect(base_url('settings'));
    }

    private function replaceNewlines($str)
    {
        return preg_replace('/(\r\n|\n|\r)/', '<br/>', $str);
    }

    private function replaceBreaks($str)
    {
        return str_replace('<br/>', "\r\n", $str);
    }
}