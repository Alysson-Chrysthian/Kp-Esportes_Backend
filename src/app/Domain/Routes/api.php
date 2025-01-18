<?php

use KpEsportes\App\Domain\Router;
use KpEsportes\App\Http\Controller\AdminController;
use KpEsportes\App\Http\Controller\CategoryController;
use KpEsportes\App\Http\Controller\ProductController;
use KpEsportes\App\Http\Middleware\Auth;
use KpEsportes\App\Http\Middleware\Guest;

Router::add("POST", "/auth/admin/sendVerificationMail", [AdminController::class, "sendValidationMail"], [Guest::class, "handle"]);
Router::add("GET", "/auth/admin/verifyEmail", [AdminController::class, "verifyEmail"], [Guest::class, "handle"]);

Router::add("POST", "/category/add", [CategoryController::class, "addCategory"], [Auth::class, "handle"]);
Router::add("DELETE", "/category/delete/{id}", [CategoryController::class, "deleteCategory"], [Auth::class, "handle"]);
Router::add("GET", "/category/all", [CategoryController::class, "showAllCategories"], [Auth::class, "handle"]);
Router::add("PUT", "/category/update/{id}", [CategoryController::class, "updateCategory"], [Auth::class, "handle"]);

Router::add("POST", "/product/add", [ProductController::class, "addProduct"], [Auth::class, "handle"]);