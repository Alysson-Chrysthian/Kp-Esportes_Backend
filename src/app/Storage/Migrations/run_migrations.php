<?php

use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;

require_once(__DIR__ . "/../../../vendor/autoload.php");

Env::load(".env.test");

$db = new SqlDatabase;
$db->connect();

$database_driver = Env::get("DB_DRIVER");
$dir = __DIR__ . "/" . $database_driver;

$migration_files = scandir($dir);

foreach($migration_files as $file) {
    if (!str_ends_with($file, ".sql"))
        continue;

    $query = file_get_contents($dir . "/" . $file);
    echo "running " . strtoupper($file);

    $db->query($query);
    echo "\t--\tdone\n";
}

$db->close();