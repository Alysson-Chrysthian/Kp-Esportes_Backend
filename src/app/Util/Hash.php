<?php

namespace KpEsportes\App\Util;

class Hash {

    public static function make(string $password, string $alg = "sha256") {
        return hash($alg, $password);
    }

}