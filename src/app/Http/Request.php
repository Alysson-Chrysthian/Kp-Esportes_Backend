<?php

namespace KpEsportes\App\Http;

class Request {

    protected array|null $body;
    protected array $headers;

    public function __construct() {
        $this->headers = $this->getRequestHeaders();
        $this->body = $this->getInputStream();

        if ($this->body == null)
            $this->body = $_REQUEST;

        $this->body = array_merge($this->body, $_FILES);
    }

    public function getInput(string $name) {
        return isset($this->body[$name]) ? $this->body[$name] : null;
    }

    public function getHeader(string $name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    public function getInputStream() {
        return json_decode(file_get_contents("php://input"), true);
    }

    public function getRequestHeaders() {
        if (function_exists("getallheaders"))
            return getallheaders();
        else
            return [];
    }

    public function getBody() {
        return $this->body;
    }

    public function getHeaders() {
        return $this->headers;
    }

}