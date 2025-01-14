<?php

use KpEsportes\App\Domain\Cors;
use KpEsportes\App\Domain\Router;
use KpEsportes\App\Util\Env;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/app/Domain/Routes/api.php";

$cors = new Cors;

$cors->setAllowedMethods("*");
$cors->setAllowedHeaders("*");
$cors->setAllowedOrigin("*");

$cors->configure();

Env::load();

header("Content-Type: application/json");

try {
    http_response_code(200);
    echo json_encode([
        Router::checkRoute(),
    ]);
} catch (Exception $error) {
    http_response_code($error->getCode());
    
    $message = $error->getMessage();
    if (json_decode($message) != null)
        $message = json_decode($message);

    echo json_encode([
        "error" => $message,
    ]);
}