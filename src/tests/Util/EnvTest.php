<?php declare(strict_types=1);

use KpEsportes\App\Util\Env;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase {

    protected function setUp() : void {
        Env::load(".env.test");
    }
    
    public function testCanRetrieveAppEnvVar() {
        $this->assertEquals("test", Env::get("APP_ENV"));
    }

}
