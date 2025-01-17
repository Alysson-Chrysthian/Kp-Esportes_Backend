<?php

namespace KpEsportes\App\Util;

use Dotenv\Dotenv;

class Env {

    public static function load(string $env_filename = ".env") {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../", $env_filename);
        $dotenv->load();
    }

    public static function get(string $name) {
        return isset($_ENV[$name]) ? $_ENV[$name] : null;
    }

}