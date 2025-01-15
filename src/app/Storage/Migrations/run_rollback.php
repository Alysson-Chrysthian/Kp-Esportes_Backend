<?php

use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;

require_once(__DIR__ . "/../../../vendor/autoload.php");

Env::load(".env.test");

$db = new SqlDatabase;
$db->connect();

$migrations_directory = scandir(__DIR__);
$migrations_directory = array_reverse($migrations_directory);

foreach ($migrations_directory as $filename) {
    if (!str_ends_with($filename, ".sql"))
        continue;
    
    $query = file_get_contents(__DIR__ . "/" . $filename);
    preg_match("#CREATE TABLE IF NOT EXISTS ([A-z]{1,})#", $query, $matches);
    array_shift($matches);

    echo "rolling back " . strtoupper($filename);

    $db->query("DROP TABLE IF EXISTS " . $matches[0]);

    echo "\t--\troolback_complete\n";
}

$db->close();