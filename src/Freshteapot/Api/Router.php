<?php
namespace Freshteapot\Api;


class Router
{
    private
    $routes = array(),
    $allowedMethods = array( 'get', 'post', 'put', 'delete', 'patch' );

    public
    $response;

    /**
     */
    function __construct(){}

    public function loadConfig($path)
    {
        if (file_exists($path)) {
            $config = json_decode(file_get_contents($path), true);
            return $config;
        }
        throw new \Exception("File not found.");
    }

    public function addRoutes ($data)
    {
        foreach ($data as $routes) {
            foreach ($routes as $config) {
                $this->addRoute($config);
            }
        }
    }

    public function addRoute ($route)
    {
        $method = $route["http"]["method"];
        $uri = $route["http"]["uri"];

        if (!isset($this->routes[$method])) {
            $this->routes[$method] = array();
        }
        $this->routes[$method][$uri] = $route;
    }

    /**
     * 
     * Enter description here ...
     * @param string $method
     * @param string $uri
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getRoute ( $method, $uri )
    {
        if (strpos($uri, "?") !== false) {
            list($uri, $extra) = explode("?", $uri,2);
        } else {
            $extra = "";
        }

        //simple exact match
        if (isset($this->routes[ $method ][ $uri ])) {
            $route = $this->routes[$method][$uri];
            if (!empty($extra)) {
                $route['http']['query'] = array();
                parse_str($extra, $route['http']['query']);
            }
            return $route;
        }

        $part = explode("/", $uri);
        $partSize = count($part);

        $routes = $this->routes[$method];
        foreach ( $routes as $route ) {

            //@note Could force a hard limit for partSize
            $routePart = explode("/", $route["http"]["uri"]);
            if (count($routePart) != $partSize) {
                continue;
            }

            $a = array();
            $b = array();
            $input =  array();
            foreach ($routePart as $k => $v) {
                if (!empty($v) && $v{0} == ":") {
                    $a[] = "input";
                    $b[] = "input";
                    $input[] = $k;
                } else {
                    $a[] = $v;
                    $b[] = $part[$k];
                }
            }

            if (implode("/", $a) === implode("/", $b )) {
                $params = array();
                foreach ($input as $index) {
                    $key = $routePart[$index];
                    $key = str_replace(":","",$key);
                    $params[$key] = $part[$index];
                }
                $route["http"]["params"] = $params;
                if (!empty($extra)) {
                    $route['http']['query'] = array();
                    parse_str($extra, $route['http']['query']);
                }
                return $route;
            }
        }
        throw new \InvalidArgumentException( "Route doesnt exists." );
    }
}
