<?php
define ("APP_DIRECTORY", __DIR__ . DIRECTORY_SEPARATOR . 'src' );
include APP_DIRECTORY . "/Freshteapot/bootstrap.php";


$method = "put";
$uri = "/list/123/changeorder";

$config = array(
    "pathToRoutes" => APP_DIRECTORY . "/routes.config"
);

$router = new \Freshteapot\Api\Router();
$router->addRoutes($router->loadConfig($config['pathToRoutes']));
$headers = array("Cache-control none;");


$extra = array(
    "internal" => true,
    "command-line" => true
);

$a = new \Freshteapot\Api\ServerRoute( $method, $uri, $headers, $router );
echo $a;