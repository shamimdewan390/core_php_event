<?php

require_once 'Database.php';
require_once 'Auth.php';

class Attendee
{

    private $conn;

    public function __construct()
    {

        $this->conn = new Database();
    }

    public function index($col, $table)
    {
        $result = $this->conn->select($col, $table);
        return $result;
    }

    // insert Function for book
    public function insert($table, $data = [])
    {
        $result = $this->conn->insert($table, $data);
        return $result;
    }
    /**
     * 
     * Where Take array values     
     */
    public function find($colmns, $table, $where)
    {
        $result = $this->conn->select($colmns, $table, $where)->fetch_assoc();
        return $result;
    }
    public function countRows($colmns, $table, $where)
    {
        $result = $this->conn->countRows($colmns, $table, $where);
        return $result;
    }

    


    public function pagination($colms, $table, $limit, $offset)
    {
        $result = $this->conn->pagination($colms, $table, $limit, $offset);
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function join($colums, $maintable, $jointypes, $joinTables, $joinConditions, $where = '', $limit = '', $offset = '')
    {
        $result = $this->conn->join($colums, $maintable, $jointypes, $joinTables, $joinConditions, $where, $limit, $offset);
        return $result;
    }
}
