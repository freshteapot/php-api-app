<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\ServerRoute;
use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 * @api.example GET /list/123/edit
 */
class ListEdit extends HttpApi
{
    /**
     * To demonstrate the decoupling of the code, we do a second Request
     * from which we return the data.
     * 
     * @api.route /list/:id/edit
     * @return Freshteapot\Http\Response;
     */
    public function get ()
    {
        $a = new ServerRoute( "get", "/list/123/edit/ftv1", $this->headers, $this->router );

        $response = (string)$a;
        $response = json_decode( $a, true );
        return new Response("200", $response['message'] );
    }
}
