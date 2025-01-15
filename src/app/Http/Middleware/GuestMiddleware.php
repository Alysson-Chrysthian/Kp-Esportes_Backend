<?php

namespace KpEsportes\App\Http\Middleware;

use Exception;
use KpEsportes\App\Http\Request;

class GuestMiddleware extends Middleware {

    public Request $request;

    public function __construct() {
        $this->request = new Request;
    }

    public function handle() {
        $authorizationToken = $this->request->getHeader("Authorization");
        if ($authorizationToken == null)
            return;
        
        throw new Exception("Você não pode acessar esta rota estando autenticado", 400);
    }

}