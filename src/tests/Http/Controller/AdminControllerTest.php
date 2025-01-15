<?php declare(strict_types=1);

use KpEsportes\App\Domain\Model\Admin;
use KpEsportes\App\Domain\Model\ValidationMailToken;
use KpEsportes\App\Http\Controller\AdminController;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class AdminControllerTest extends TestCase {

    public string $token = "";

    protected function setUp() : void {
        if (!extension_loaded("pgsql"))
            $this->markTestSkipped();
        Env::load(".env.test");
        date_default_timezone_set(Env::get("TIMEZONE"));
    }

    public function testCanSendEmailVerificationMessage() {
        $_REQUEST = [
            "email" => "alyssonchrysthian@gmail.com",
            "name" => "alysson",
            "password" => "mypassword",
        ];

        $adminController = new AdminController;
        $adminController->sendValidationMail();
        
        $db = new SqlDatabase;
        $db->connect();

        $result = $db->fetchFirst("SELECT * FROM validation_mail_tokens WHERE email = :email", ValidationMailToken::class, [
            "email" => "alyssonchrysthian@gmail.com",
        ]);

        $db->close();

        $this->assertNotFalse($result);
    }

    #[Depends("testCanSendEmailVerificationMessage")]
    public function testCanVerifyToken() {
        $db = new SqlDatabase;
        $db->connect();
        
        $tokenForRequest = $db->fetchFirst("SELECT * FROM validation_mail_tokens WHERE email = :email", ValidationMailToken::class, [
            "email" => "alyssonchrysthian@gmail.com",
        ])->token;

        $_REQUEST = [
            "token" => $tokenForRequest, 
            "email" => "alyssonchrysthian@gmail.com", 
        ];

        $adminController = new AdminController;
        $adminController->verifyEmail();

        $admin = $db->fetchFirst("SELECT * FROM admins WHERE email = :email", Admin::class, [
            "email" => "alyssonchrysthian@gmail.com",
        ]);
        $token = $db->fetchFirst("SELECT * FROM validation_mail_tokens WHERE email = :email", ValidationMailToken::class, [
            "email" => "alyssonchrysthian@gmail.com"
        ]);

        $db->close();
    
        $this->assertIsObject($admin);   
        $this->assertNull($token);
    }

    #[Depends("testCanVerifyToken")]
    public function testCanDeleteAdmin() {
        $this->expectNotToPerformAssertions();
        
        $db = new SqlDatabase;
        $db->connect();

        $db->persist("DELETE FROM admins WHERE email = :email", [
            "email" => "alyssonchrysthian@gmail.com",
        ]);

        $db->close();
    }

}