<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class User_model extends CI_model
{
    public function __construct()
    {
        // loading database file for the web application
        $this->load->database();
    }

    public function getAdmins()
    {
        // TODO: consider breaking names in DB to be atomic... should not need to do this sort of thing 
        $this->db->select("*, SUBSTRING_INDEX(name,' ',1) as first_name, SUBSTRING_INDEX(name,' ',-1) as last_name", false);
        $this->db->from('admin');
        $this->db->order_by('last_name, first_name');
        $result = $this->db->get();

        return $result;
    }

    public function deleteInstructors()
    {
        $this->db->delete('instructor');
    }

    /**
     * Checks whether an instructor exists.
     * @return bool
     */
    public function instructorExists($inst_id)
    {
        $this->db->select('*');
        $this->db->from('instructor');
        $this->db->order_by('inst_id_hashed', $inst_id);

        return $this->db->get()->num_rows() > 0;
    }

    public function isAdmin($username)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username', $username);
        $result = $this->db->get();

        return $result->num_rows() != 0;
    }

    public function duplicateAdminExists($fname, $username)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where("(username = '$username') OR (name = '$fname')");
        $result = $this->db->get();

        return $result->num_rows() != 0;
    }

    public function getAdmin($username = null)
    {
        $user = array('username' => $username);

        if ($username !== null)
        {
            $this->db->select('*');
            $this->db->from('admin');
            $this->db->where('username', $username);
            $query = $this->db->get();
            if ($query->num_rows() == 1)
            {
                $user = $query->row_array();
                $user['valid'] = true;
                $user['group'] = 'admin';
            }
        }

        return $user;
    }
    
    public function getInstructor($username = null)
    {
        $user = array('inst_id_hashed' => $username);

        if ($username !== null)
        {
            $this->db->select('*');
            $this->db->from('instructor');
            $this->db->where('inst_id_hashed', $username);
            $query = $this->db->get();

            if ($query->num_rows() == 1)
            {
                $user = $query->row_array();
                $user['valid'] = true;
                $user['group'] = 'instructor';
            }
        }

        return $user;
    }

    public function getInstructorId($name)
    {
        $this->db->select('inst_id_hashed');
        $this->db->from('instructor');
        $this->db->where('CONCAT(first_name, " ", last_name) =', $name);
        $query = $this->db->get()->result_array();

        return $query[0]['inst_id_hashed'];
    }

    public function deleteAdmin($username)
    {
        $this->db->delete('admin', array('username' => $username));
    }

    public function addAdmin($name, $username, $email)
    {
        $row = array('name' => $name, 'username' => $username, 'email' => $email);
        $this->db->insert('admin', $row);
    }

    public function addInstructor($fname, $lname, $username, $email)
    {
        $row = array('first_name' => $fname, 'last_name' => $lname, 'inst_id_hashed' => $username, 'email' => $email);
        $this->db->insert('instructor', $row);
    }
}