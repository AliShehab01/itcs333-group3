
<?php
class Database {
    private $host = "127.0.0.1";
    private $db_name = "mydb";
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        $this->username = getenv("db_user");
        $this->password = getenv("db_pass");
    }

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>
