<?php

namespace KpEsportes\App\Http\Middleware;

use Exception;
use KpEsportes\App\Http\Request;

class Guest extends Middleware {
    
    public Request $request;

    public function __construct() {
        $this->request = new Request;
    }

    public function handle() {
        if ($this->request->getHeader("Authorization") != null)
            throw new Exception("Você nao pode acessar esta rota estando autenticado", 403);
    }

}