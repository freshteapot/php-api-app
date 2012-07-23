<?php
namespace Freshteapot\Tests;

use Freshteapot\Tests\Api\Fixtures;

class Router extends \PHPUnit_Framework_TestCase
{
    private $_fixtures;

    function __construct(){}

    public function setUp ()
    {
        $this->_fixtures = new Fixtures();
    }

    public function testLoadConfig()
    {
        $router = new \Freshteapot\Api\Router();
        try {
            $router->loadConfig(null);
        } catch(\Exception $e) {
            $this->assertEquals("File not found.", $e->getMessage());
        }

        $a = $this->_fixtures->getRouterConfig();
        $b = $router->loadConfig(__DIR__ . "/assets/routes.config");
        $this->assertEquals(json_encode($a), json_encode($b));
    }

    function testGetRoute()
    {
        $router = new \Freshteapot\Api\Router();
        $config = $router->loadConfig(__DIR__ . "/assets/routes.config");
        $router->addRoutes($config);

        $prop = new \ReflectionProperty($router, 'routes');
        $prop->setAccessible(true);

        $a = $this->_fixtures->getRouteExample();
        $b = $prop->getValue($router);
        $this->assertEquals($a, $b);
    }
}
