<?php
class Database {
    private $db_server = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "elderlyassistancecoordinationdb";
    public $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->db_server, $this->db_user, $this->db_pass, $this->db_name);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . htmlspecialchars($this->conn->connect_error));
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>