<?php

namespace KpEsportes\App\Domain;

use Closure;
use Exception;
use InvalidArgumentException;

class Router {
    
    protected static array $endpoints = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "PATCH" => [],
        "DELETE" => [],
    ];

    public static function add(string $method, string $endpoint, array|Closure $controller, array|Closure ...$middleware) {
        $method = strtoupper($method);
        if (!isset(self::$endpoints[$method]))
            throw new InvalidArgumentException("O metodo utilizado não existe", 400);
    
        $pattern = preg_replace("#\{[A-z]{1,}\}#", "(.*)", $endpoint);
        
        self::$endpoints[$method]["/api" . $pattern] = [
            "controller" => $controller,
            "middleware" => $middleware,
        ];
    }

    public static function checkRoute() {
        $uri = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        
        foreach (self::$endpoints[$method] as $pattern => $callbacks) {
            if (!preg_match("#" . $pattern . "#", $uri, $args))
                continue;
            array_shift($args);

            self::runMiddlewares($callbacks["middleware"], $args);
            return self::runController($callbacks["controller"], $args);
        }

        throw new Exception("Essa rota nao existe ou não é compativel com esse metodo", 405);
    }

    public static function getEndpoints() {
        return self::$endpoints;
    }

    private static function runMiddlewares(array $middlewares, array $args) {
        foreach ($middlewares as $middleware) {
            $callable = $middleware;
            if (is_array($middleware))
                $callable = [
                    new $middleware[0],
                    $middleware[1],
                ];

            call_user_func_array($callable, $args);
        }
    }

    private static function runController(array|Closure $controller, array $args) {
        $callable = $controller;
        if (is_array($controller))
            $callable = [
                new $controller[0],
                $controller[1],
            ];
        
        return call_user_func_array($callable, $args);
    }
    
}