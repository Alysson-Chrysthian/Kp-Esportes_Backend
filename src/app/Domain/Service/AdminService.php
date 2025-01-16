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

}