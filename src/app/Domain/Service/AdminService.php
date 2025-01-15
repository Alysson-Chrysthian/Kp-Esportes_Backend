<?php

namespace KpEsportes\App\Domain\Service;

use KpEsportes\App\Domain\Model\Admin;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Date;

class AdminService extends Service {

    public SqlDatabase $db;
    protected string $table = "admins";

    public function __construct() {
        $this->db = new SqlDatabase;
    }

    public function save(Admin $admin) {
        $this->db->connect();

        $this->db->persist("
            INSERT INTO " . $this->table . "(name, email, password, created_at, updated_at)
            VALUES(:name, :email, :password, :created_at, :updated_at)
        ", [
            "name" => $admin->name,
            "email" => $admin->email,
            "password" => $admin->password,
            "created_at" => Date::now()->format(),
            "updated_at" => Date::now()->format(),
        ]);

        $this->db->close();
    }

}