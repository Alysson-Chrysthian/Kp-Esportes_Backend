<?php

namespace KpEsportes\App\Domain\Model;

class Admin extends Model {

    public int $admin_id;
    public string $name;
    public string $email;
    public string $password;

}