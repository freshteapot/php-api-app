<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Library\Api\RoutesBuilder;

use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 * @api.example POST /routes
 */
class Routes extends HttpApi
{
    /**
     * @api.internal
     * @api.route /routes
     */
    public function post ()
    {
        $controllersFolder = APP_DIRECTORY . "/" . str_replace( "\\", "/", __NAMESPACE__ ) . "/";
        $builder = new RoutesBuilder( );
        $builder->setPathToAppDirectory( APP_DIRECTORY );
        $builder->setPathToControllers( $controllersFolder );
        $routes = $builder->make( APP_DIRECTORY . "/routes.config" );

        return new Response("200", $routes );
    }
}
