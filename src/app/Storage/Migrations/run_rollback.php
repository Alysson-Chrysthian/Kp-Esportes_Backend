<?php

use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;

require_once(__DIR__ . "/../../../vendor/autoload.php");

Env::load(".env.test");

$db = new SqlDatabase;
$db->connect();

$database_driver = Env::get("DB_DRIVER");
$dir = __DIR__ . "/" . $database_driver;

$migrations_directory = scandir($dir);
$migrations_directory = array_reverse($migrations_directory);

foreach ($migrations_directory as $filename) {
    if (!str_ends_with($filename, ".sql"))
        continue;
    
    $query = file_get_contents($dir . "/" . $filename);
    preg_match("#CREATE TABLE IF NOT EXISTS ([A-z]{1,})#", $query, $matches);
    array_shift($matches);

    echo "rolling back " . strtoupper($filename);

    $db->query("DROP TABLE IF EXISTS " . $matches[0]);

    echo "\t--\troolback_complete\n";
}

$db->close();