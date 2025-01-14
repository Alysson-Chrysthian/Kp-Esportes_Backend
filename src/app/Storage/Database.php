<?php

namespace KpEsportes\App\Storage;

use PDO;

abstract class Database {

    protected string $driver;
    protected string $username;
    protected string $password;
    protected string $database;
    protected string $host;
    protected string $port;

    protected PDO|null $conn;

    public abstract function connect();
    public abstract function persist(string $query, array $binds = null);
    public abstract function fetch(string $query, string $class = null, array $binds = null);
    public abstract function query(string $query);
    public abstract function close();

}