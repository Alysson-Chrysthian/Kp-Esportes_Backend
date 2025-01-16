<?php

namespace KpEsportes\App\Domain\Model;

class ValidationMailToken extends Model {

    public int $validation_mail_token_id;
    public string $token;
    public string $email;
    public string $user_info;
    public string $expires_at;

}