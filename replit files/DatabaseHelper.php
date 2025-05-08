<?php
class DatabaseHelper {
    private $host;
    private $dbName;
    private $username;
    private $password;
    /** @var PDO */
    private $pdo;

    public function __construct($host, $dbName, $username, $password) {
        $this->host     = $host;
        $this->dbName   = $dbName;
        $this->username = $username;
        $this->password = $password;
    }

    /** @return PDO */
    private function getPDO() {
        if (!$this->pdo) {
            $this->pdo = new PDO(
                "mysql:host={$this->host};charset=utf8mb4",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            // Ensure database and table exist
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbName}`");
            $this->pdo->exec("USE `{$this->dbName}`");
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS `study_groups` (
                    `id`            INT AUTO_INCREMENT PRIMARY KEY,
                    `subject_code`  VARCHAR(100) NOT NULL,
                    `section`       VARCHAR(50)  NOT NULL,
                    `college`       VARCHAR(100) NOT NULL,
                    `wlink`         VARCHAR(255) NOT NULL,
                    `created_at`    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
        return $this->pdo;
    }

    /**
     * Insert a new study group.
     * @return int|false  Last-insert ID on success, false on failure
     */
    public function addGroup(string $subjectCode, string $section, string $college, string $wlink) {
        try {
            $stmt = $this->getPDO()->prepare("
                INSERT INTO `study_groups`
                  (`subject_code`, `section`, `college`, `wlink`)
                VALUES
                  (:subject, :section, :college, :wlink)
            ");
            $stmt->execute([
                ':subject' => $subjectCode,
                ':section' => $section,
                ':college' => $college,
                ':wlink'   => $wlink,
            ]);
            return (int)$this->getPDO()->lastInsertId();
        } catch (PDOException $e) {
            error_log("addGroup error: " . $e->getMessage());
            return false;
        }
    }

    /** Fetch all groups */
    public function fetchGroups(): array {
        $stmt = $this->getPDO()->query("SELECT * FROM `study_groups` ORDER BY `created_at` DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
