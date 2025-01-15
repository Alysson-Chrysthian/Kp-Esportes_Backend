<?php

namespace KpEsportes\App\Http\Mail;

use KpEsportes\App\Domain\Model\ValidationMailToken;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Util\Env;

class ValidationMail extends MailTemplate {

    protected static Request $request;

    public static function view($args) {
        self::setUp();
        return self::getBody($args);
    }

    protected static function setUp() {
        self::$request = new Request;
    }

    protected static function getBody($validation_mail_token_model) {
        $user_name = self::$request->getInput("name");
        $url = Env::get("APP_URL") . "/api/auth/admin/verifyEmail" . "?token=" . $validation_mail_token_model->token . "&email=" . $validation_mail_token_model->email;

        return "
           <div style='font-family: Arial, sans-serif; line-height: 1.5; text-align: center;'>
                <h1 style='color: #333;'>Olá $user_name</h1>
                <p style='color: #555;'>Clique no link abaixo para ser redirecionado para a página de verificação de e-mail:</p>
                <a href=\"$url\" style='
                    display: inline-block;
                    background-color: #3b565e;
                    color: #ffffff;
                    padding: 16px;
                    border-radius: 8px;
                    text-decoration: none;
                    transition: all 0.5s;
                ' onmouseover='this.style.backgroundColor=\"#fb565e\"'>
                    Verificar e-mail
                </a>
            </div>
        ";
    }

}