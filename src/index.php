<?php

use KpEsportes\App\Domain\Cors;
use KpEsportes\App\Domain\Router;
use KpEsportes\App\Util\Env;

require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . "/app/Domain/Routes/api.php");

if (Env::get("APP_ENV") == null)
    Env::load();

$cors = new Cors;

$cors->setAllowedMethods("*");
$cors->setAllowedHeaders("*");
$cors->setAllowedOrigin(Env::get("FRONTEND_URL"));

$cors->configure();

date_default_timezone_set(Env::get("TIMEZONE"));

header("Content-Type: application/json");

try {
    http_response_code(200);
    echo json_encode(Router::checkRoute());
} catch (Exception $error) {
    $code = 400;
    if (is_numeric($error->getCode()))
        $code = $error->getCode();

    http_response_code($code);
    
    $message = $error->getMessage();
    if (json_decode($message) != null)
        $message = json_decode($message);

    echo json_encode([
        "error" => $message,
    ]);
}