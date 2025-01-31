<?php

class Database {

    private $host = "localhost";
    private $user = "localhost_user";
    private $password = "12345678";
    private $dbName = "localhost";
    protected $conn;

    public function __construct() {
        // Link of Database
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);
    }

    public function countRows($colmns, $table, $where = ["key" => "value"]) {
        $where = $this->checkWhere($where);
        $sql = "SELECT COUNT($colmns) AS total FROM {$table} {$where}";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Select Function 
    public function select($colmns = ['columnName1', 'columnName2'], $table, $where = ["key" => 'value']) {

        $colmns = $this->getCol($colmns);
        $where = $this->checkWhere($where);
        $sql = "SELECT {$colmns} FROM {$table} {$where}";
        // echo $sql;exit;
        $result = $this->conn->query($sql);
        return $result;
    }

    // Insert Function
    public function insert($table, $data = ["colName" => "value"]) {

        $formattedData = $this->formatData($data);
        
        if (!$formattedData) {
            return "Invalid data format!";
        }
    
        $sql = "INSERT INTO {$table} {$formattedData}";
    
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return "Error in SQL preparation: " . $this->conn->error;
        }
    
        $types = str_repeat('s', count($data)); // Assuming all values are strings
        $values = array_values($data);
        
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "Error: " . $stmt->error;
        }
    }
    // public function insert($table, $data = ["colName" => "value"]) {

    //     $sql = "INSERT INTO {$table} {$this->formatData($data)} ";
      
    //     $result = $this->conn->query($sql);

    //     if ($this->conn->affected_rows == 1) {
    //         return $result;
    //     } else {
    //         return $this->conn->error;
    //     }
    // }

    //Update Function
    public function update($table, $updateArr = [], $where = ["key" => 'value']) {
        $updateArr = $this->updateArr($updateArr);
        $where = $this->checkWhere($where);
        $sql = "UPDATE {$table} SET {$updateArr} {$where}";
    //    echo $sql;exit;
        $result = $this->conn->query($sql);
        if ($result) {
            return $result;
        } else {
            return 'error';
        }
    }

    // Delete Function
    public function delete($table, $where = ['key' => 'value']) {

        $where = $this->checkWhere($where);
        $sql = "DELETE FROM {$table} {$where}";
        // echo $sql;exit;
        $result = $this->conn->query($sql);
        if ($result) {
            return $result;
        } else {
            return '';
        }
    }

    // Paginate Function
    public function pagination($columns, $table, $limit, $offset, $user_id = null, $sortColumn = 'id', $sortOrder = 'DESC', $min_capacity = null, $max_capacity = null, $searchColumn = '', $search = '') {
        $colmn = $this->getCol($columns);
    
        // Initialize base query
        $sql = "SELECT {$colmn} FROM {$table}";
    
        // Array to store WHERE conditions
        $whereClauses = [];
        $params = [];
    
        // Add user_id filter only if provided
        if (!is_null($user_id)) {
            $whereClauses[] = "user_id = ?";
            $params[] = $user_id;
    }
    
        // Add capacity filter
        if (!is_null($min_capacity) && !is_null($max_capacity)) {
            $whereClauses[] = "capacity BETWEEN ? AND ?";
            $params[] = $min_capacity;
            $params[] = $max_capacity;
        }
    
        // Add search filter
        if (!empty($searchColumn) && !empty($search)) {
            $whereClauses[] = "{$searchColumn} LIKE ?";
            $params[] = "%$search%";
        }
    
        // Add WHERE clause if needed
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
    
        // Ensure sorting is applied (default is `id DESC`)
        $sql .= " ORDER BY {$sortColumn} {$sortOrder}";
    
        // Add LIMIT and OFFSET
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = (int) $limit;
        $params[] = (int) $offset;
    
        // Prepare statement
        $stmt = $this->conn->prepare($sql);
        
        // Bind parameters dynamically
        if (!empty($params)) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }
    
        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    
    
    
    // public function pagination($colmns,$table,$limit,$offset, $sortColumn = '', $sortOrder = '') {

    //     $colmn = $this->getCol($colmns);
    //     $sql = "SELECT {$colmn} FROM {$table} WHERE capacity BETWEEN 1 AND 100 ORDER BY $sortColumn $sortOrder LIMIT {$limit} OFFSET {$offset}";
        
    //     $result = $this->conn->query($sql);
    //     return $result;
    // }

    public function join($colums = '', $maintable = '', $jointype = ['INNER JOIN'], $joinTables = ['other tables'], $joinConditions = ["ON Condition"], $where = '', $limit = '', $offset = '') {
        $where = $this->checkWhere($where);

        if (empty($colums) or!is_string($colums)) {     // checking column if exist as string
            return "<label class=" . "text-danger" . "> Please Enter Columns Name </label> ";
        } elseif (empty($maintable) or!is_string($maintable)) {        //checking main Table if exist as string
            return "<label class=" . "text-danger" . "> Please Enter Main Table </label> ";
        } elseif (empty($jointype) or!is_array($jointype)) {        // checking join type if exist as an array
            return "<label class=" . "text-danger" . "> Please Enter join Type as an array e.g. ['Inner JOIN','LEFT JOIN'] </label> ";
        } elseif (empty($joinTables) or!is_array($joinTables)) {        // checking Table names if exist as an array
            return "<label class=" . "text-danger" . "> Please Enter joining Tables Name as an array e.g. ['table1','table2'] </label> ";
        } elseif (empty($joinConditions) or!is_array($joinConditions)) {     // checking Joining Condition if exist as an array
            return "<label class=" . "text-danger" . "> Please Enter joining Condition as an array e.g. ['table1.id = table2_id','table2'] </label> ";
        } elseif (count($joinTables) != count($jointype) && count($joinConditions) != count($jointype)) {
            return "<label class=" . "text-danger" . "> Some thing wrong in Join type or tables </label> ";
        } else {
            $sql = "SELECT {$colums} FROM {$maintable }";
            foreach ($jointype as $key => $join) {
                $sql .= " {$join} {$joinTables[$key]} ON {$joinConditions[$key]} ";
            }
            if ($limit == '') {
                $sql .= "{$where} ";
            } else {
                $sql .= "{$where} LIMIT {$limit} OFFSET {$offset}";
            }
//            echo $sql;exit;
            $result = $this->conn->query($sql);
            if ($result) {
                return $result;
            } else {
                return $this->conn->error;
            }
        }
    }

    /**
     * 
     * These Are Helper Functions
     *      
     */
    // Colummns checher Whether it has aray value or string type value
    private function getCol($colmns) {
        if (!empty($colmns)) {

            if (is_array($colmns) && $colmns !== ['columnName1', 'columnName2']) {
                $sql = implode(",", $colmns);
                return $sql;
            } else {
                return "*";
            }
        } else {
            return '*';
        }
    }

    // This will make WHERE Claus 
    private function checkWhere($where) {
        if (!empty($where) && is_array($where) && $where !== ["key" => 'value']) {
            $sql = " WHERE ";
            $count = 1;
            foreach ($where as $key => $value) {
                if ($count == count($where)) {
                    $sql .= " {$key} = '{$value}' ";
                } else {
                    $sql .= " {$key}  = '{$value}' AND " ;
                }
                $count++;
            }
            
            return $sql;
        } else {
            return '';
        }
    }

    // this will manage post data for Update
    private function updateArr($updateArr) {
        if (!empty($updateArr)) {
            $sql = '';
            $count = 1;
            foreach ($updateArr as $key => $value) {
                if ($value == '' or $key == 'id') {
                    $sql ='';
                }
                elseif ($count == count($updateArr)) {
                    $sql .= " {$key} = '{$value}' ";
                } else {
                    $sql .= " {$key} = '{$value}', ";
                    ;
                }
                $count++;
            }
            return $sql;
        } else {
            return '';
        }
    }

    // this will format inserted data from $_POST
    private function formatData($data) {
        if (!empty($data) && $data !== ["colName" => "value"]) {
            $columnNames = array_keys($data);
            $placeholders = array_fill(0, count($data), '?');
    
            $colName = implode(", ", $columnNames);
            $colValue = implode(", ", $placeholders);
    
            return "( {$colName} ) VALUES ( {$colValue} )";
        } else {
            return '';
        }
    }
    // private function formatData($data) {
    //     if (!empty($data) && $data !== ["colName" => "value"]) {
    //         $columnName = array_keys($data);
    //         $columnValue = array_values($data);
    //         $colName = '';
    //         $colValue = '';
    //         foreach ($columnName as $key => $value) {
    //             if ($key == count($columnName) - 1) {
    //                 $colName .= " {$value} ";
    //                 $colValue .= " '$columnValue[$key]' ";
    //             } else {
    //                 $colName .= " {$value}, ";
    //                 $colValue .=  " '$columnValue[$key]', ";
    //             }
    //         }
    //         $string = "( {$colName} ) VALUES ( {$colValue} )";

    //         return $string;
    //     } else {
    //         return '';
    //     }
    // }
    
    

}
