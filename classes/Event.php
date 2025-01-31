<?php

require_once 'Database.php';
require_once 'Auth.php';

class Event {

    private $conn;

    public function __construct() {

        $this->conn = new Database();
    }

    public function index($col, $table, $user_id = null) {
      
        $result = $this->conn->select($col, $table, $user_id);
        return $result;
    }

    // insert Function for book
    public function insert($table, $data = []) {
        $result = $this->conn->insert($table, $data);
        return $result;
    }

    /**
     * 
     * Where Take array values     
     */
    public function find($colmns, $table, $where) {
        $result = $this->conn->select($colmns, $table, $where)->fetch_assoc();
        return $result;
    }

    public function update($table, $updateArr, $whereArr) {
        $result = $this->conn->update($table, $updateArr, $whereArr);
        return $result;
    }

    public function delete($table, $where) {
        $result = $this->conn->delete($table, $where);
        return $result;
    }

    public function pagination($colms, $table, $limit, $offset, $user_id = null, $sortColumn='', $sortOrder='', $min_capacity = null, $max_capacity = null, $searchColumn= null, $search = null) {

   
        $result = $this->conn->pagination($colms, $table, $limit, $offset,$user_id, $sortColumn, $sortOrder, $min_capacity, $max_capacity, $searchColumn, $search);
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function join($colums, $maintable, $jointypes, $joinTables, $joinConditions,$where = '',$limit='', $offset='') {
        $result = $this->conn->join($colums, $maintable, $jointypes, $joinTables, $joinConditions,$where,$limit, $offset);
        return $result;
    }

}
