<?php
namespace Freshteapot\Library;

$_SERVER['REQUEST_DATETIME'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
$_SERVER["ENV_URI_BASEURL"] = 'http://learnalist.net/';
$_SERVER["ENV_URI_BASEFOLDER"] = '';

class UtilityBelt{}