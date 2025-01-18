<?php

namespace KpEsportes\App\Domain\Service;

use KpEsportes\App\Domain\Model\Admin;
use KpEsportes\App\Storage\SqlDatabase;

class AdminService extends Service {

    protected string $table = "admins";

    public function __construct() {
        $this->db = new SqlDatabase;
    }

    public function findByEmail(string $email) {
        $this->db->connect();
        $admin = $this->db->fetchFirst("SELECT * FROM " . $this->table . " WHERE email = :email", Admin::class, [
            "email" => $email,
        ]);
        $this->db->close();

        return $admin;
    }

    public function existWhere(string $where_clause, array $binds) {
        $this->db->connect();
        $admin = $this->db->fetchFirst("SELECT * FROM admins WHERE $where_clause", Admin::class, $binds);
        $this->db->close();

        return $admin == null ? false : true;
    }

}