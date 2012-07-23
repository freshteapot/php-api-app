<?php 
namespace Freshteapot\Tests\Api;

class Fixtures
{
    function getRouterConfig ()
    {
        $json = <<<JSON
{
    "Freshteapot\\Api\\Controllers\\Error": [
        {
            "controller": {
                "className": "\\Freshteapot\\Api\\Controllers\\Error", 
                "method": "get", 
                "path": "Freshteapot/Api/Controllers/Error.php"
            }, 
            "http": {
                "method": "get", 
                "uri": "/error"
            }
        }
    ]
}
JSON;
        $json = str_replace('\\','\\\\',$json); 
        return json_decode($json, true);
    }

    function getRouteExample ()
    {
        return array (
          'get' => 
          array (
            '/error' => 
            array (
              'controller' => 
              array (
                'className' => '\\Freshteapot\\Api\\Controllers\\Error',
                'method' => 'get',
                'path' => 'Freshteapot/Api/Controllers/Error.php',
              ),
              'http' => 
              array (
                'method' => 'get',
                'uri' => '/error',
              ),
            ),
          ),
        );
    }
}