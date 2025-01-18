<?php declare(strict_types=1);

use KpEsportes\App\Domain\Model\Admin;
use KpEsportes\App\Domain\Model\ValidationMailToken;
use KpEsportes\App\Http\Controller\AdminController;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;
use KpEsportes\App\Util\Hash;
use KpEsportes\App\Util\JWT;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class AdminControllerTest extends TestCase {

    public string $token = "";

    protected function setUp() : void {
        Env::load(".env.test");
        if (!extension_loaded("pgsql"))
            $this->markTestSkipped();
        date_default_timezone_set(Env::get("TIMEZONE"));
        
        $db = new SqlDatabase;
        $db->connect();

        $db->persist("DELETE FROM admins WHERE email = :email", [
            "email" => "alysson@gmail.com",
        ]);
        $db->persist("INSERT INTO admins(name, email, password) VALUES(:name, :email, :password)", [
            "name" => "alysson",
            "email" => "alysson@gmail.com",
            "password" => Hash::make("mypassword"),
        ]);

        $db->close();
    }

    public function testCanSendEmailVerificationMessage() {
        $_REQUEST = [
            "email" => "alysson@gmail.com",
            "name" => "alysson",
            "password" => "mypassword",
        ];

        $adminController = new AdminController;
        $adminController->sendValidationMail();
        
        $db = new SqlDatabase;
        $db->connect();

        $result = $db->fetchFirst("SELECT * FROM validation_mail_tokens WHERE email = :email", ValidationMailToken::class, [
            "email" => "alysson@gmail.com",
        ]);

        $db->close();

        $this->assertNotFalse($result);
    }

    #[Depends("testCanSendEmailVerificationMessage")]
    public function testCanVerifyToken() {
        $db = new SqlDatabase;
        $db->connect();
        
        $tokenForRequest = $db->fetchFirst("SELECT * FROM validation_mail_tokens WHERE email = :email", ValidationMailToken::class, [
            "email" => "alysson@gmail.com",
        ])->token;

        $_REQUEST = [
            "token" => $tokenForRequest, 
            "email" => "alysson@gmail.com", 
        ];

        $adminController = new AdminController;
        $response = $adminController->verifyEmail();

        $token = $db->fetchFirst("SELECT * FROM validation_mail_tokens WHERE email = :email", ValidationMailToken::class, [
            "email" => "alysson@gmail.com"
        ]);

        $jwt = new JWT;
        $admin = $jwt->decodeToken($response["token"]);

        $db->close();
    
        $this->assertEquals("alysson@gmail.com", $admin["email"]);
        $this->assertNull($token);
    }

    #[Depends("testCanVerifyToken")]
    public function testCanDeleteAdmin() {
        $this->expectNotToPerformAssertions();
        
        $db = new SqlDatabase;
        $db->connect();

        $db->persist("DELETE FROM admins WHERE email = :email", [
            "email" => "alysson@gmail.com",
        ]);

        $db->close();
    }

}