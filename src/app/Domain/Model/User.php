<?php

namespace KpEsportes\App\Domain\Model;

abstract class User extends Model {

    public string $name;
    public string $email;
    public string $password;

}