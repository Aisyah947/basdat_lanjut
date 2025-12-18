<?php
class Database {
    private $host = "localhost";
    private $db_name = "restoran_db";
    private $username = "postgres";
    private $password = "admin123";
    private $port = "5433";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: ".$exception->getMessage();
        }
        return $this->conn;
    }
}
?>
