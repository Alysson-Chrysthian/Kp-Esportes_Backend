<?php

namespace KpEsportes\App\Http\Controller;

use Exception;
use KpEsportes\App\Domain\Model\Admin;
use KpEsportes\App\Domain\Model\ValidationMailToken;
use KpEsportes\App\Domain\Service\AdminService;
use KpEsportes\App\Domain\Service\ValidationMailTokenService;
use KpEsportes\App\Http\Mail\ValidationMail;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Util\Hash;
use KpEsportes\App\Util\Mail;
use KpEsportes\App\Util\Validator;

class AdminController extends Controller {

    public Request $request;
    public ValidationMailTokenService $validationMailTokenService;
    public AdminService $adminService;

    public function __construct() {
        $this->request = new Request;
        $this->validationMailTokenService = new ValidationMailTokenService;
        $this->adminService = new AdminService;
    }

    public function sendValidationMail() {
        Validator::validate([
            "email" => ["required", "email", "unique:admins,email"],
            "name" => ["required", "min:3", "max:20"],
            "password" => ["required", "min:8", "max:16"],
        ]);

        $validationMailToken = new ValidationMailToken;

        $validationMailToken->token = Hash::make($this->request->getInput("email"));
        $validationMailToken->email = $this->request->getInput("email");
        $validationMailToken->user_info = json_encode([
            "email" => $this->request->getInput("email"),
            "name" => $this->request->getInput("name"),
            "password" => Hash::make($this->request->getInput("password")),
        ]);

        $this->validationMailTokenService->save($validationMailToken);

        $mail = new Mail($this->request->getInput("email"));
        $mail->send(
                ValidationMail::view($validationMailToken), 
                "Verificação de email do admin " . $this->request->getInput("name"), 
                "Caso não consiga fazer a verificaçao do email contate o suporte tecnico em (88) 997140695",
            );

        return [
            "message" => "email enviado com sucesso",
        ];
    }

    public function verifyEmail() {
        Validator::validate([
            "token" => ["required", "exist:validation_mail_tokens,token"],
            "email" => ["required", "email", "exist:validation_mail_tokens,email"],
        ]);

        $validationMailToken = $this->validationMailTokenService->whereFirst("token = :token AND email = :email ORDER BY created_at DESC", [
            "token" => $this->request->getInput("token"),
            "email" => $this->request->getInput("email"),
        ]);

        if (strtotime($validationMailToken->expires_at < time())) 
            throw new Exception("O pedido para validação de email ja expirou, por favor tente novamente", 401);

        $admin_info = json_decode($validationMailToken->user_info, true);
        
        $admin = new Admin;

        $admin->name = $admin_info["name"];
        $admin->email = $admin_info["email"];
        $admin->password = $admin_info["password"];

        $this->adminService->save($admin);
        $this->validationMailTokenService->deleteByEmail($this->request->getInput("email"));
        
        return [
            "message" => "O admin foi cadastrado com sucesso por favor volte para o app e faça o login",
        ];
    }

}