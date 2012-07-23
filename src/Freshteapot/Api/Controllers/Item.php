<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 * @api.example GET /item
 * @api.example POST /item
 * @api.example PATCH /item/2
 */
class Item extends HttpApi
{
    /**
     * @api.route /item/:id
     */
    public function get ( )
    {
        /*
array("SetupSite", "AclAccess", );
new Plugin\SetupSite();
new Plugin\CheckAccess();
         */
        return new Response("200", "get item" );
    }

    /**
     * @api.route /item
     */
    public function post ()
    {
        //$input = file_get_contents('php://input');
        $input = $_POST;
        return new Response( "200", var_export($input, true) );
    }

    /**
    * @api.route /item
    */
    public function put ()
    {
        $input = file_get_contents('php://input');
        return new Response("200", $input );
    }

    /**
    * @api.route /item/:id
    */
    public function patch ( )
    {
        $input = file_get_contents('php://input');
        return new Response("200", $input );
    }
}
