<?php

namespace KpEsportes\App\Domain\Service;

use KpEsportes\App\Domain\Model\Client;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Date;
use KpEsportes\App\Util\Hash;

class ClientService extends Service {

    protected SqlDatabase $db;

    public function __construct() {
        $this->db = new SqlDatabase;
    }

    public function save(Client $client) {
        $this->db->connect();

        $this->db->persist("
            INSERT INTO clients(name, email, password, created_at, updated_at) 
            VALUES(:name, :email, :password, :created_at, :updated_at)
        ", [
            "name" => strtolower($client->name),
            "email" => strtolower($client->email),
            "password" => Hash::make($client->password),
            "created_at" => Date::now(),
            "updated_at" => Date::now()
        ]);

        $this->db->close();
    }

    public function deleteByEmail(string $email) {
        $this->db->connect();

        $rows_affected = $this->db->persist("
            DELETE FROM clients
            WHERE email = :email
        ", [
            "email" => strtolower($email),
        ]);

        $this->db->close();
        
        return $rows_affected;
    }

}