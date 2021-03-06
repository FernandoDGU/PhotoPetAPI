<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'photopetdb';
    private $username = 'root';
    private $password = 'photopetapi';

    private $conn;

    public function connect()
    {
        $this->con = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            die();
        }

        return $this->conn;
    }
}
