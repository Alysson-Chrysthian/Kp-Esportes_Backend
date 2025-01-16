<?php

namespace KpEsportes\App\Util;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail {

    protected string $email;

    public function __construct(string $email) {
        $this->email = $email;
    }

    public function send(string $message, string $subject, string $alt_body) {
        $mailer = new PHPMailer(true);
        $this->configure($mailer);

        $mailer->setFrom(Env::get("SMTP_USER"));
        $mailer->addAddress($this->email);
        
        $mailer->Subject = $subject;
        $mailer->Body = $message;
        $mailer->AltBody = $alt_body;
        
        $mailer->send();
    }

    protected function configure(PHPMailer $mailer) {
        $mailer->isSMTP();
        $mailer->Host = Env::get("SMTP_HOST");
        $mailer->Username = Env::get("SMTP_USER");
        $mailer->Password = Env::get("SMTP_PASSWORD");
        $mailer->Port = Env::get("SMTP_PORT");
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->SMTPAuth = true;
        $mailer->CharSet = PHPMailer::CHARSET_UTF8;
        $mailer->isHTML();
    }

}