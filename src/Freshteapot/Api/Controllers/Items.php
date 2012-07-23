<?php

namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 * @api.example GET /items
 */
class Items extends HttpApi
{
    /**
     * @api.route /items
     */
    public function get ()
    {
        return new Response("200", array( "10","20" ) );
    }
}
