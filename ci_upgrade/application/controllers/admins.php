<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Admins extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');

        if (!$this->isSuper())
        {
            die($this->load->view('error_view', array('message' => 'You are not a superadmin.'), true));
        }
    }

    /**
     * Display list of admins
     */
    public function index()
    {
        $currentUsername = $this->cas->get_user();
        $results = $this->User_model->getAdmins();
        $data['admins'] = array();

        foreach ($results->result_array() as $row)
        {
            $newAdmin = array('name' => $row['name'], 'username' => $row['username'], 'email' => $row['email'], 'super' => $row['super'] == 1);
            $data['admins'][] = $newAdmin;

            if ($currentUsername == $row['username'])
            {
                $data['currentAdmin'] = $newAdmin;
            }
        }

        $this->load->view('admin_management_view', $data);
    }

    /**
     * POST: name, username, email
     */
    public function add()
    {
        ob_clean();
        $name = $this->input->post('name');
        $userName = $this->input->post('username');
        $email = $this->input->post('email');

        if ($this->hasNull(array($name, $userName, $email)))
        {
            return;
        }

        if ($this->User_model->duplicateAdminExists($name, $userName))
        {
            echo 0;
        }
        else
        {
            $this->initializeAdmin($name, $userName, $email);
        }
    }

    /**
     * POST: name, username, email, oldName, oldUsername, oldEmail
     */
    public function modify()
    {
        ob_clean();

        $oldName = $this->input->post('oldName');
        $oldUsername = $this->input->post('oldUsername');
        $oldEmail = $this->input->post('oldEmail');
        $username = $this->input->post('username');
        $name = $this->input->post('name');
        $email = $this->input->post('email');

        if ($this->hasNull(array($oldName, $oldUsername, $oldEmail, $username, $name, $email)))
        {
            return;
        }

        $currentUser = $this->cas->get_user();
        $priorAdmin = $this->User_model->getAdmin($oldUsername);

        if ($priorAdmin['super'])
        {
            die('2');
        }

        // cannot modify ourselves
        if ($oldUsername == $currentUser || $username == $currentUser)
        {
            die('1');
        }

        $this->User_model->deleteAdmin($oldUsername);

        if ($this->User_model->duplicateAdminExists($name, $username))
        {
            $this->User_model->addAdmin($oldName, $oldUsername, $oldEmail);
            echo 0;
        }
        else
        {
            $this->initializeAdmin($name, $username, $email);
        }
    }

    /**
     * POST: username
     */
    public function remove()
    {
        ob_clean();
        $username = $this->input->post('username');

        if ($username == null)
        {
            return;
        }

        $priorAdmin = $this->User_model->getAdmin($username);

        if (!$priorAdmin['super'] && $this->cas->get_user() != $username)
        {
            $this->User_model->deleteAdmin($username);
            echo $username;
        }
    }

    private function isSuper()
    {
        $currentUsername = $this->cas->get_user();
        $user = $this->User_model->getAdmin($currentUsername);
        return $user['super'] == 1;
    }

    private function initializeAdmin($name, $username, $email)
    {
        $this->User_model->addAdmin($name, $username, $email);
        $data['name'] = $name;
        $data['user'] = $username;
        $data['email'] = $email;
        echo json_encode($data);
    }
}