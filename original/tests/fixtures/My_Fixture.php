<?php

class My_Fixture extends Fixture
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * loads fixture data $fixt into corresponding table
     */
    function load($table, $fixt)
    {
        $this->_assign_db();

        // $fixt is supposed to be an associative array
        // E.g. outputted by spyc from reading a YAML file
        $this->CI->db->simple_query('SET FOREIGN_KEY_CHECKS = 0;');
        $this->CI->db->simple_query('truncate table ' . $table . ';');

        foreach ($fixt as $id => $row)
        {
            foreach ($row as $key => $val)
            {
                if ($val !== '')
                {
                    $row["`$key`"] = $val;
                }
                //unset the rest
                unset($row[$key]);
            }

            $this->CI->db->insert($table, $row);
        }

        $nbr_of_rows = sizeof($fixt);
        log_message('debug',
            "Data fixture for db table '$table' loaded - $nbr_of_rows rows");
    }

    private function _assign_db()
    {
        if (!isset($this->CI->db) OR
            !isset($this->CI->db->database)
        )
        {
            $this->CI =& get_instance();
            $this->CI->load->database();
        }

        //security measure 2: only load if used database ends on '_test'
        $len = strlen($this->CI->db->database);

        if (substr($this->CI->db->database, $len - 5, $len) != '_test')
        {
            die("\nSorry, the name of your test database must end on '_test'.\n" .
                "This prevents deleting important data by accident.\n");
        }
    }
}
