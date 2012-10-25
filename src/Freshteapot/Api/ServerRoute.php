<?php
namespace Freshteapot\Api;

use Freshteapot\Http\Response;
use Freshteapot\Http\Request;
use Freshteapot\Api\Router;

class ServerRoute
{
    private
    $route,
    $headers,
    $allowedMethods = array( 'get', 'post', 'put', 'delete', 'patch' ),
    $response;

    /**
     * 
     * Enter description here ...
     * @param string $method
     * @param string $uri
     * @param Request $headers
     * @param Router $router
     * @param array $extra
     * @throws \InvalidArgumentException
     */
    function __construct ( $method, $uri, Request $request, Router $router, $extra = array() )
    {
        $this->router = $router;
        $this->request = $request;
        //@todo how to work with query?
        $this->uri = $uri;
        /*
         * If method=get should we copy varnish and do a look up.
         * 	We could even use varnish and do a look up.
         * 	
         */
        $this->method = $method;
        try {
            $this->route = $this->router->getRoute( $this->method, $this->uri );
            if ( isset( $extra["api-router"] ) ) {
                $this->router = $extra["api-router"];
            }

            if ( isset( $this->route["controller"]["internal"] ) && !isset( $extra["internal"] ) ) {
                throw new \InvalidArgumentException( "Internal Access only" );
            }

            if ( isset( $this->route["controller"]["command-line"] ) && !isset( $extra["command-line"] ) ) {
                throw new \InvalidArgumentException( "Internal Access only" );
            }

            $this->run();
        } catch( \InvalidArgumentException $e ) {
            //@todo Better handling required.
            echo "Method not allowed:{$method}";
            exit;
        }
    }

    private function run ( )
    {
        $className = $this->route["controller"]["className"];
        $method = $this->route["controller"]["method"];

        try {
            $api = new $className();
        } catch ( \Exception $e ) {
            echo "Need to fix this 1";
            exit;
            $a = new Server("get", "/error/1", array() );
            $this->response = $a->response;
            return;
        }
        try {
            $this->response = $api->setRequest( $this->request )
                ->setRouter( $this->router )
                ->setRoute( $this->route )
                ->setUri( $this->uri )
                ->$method();
        } catch ( \Exception $e ) {
            echo "Need to fix this 2";

            exit;
            $a = new Server("get", "/error/1", array() );
            $this->response = $a->response;
            return;
        }
    }

    /**
     * Return a JSON string of the response from the Request.
     */
    public function __toString ()
    {
        /*
         * Need to set the headers
         * 1 header could be a hashkey for varnish?
         * If I want to cache, that is the controller or the models job.
         * If I want to do edge caching then do it here, but dont solve it now.
         */
        if( $this->response instanceof Response )
        {
            return $this->response->__toString();
        }
    }

    public function render()
    {
        //Set header code
        //$this->response->code
        if (count($this->response->headers)>=1) {
            foreach( $this->response->headers as $header => $message ) {
                //@TODO plugin to do something based on a header?
                $str = $header . ": " . $message;
                header($str);
            }
        }

        if (is_string($this->response->message)) {
            return $this->response->message;
        } else {
            return json_encode($this->response->message);
        }
    }
    
    public function response ()
    {
        return $this->response;
    }
}
