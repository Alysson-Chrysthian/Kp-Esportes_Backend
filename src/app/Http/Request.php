<?php

namespace KpEsportes\App\Http;

class Request {

    protected array|null $body;
    protected array $headers;

    public function __construct() {
        $this->headers = getallheaders();
        $this->body = $this->getInputStream();

        if ($this->body == null)
            $this->body = $_REQUEST;

        $this->body = array_merge($this->body, $_FILES);
    }

    public function getInput(string $name) {
        return $this->body[$name];
    }

    public function getHeader(string $name) {
        return $this->headers[$name];
    }

    public function getInputStream() {
        return json_decode(file_get_contents("php://input"), true);
    }

    public function getBody() {
        return $this->body;
    }

    public function getHeaders() {
        return $this->headers;
    }

}