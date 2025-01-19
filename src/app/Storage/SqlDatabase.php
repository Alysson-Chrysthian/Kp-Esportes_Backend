<?php

namespace KpEsportes\App\Storage;

use KpEsportes\App\Util\Env;
use PDO;

class SqlDatabase extends Database {

    public function __construct() {
        $this->driver = Env::get("DB_DRIVER");
        $this->username = Env::get("DB_USERNAME");
        $this->password = Env::get("DB_PASSWORD");
        $this->database = Env::get("DB_NAME");
        $this->host = Env::get("DB_HOST");
        $this->port = Env::get("DB_PORT");
    }

    public function connect() {
        $dsn = $this->driver . ":";
        $dsn .= "host=" . $this->host . ";";
        $dsn .= "port=" . $this->port . ";";
        $dsn .= "dbname=" . $this->database . ";";

        $this->conn = new PDO($dsn, $this->username, $this->password); 
    }

    public function fetch(string $query, string $class = null, array $binds = null) {
        $prepared_statement = $this->conn->prepare($query);
        $prepared_statement->execute($binds);

        $result = $prepared_statement->fetchAll(PDO::FETCH_CLASS, $class);

        return $result;
    }

    public function fetchFirst(string $query, string $class = null, array $binds = null) {
        $prepared_statement = $this->conn->prepare($query);
        $prepared_statement->execute($binds);
        
        if ($class != null)
            $prepared_statement->setFetchMode(PDO::FETCH_CLASS, $class);
        
        $result = $prepared_statement->fetch();

        return $result ? $result : null;
    }

    public function agregate(string $query, string $agregation_function, array $binds = null) {
        $prepared_statement = $this->conn->prepare($query);
        $prepared_statement->execute($binds);
        $result = $prepared_statement->fetch()[$agregation_function];

        return $result;
    }

    public function persist(string $query, array $binds = null) {
        $prepared_statement = $this->conn->prepare($query);
        $rows = $prepared_statement->execute($binds);
        
        return $rows;
    }

    public function query(string $query) {
        $this->conn->query($query);
    }

    public function close() {
        $this->conn = null;
    }

}