<?php

namespace KpEsportes\App\Domain\Service;

use KpEsportes\App\Domain\Model\ValidationMailToken;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Date;

class ValidationMailTokenService extends Service {

    public SqlDatabase $db;
    protected string $table = "validation_mail_tokens";

    public function __construct() {
        $this->db = new SqlDatabase;
    }

    public function save(ValidationMailToken $validationMailToken) {
        $this->db->connect();

        $this->db->persist("
            INSERT INTO " . $this->table . "(token, email, user_info, created_at, updated_at, expires_at)
            VALUES(:token, :email, :user_info, :created_at, :updated_at, :expires_at)
        ", [
            "token" => $validationMailToken->token,
            "email" => $validationMailToken->email,
            "user_info" => $validationMailToken->user_info,
            "created_at" => Date::now()->format(),
            "updated_at" => Date::now()->format(),
            "expires_at" => Date::now()->addHours(2)->format(),
        ]);

        $this->db->close();
    }

    public function whereFirst($whereClause, array|null $binds = null) {
        $this->db->connect();

        $result = $this->db->fetchFirst("SELECT * FROM " . $this->table . " WHERE $whereClause", ValidationMailToken::class, $binds);

        $this->db->close();

        return $result;
    }

    public function deleteByEmail(string $email) {
        $this->db->connect();

        $this->db->persist("DELETE FROM " . $this->table . " WHERE email = :email", [
            "email" => $email,          
        ]);

        $this->db->close();
    }

}