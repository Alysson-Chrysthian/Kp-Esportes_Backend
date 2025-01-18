<?php

namespace KpEsportes\App\Domain\Service;

use KpEsportes\App\Storage\SqlDatabase;

abstract class Service {
    protected string $table;
    public SqlDatabase $db;
}