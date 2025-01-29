index.php
views/event/index.php
views/event/create.php
views/layout/header.php
views/layout/footer.php
login.php

project/
├── index.php
├── views/register.php
├── views/login.php
├── views/logout.php
├── views/event/index.php
├── views/event/create.php
├── classes/Event.php
├── views/layout/
│   ├── header.php
│   ├── footer.php
├── README.md



       // $sql = "CREATE TABLE users (
        //     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        //     name VARCHAR(30) NOT NULL,
        //     email VARCHAR(50),
        //     password VARCHAR(200) NOT NULL,
        //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        //     )";
            
        //     if ($this->conn->query($sql) === TRUE) {
        //       echo "Table users created successfully";
        //     } else {
        //       echo "Error creating table: " . $this->conn->error;
        //     }

        //     die();



        <!-- $sql = "CREATE TABLE events (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            date DATE NOT NULL,
            capacity INT(11) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
    
        if ($this->conn->query($sql) === TRUE) {
            echo "Table events created successfully";
        } else {
            echo "Error creating table: " . $this->conn->error;
        } -->