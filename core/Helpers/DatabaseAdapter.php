<?php

namespace Erp360\Core\Helpers;

use PDO;
use PDOException;

class DatabaseAdapter {

    public $hostname, $dbname, $username, $password, $conn;

    public function __construct() {
        $this->host_name = "localhost";
        $this->dbname = $_ENV['DB_NAME'];
        $this->username =  $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        try {
            $this->conn = new PDO("mysql:host=$this->host_name;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}