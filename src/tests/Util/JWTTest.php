<?php declare(strict_types=1);

use KpEsportes\App\Util\Env;
use KpEsportes\App\Util\JWT;
use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase {

    protected function setUp() : void {
        Env::load(".env.test");
    }

    public function testCanEncode() {
        $this->expectNotToPerformAssertions();

        $jwt = new JWT;
        $jwt->createToken([
            "email" => "mymail@mail.com",
        ]);
    }

    public function testCanDecode() {
        $payload = [
            "email" => "mymail@mail.com",
        ];
        
        $jwt = new JWT;
        $token = $jwt->createToken($payload);

        $payload_decode = $jwt->decodeToken($token);

        $this->assertEquals($payload, $payload_decode);
    }

}