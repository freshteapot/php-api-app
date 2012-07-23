<?php
namespace Freshteapot\Http;

use Freshteapot\Library\OneTime;

class Request extends OneTime
{
    public function __construct( Array $config=array() )
    {
        if (function_exists('apache_request_headers')) {
            $config = array_merge(apache_request_headers(), $config);
        }
        parent::__construct($config);
    }
}