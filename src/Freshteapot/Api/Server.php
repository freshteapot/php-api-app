<?php
namespace Freshteapot\Api;

use Freshteapot\Http\Response;
//@TODO - Can this be removed?
class Server
{
    private
    $router,
    $allowedMethods = array( 'get', 'post', 'put', 'delete', 'patch' );

    public
    $response;

    /**
     * @todo How to pass the headers, so that they can be used.
     * 
     * @param String $method
     * @param String $uri
     * @param Array $headers
     */
    function __construct ( $method, $uri, $headers )
    {
        try {
            $this->run( $method, $uri );
        } catch( \InvalidArgumentException $e ) {
            //@todo Better handling required.
            echo "Method not allowed";
        }
    }

    /**
     * @param String $method
     * @param String $uri
     */
    private function run ( $method, $uri )
    {
        $this->checkMethod( $method );

        
//        $routes = new Router();

        $path = explode( "/", $uri );
        //@todo Is hard coded really the way here?
        $className = __NAMESPACE__ . "\Controllers\\" . ucfirst( strtolower( $path["1"] ) );
        try {
            $api = new $className();
        } catch ( \Exception $e ) {
            $a = new Server("get", "/error/1", array() );
            $this->response = $a->response;
            return;
        }

        try {
            echo "here";
            exit;
            //@todo Figure out how to pre break the uri parts ready for the controller.
            $this->response = $api->$method($uri);
        } catch ( \Exception $e ) {
            $a = new Server("get", "/error/1", array() );
            $this->response = $a->response;
            return;
        }
    }

    /**
     * A helper method to make sure we are only allowing methods we throw Exceptions for.
     * 
     * @param String $method
     * @throws \InvalidArgumentException
     */
    private function checkMethod ( $method )
    {
        if ( in_array( $method, $this->allowedMethods ) ) {
            return true;
        }
        throw new \InvalidArgumentException( "Bad Method" );
    }

    /**
     * Return a JSON string of the response from the Request.
     */
    public function __toString ()
    {
        if( $this->response instanceof Response  )
        {
            return $this->response->toJSON();
        }
    }
}
