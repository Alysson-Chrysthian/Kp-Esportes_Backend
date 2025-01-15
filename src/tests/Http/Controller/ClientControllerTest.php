<?php declare(strict_types=1);

use KpEsportes\App\Domain\Model\Client;
use KpEsportes\App\Domain\Service\ClientService;
use KpEsportes\App\Http\Controller\ClientController;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class ClientControllerTest extends TestCase {

    protected function setUp() : void {
        if (!extension_loaded("pgsql"))
            $this->markTestSkipped();

        error_reporting(E_ALL);
        Env::load(".env.test");
    }

    public function testSignupIsWorking() {
        $_REQUEST = [
            "name" => "myname",
            "email" => "myemail@mail.com",
            "password" => "mypassword"
        ];

        $db = new SqlDatabase;
        
        $clientController = new ClientController;    
        $clientController->signup();

        $db->connect();
        
        $result = $db->fetchFirst("SELECT * FROM clients WHERE email = :email", Client::class,[
            "email" => "myemail@mail.com",
        ]);

        $db->close();
    
        $this->assertNotNull($result);
        $this->assertEquals("myemail@mail.com", $result->email);    
    }

    #[Depends("testSignupIsWorking")]
    public function testUniqueRuleIsWorking() {
        $this->expectException(Exception::class);

        $_REQUEST = [
            "name" => "myname",
            "email" => "myemail@mail.com",
            "password" => "mypassword"
        ];

        $clientController = new ClientController;
        $clientController->signup();   
    }

    #[Depends("testSignupIsWorking")]
    public function testCanDelete() {
        $clientService = new ClientService;
        $rows_affected = $clientService->deleteByEmail("myemail@mail.com");
        
        $this->assertEquals(1, $rows_affected);
    }

}