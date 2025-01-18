<?php

namespace KpEsportes\App\Domain\Model;

use KpEsportes\App\Storage\SqlDatabase;

abstract class Model {

    public string|null $created_at;
    public string|null $updated_at;

    protected function belongTo(string $class, string $table, string $local_key, string $foreign_key) {
        $db = new SqlDatabase;
        $db->connect();

        $obj = $db->fetchFirst("SELECT * FROM $table WHERE $foreign_key = :local_key", $class, [
            "local_key" => $this->{$local_key},
        ]);

        $db->close();

        return $obj;
    }

}