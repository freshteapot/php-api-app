<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 * @api.example GET /error
 * @author freshteapot
 *
 * I have chosen to use via hard coding, this class
 * to act as the default api call if there is an issue
 * when trying to use the api.
 *
 * This means, when an api request fails.
 * We call another api request to return the response.
 */
class Error extends HttpApi
{
    /**
     * This will be the error message for a GET request
     * @api.route /error
     * @return Freshteapot\Http\Response;
     */
    public function get ()
    {
        return new Response( "400", "Bad Url");
    }
}
