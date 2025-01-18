<?php

namespace KpEsportes\App\Http\Middleware;

use Exception;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Util\JWT;

class Auth extends Middleware {
    
    public Request $request;

    public function __construct() {
        $this->request = new Request;
    }

    public function handle() {
        $token = $this->request->getHeader("Authorization");
        $jwt = new JWT;

        if ($token != null && $jwt->decodeToken($token))
            return;
            
        throw new Exception("Você precisa esta autenticado para acessar esta rota", 403);
    }

}