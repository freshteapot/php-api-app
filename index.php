<?php
define ("APP_DIRECTORY", __DIR__ . DIRECTORY_SEPARATOR . 'src' );
include APP_DIRECTORY . "/Freshteapot/bootstrap.php";

$a = new \Freshteapot\Api\Server( "get", "/document", array() );
echo $a;