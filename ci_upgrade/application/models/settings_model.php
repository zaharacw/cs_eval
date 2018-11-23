<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Settings_model extends CI_model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
    }

    public function loadSettings()
    {
        $settings = read_file('./eval_settings.json');

        if (!$settings)
        {
            $defaults = read_file('./eval_settings.default.json');
            write_file('./eval_settings.json', $defaults);
            $settings = $defaults;
        }

        return json_decode($settings);
    }

    public function saveSettings($settings)
    {
        write_file('./eval_settings.json', json_encode($settings));
    }

    public function modifySetting($key, $value)
    {
        $settings = json_decode(read_file('./eval_settings.json'), true);
        $settings[$key] = $value;
        write_file('./eval_settings.json', json_encode($settings));
    }

    public function isDeveloperMode()
    {
        // return value unless it is null
        $value = $this->loadSettings()->developerMode;

        return $value == null ? false : $value;
    }
}