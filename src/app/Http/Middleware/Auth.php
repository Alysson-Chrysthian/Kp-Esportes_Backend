<?php

namespace KpEsportes\App\Http\Middleware;

use Exception;
use KpEsportes\App\Http\Request;

class Auth extends Middleware {
    
    public Request $request;

    public function __construct() {
        $this->request = new Request;
    }

    public function handle() {
        if ($this->request->getHeader("Authorization") == null)
            throw new Exception("Você precisa esta autenticado para acessar esta rota", 403);
    }

}