<?php

use KpEsportes\App\Domain\Router;
use KpEsportes\App\Http\Controller\AdminController;
use KpEsportes\App\Http\Middleware\Guest;

Router::add("POST", "/auth/admin/sendVerificationMail", [AdminController::class, "sendValidationMail"], [Guest::class, "handle"]);
Router::add("GET", "/auth/admin/verifyEmail", [AdminController::class, "verifyEmail"], [Guest::class, "handle"]);
