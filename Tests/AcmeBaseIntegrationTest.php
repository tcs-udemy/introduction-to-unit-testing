<?php
namespace Acme\Tests;

use Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use PDO;

/**
 * Class AcmeBaseIntegrationTest
 * @package Acme\Tests
 */
abstract class AcmeBaseIntegrationTest extends \PHPUnit_Extensions_Database_TestCase {

    public $bootstrapResources;
    public $dbAdapter;
    public $bootstrap;
    public $conn;
    public $session;

    public function setUp()
    {
        require __DIR__ . '/../vendor/autoload.php';
        require __DIR__ . '/../bootstrap/functions.php';
        Dotenv::load(__DIR__ . '/../');

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'acme_test',
            'username'  => 'vagrant',
            'password'  => 'secret',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }


    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . "/acme_db.xml");
    }


    public function getConnection()
    {
        $db = new PDO(
            "mysql:host=localhost;dbname=acme_test",
            "vagrant", "secret");

        return $this->createDefaultDBConnection($db, "acme_test");
    }
}
