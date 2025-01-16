<?php declare(strict_types=1);

use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class SqlDatabaseTest extends TestCase {

    protected function setUp() : void {
        Env::load(".env.test");
        if (!extension_loaded(Env::get("DB_DRIVER")))
            $this->markTestSkipped();
    }

    public function testCanConnectToDatabase() {
        $this->expectNotToPerformAssertions();
        
        $db = new SqlDatabase;
        $db->connect();
        $db->close();
    }

    public function testCanCreateTable() {
        $this->expectNotToPerformAssertions();

        $db = new SqlDatabase;
        $db->connect();

        $db->query("CREATE TABLE IF NOT EXISTS table_test ( id SERIAL PRIMARY KEY, message TEXT )");

        $db->close();
    }

    #[Depends("testCanCreateTable")]
    public function testCanPersistData() {
        $db = new SqlDatabase;
        $db->connect();

        $rows = $db->persist("INSERT INTO table_test(message) VALUES(:message)", [
            "message" => "test message"
        ]);

        $db->close();

        $this->assertEquals(1, $rows);
    }

    #[Depends("testCanPersistData")]
    public function testCanFetch() {
        $db = new SqlDatabase;
        $db->connect();

        $result = $db->fetch("SELECT * FROM table_test WHERE id = :id", null, [
            "id" => 1,
        ]);

        $db->close();

        $this->assertTrue(is_array($result));
        if (count($result) > 0)
            $this->assertEquals(stdClass::class, get_class($result[0]));
    }

    #[Depends("testCanCreateTable")]
    public function testCanDropTable() {
        $this->expectNotToPerformAssertions();
        
        $db = new SqlDatabase;
        $db->connect();

        $db->query("DROP TABLE table_test");

        $db->close();
    }
    
}