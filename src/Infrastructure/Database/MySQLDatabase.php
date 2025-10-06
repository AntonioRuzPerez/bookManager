<?php
class MySQLDatabase {
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private mysqli $connection;
    public function __construct(string $host, string $username, string $password, string $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
    }

    public function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }
        return $this->connection;
    }

    public function query(string $sql, array $params = [], string $types = ''): mysqli_result|bool {
        if (!isset($this->connection)) {
            $this->connect();
            if (!isset($this->connection)) {
                throw new RuntimeException('Database connection not established. Call connect() first.');
            }
        }

        if (empty($params)) {
            $result = $this->connection->query($sql);
            if ($result === false) {
                throw new RuntimeException('Query failed: ' . $this->connection->error . ' SQL: ' . $sql);
            }
            return $result;
        }

        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            throw new RuntimeException('Prepare failed: ' . $this->connection->error . ' SQL: ' . $sql);
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = str_repeat('s', count($params));
            }
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException('Execute failed: ' . $stmt->error . ' SQL: ' . $sql);
        }

        $result = $stmt->get_result();
        return $result;
    }

    public function executeCommand(string $sql, array $params = [], string $types = ''): bool {
        if (!isset($this->connection)) {
            $this->connect();
            if (!isset($this->connection)) {
                throw new RuntimeException('Database connection not established. Call connect() first.');
            }
        }

        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            throw new RuntimeException('Prepare failed: ' . $this->connection->error . ' SQL: ' . $sql);
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = str_repeat('s', count($params));
            }
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException('Execute failed: ' . $stmt->error . ' SQL: ' . $sql);
        }

        return true;
    }

    public function getLastInsertId(): int{
        if (!isset($this->connection)) {
            throw new RuntimeException('Database connection not established.');
        }
        return $this->connection->insert_id;
    }

    public function get_row(string $sql) {
        $result = $this->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $result->free_result();
            return $row;
        } else {
            return null;
        }
    }

    public function get_all(string $sql) {
        $result = $this->query($sql);
        $data = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free_result();
        }
        return $data;
    }

    public function escape_string(string $string) {
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection->real_escape_string($string);
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __destruct() {
        $this->close();
    }
}