<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\Server;
use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 * @api.example GET /alist/123
 */
class Alist extends HttpApi
{
    /**
     * To demonstrate the decoupling of the code, we do a second Request
     * from which we return the data.
     * 
     * @return Freshteapot\Http\Response;
     */
    public function get ()
    {
        $a = new Server( "get", "/items", array() );
        $response = (string)$a;
        $response = json_decode( $a, true );
        return new Response("200", $response['message'] );
    }
}
