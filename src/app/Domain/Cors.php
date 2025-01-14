<?php

namespace KpEsportes\App\Domain;

class Cors {

    protected string $allowedMethods;
    protected string $allowedHeaders;
    protected string $allowedOrigin;

    public function configure() {
        header("Access-Control-Allow-Methods: " . $this->allowedMethods);
        header("Access-Control-Allow-Headers: " . $this->allowedHeaders);
        header("Access-Control-Allow-Origin: " . $this->allowedOrigin);
        header("Access-Control-Allow-Credentials: true");

        if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
            http_response_code(204);
            exit();
        }
    }

    public function setAllowedMethods(string $allowedMethods) {
        $this->allowedMethods = $allowedMethods;
    }
    
    public function setAllowedHeaders(string $allowedHeaders) {
        $this->allowedHeaders = $allowedHeaders;
    }

    public function setAllowedOrigin(string $allowedOrigin) {
        $this->allowedOrigin = $allowedOrigin;
    }

}