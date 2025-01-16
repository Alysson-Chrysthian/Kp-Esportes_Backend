<?php declare(strict_types=1);

use KpEsportes\App\Util\Env;
use KpEsportes\App\Util\Mail;
use PHPUnit\Framework\TestCase;

class MailTest extends TestCase {

    protected function setUp() : void {
        Env::load(".env.test");
    }

    public function testCanSendEmail() {
        $this->expectNotToPerformAssertions();

        $mail = new Mail("alyssonchrysthian@gmail.com");
        $mail->send("message", "subject", "altbody");
    }

}