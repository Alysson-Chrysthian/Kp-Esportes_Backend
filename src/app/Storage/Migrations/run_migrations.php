<?php

use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;

require_once(__DIR__ . "/../../../vendor/autoload.php");

Env::load(".env.test");

$db = new SqlDatabase;
$db->connect();

$migration_files = scandir(__DIR__);

foreach($migration_files as $file) {
    if (!str_ends_with($file, ".sql"))
        continue;

    $query = file_get_contents(__DIR__ . "/" . $file);
    echo "running " . strtoupper($file);

    $db->query($query);
    echo "\t--\tdone\n";
}

$db->close();