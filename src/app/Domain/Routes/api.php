<?php

use KpEsportes\App\Domain\Router;
use KpEsportes\App\Http\Controller\ClientController;
use KpEsportes\App\Http\Middleware\GuestMiddleware;

Router::add("POST", "/auth/signup/client", [ClientController::class, "signup"], [GuestMiddleware::class, "handle"]);