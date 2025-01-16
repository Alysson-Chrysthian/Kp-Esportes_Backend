<?php

namespace KpEsportes\App\Util;

use Firebase\JWT\JWT as JWTToken;
use Firebase\JWT\Key;

class JWT {

    protected string $alg;
    protected string $key;

    public function __construct() {
        $this->alg = Env::get("AUTH_ALG");
        $this->key = Env::get("AUTH_KEY");
    }

    public function createToken(array $payload) {
        return JWTToken::encode($payload, $this->key, $this->alg);
    }

    public function decodeToken(string $token) {
        return (array) JWTToken::decode($token, new Key($this->key, $this->alg));
    }

}