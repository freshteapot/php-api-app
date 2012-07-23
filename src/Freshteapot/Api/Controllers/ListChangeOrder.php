<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\ServerRoute;
use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 */
class ListChangeOrder extends HttpApi
{
    /**
     * To demonstrate the decoupling of the code, we do a second Request
     * from which we return the data.
     * 
     * @api.route /list/:id/changeorder
     * @api.example PUT /list/123/changeorder
     * @return Freshteapot\Http\Response;
     */
    public function put ()
    {
        return new Response("200", "Read from post body." );
    }
}
