<?php

class Database
{

    private $host = "localhost";
    private $user = "localhost_user";
    private $password = "12345678";
    private $dbName = "localhost";
    protected $conn;

    public function __construct()
    {
        // Link of Database
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function countRows($column, $table, $where = []) {
        $params = [];
        $types = "";
    
        // Validate input
        if (empty($column) || !is_string($column)) {
            return "<label class='text-danger'> Please enter a valid column name </label>";
        }
        if (empty($table) || !is_string($table)) {
            return "<label class='text-danger'> Please enter a valid table name </label>";
        }
    
        // Secure WHERE clause
        $whereClause = $this->checkWhere($where, $params, $types);
        $sql = "SELECT COUNT($column) AS total FROM {$table} {$whereClause}";
    
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return $this->conn->error;
        }
    
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        } else {
            return "Error: " . $stmt->error;
        }
    }
    

    // Select Function 
    public function select($columns = ['columnName1', 'columnName2'], $table, $where = ["key" => 'value'])
    {
        $columns = $this->getCol($columns); // Ensure valid column formatting

        $params = [];
        $types = "";

        $whereClause = $this->checkWhere($where, $params, $types);

        $sql = "SELECT {$columns} FROM {$table} {$whereClause}";
        $stmt = $this->conn->prepare($sql);

        if (!empty($where)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }


    // Insert Function
    public function insert($table, $data = ["colName" => "value"])
    {

      
        $formattedData = $this->formatData($data);

        if (!$formattedData) {
            return "Invalid data format!";
        }

        $sql = "INSERT INTO {$table} {$formattedData}";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return "Error in SQL preparation: " . $this->conn->error;
        }

        $types = str_repeat('s', count($data));
        $values = array_values($data);

        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            return true;
        } else {
            return "Error: " . $stmt->error;
        }
    }

    //Update Function
    public function update($table, $updateArr = [], $where = ["key" => 'value'])
    {
        if (empty($updateArr) || empty($where)) {
            return 'error: missing parameters';
        }

        $params = [];
        $types = "";

        $setParts = [];
        foreach ($updateArr as $column => $value) {
            $setParts[] = "{$column} = ?";
            $params[] = $value;
            $types .= $this->getType($value);
        }
        $setSql = implode(", ", $setParts);

        // Prepare WHERE statement using your `checkWhere` method

        $whereClause = $this->checkWhere($where, $params, $types);

        $sql = "UPDATE {$table} SET {$setSql} {$whereClause}";

        // Prepare and execute statement
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            return 'error';
        }
    }

    // Helper function to detect data type for binding
    private function getType($var)
    {
        if (is_int($var)) return 'i';
        if (is_double($var)) return 'd';
        return 's';
    }


    // Delete Function
    public function delete($table, $where = ['key' => 'value'])
    {
        if (empty($where) || $where === ['key' => 'value']) {
            return false; // Prevent accidental full table deletion
        }

        $params = [];
        $types = "";

        $whereClause = $this->checkWhere($where, $params, $types);
        $sql = "DELETE FROM {$table} {$whereClause}";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    // Paginate Function
    public function pagination($columns, $table, $limit, $offset, $user_id = null, $sortColumn = 'id', $sortOrder = 'DESC', $min_capacity = null, $max_capacity = null, $searchColumn = '', $search = '')
    {
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


    public function join($columns = '*', $mainTable = '', $joinType = ['INNER JOIN'], $joinTables = [], $joinConditions = [], $where = [], $limit = '', $offset = '') {
        if (empty($columns) || !is_string($columns)) {
            return "<label class='text-danger'> Please enter column names </label>";
        }
        if (empty($mainTable) || !is_string($mainTable)) {
            return "<label class='text-danger'> Please enter main table name </label>";
        }
        if (empty($joinType) || !is_array($joinType)) {
            return "<label class='text-danger'> Please enter join types as an array </label>";
        }
        if (empty($joinTables) || !is_array($joinTables)) {
            return "<label class='text-danger'> Please enter joining table names as an array </label>";
        }
        if (empty($joinConditions) || !is_array($joinConditions)) {
            return "<label class='text-danger'> Please enter joining conditions as an array </label>";
        }
        if (count($joinTables) != count($joinType) || count($joinConditions) != count($joinType)) {
            return "<label class='text-danger'> Something is wrong in join type, tables, or conditions </label>";
        }
    
        $sql = "SELECT {$columns} FROM {$mainTable}";
    
        foreach ($joinType as $key => $join) {
            $sql .= " {$join} {$joinTables[$key]} ON {$joinConditions[$key]} ";
        }
    
        $params = [];
        $types = "";
    
        $whereClause = $this->checkWhere($where, $params, $types);
    
        if (!empty($whereClause)) {
            $sql .= " {$whereClause}";
        }
    
        if (!empty($limit) && is_numeric($limit)) {
            $sql .= " LIMIT ?";
            $params[] = (int)$limit;
            $types .= "i";
        }
        if (!empty($offset) && is_numeric($offset)) {
            $sql .= " OFFSET ?";
            $params[] = (int)$offset;
            $types .= "i";
        }
    
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return $this->conn->error;
        }
    
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
        if ($stmt->execute()) {
            return $stmt->get_result();
        } else {
            return $stmt->error;
        }
    }
    

    /**
     * 
     * These Are Helper Functions
     *      
     */
    // Colummns checher Whether it has aray value or string type value
    private function getCol($colmns)
    {
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
    private function checkWhere($where, &$params, &$types)
    {
        if (!empty($where) && is_array($where) && $where !== ["key" => 'value']) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} = ?";
                $params[] = $value;
                $types .= "s"; // Assuming all values are strings; modify if needed
            }
            return " WHERE " . implode(" AND ", $conditions);
        }
        return '';
    }


    // this will manage post data for Update
    private function updateArr($updateArr)
    {
        if (!empty($updateArr)) {
            $sql = '';
            $count = 1;
            foreach ($updateArr as $key => $value) {
                if ($value == '' or $key == 'id') {
                    $sql = '';
                } elseif ($count == count($updateArr)) {
                    $sql .= " {$key} = '{$value}' ";
                } else {
                    $sql .= " {$key} = '{$value}', ";;
                }
                $count++;
            }
            return $sql;
        } else {
            return '';
        }
    }

    // this will format inserted data from $_POST
    private function formatData($data)
    {
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
}
