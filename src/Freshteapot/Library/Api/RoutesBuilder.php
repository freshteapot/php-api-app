<?php

namespace Freshteapot\Library\Api;

class RoutesBuilder extends Builder
{
    /**
     * Parse over directory and build a router.config.
     * This should be ran whenever a new route is made.
     * @param string $pathToConfig
     */
    public function make ( $pathToConfig )
    {
        $files = $this->getFiles();

        $apis = array();
        $uris = array();
        foreach( $files as $file )
        {
            $path = $file['0'];

            $pathName = str_replace( $this->pathToApp, "", $path );

            $className = dirname( $pathName ) . DIRECTORY_SEPARATOR . basename( $pathName, ".php" );
            $className = str_replace( "/", "\\", $className );

            $refClass = new \ReflectionClass( $className );
            $commentBlock = $refClass->getDocComment();

            if ( $this->commentBlockisApi( $commentBlock ) !== true ) {
                continue;
            }

            $key = $refClass->getName();
            $methods = $this->parseMethods( $refClass );

            if ( count( $methods ) < 1 ) {
                continue;
            }

            $routes = array();

            foreach ( $methods as $method ) {
                $commentBlock = $refClass->getMethod( $method )->getDocComment();

                $routeData = $this->parseCommentBlockForRoutes( $commentBlock );
                if ( empty( $routeData ) ) {
                    echo "We should fail\n";
                    continue;
                }

                foreach ($routeData as $route) {
                    $routes[] = array(
                        'http' => array(
                            "uri" => $route,
                            "method" => $method
                        ),
                        'controller' => array(
                            "path" => $path,
                            "className" => $className,
                            "method" => $method
                        )
                    );

                    $uniq_route = "{$method}:{$route}";
                    echo $uniq_route . "\n";
                    if ( isset( $uris[ $uniq_route ] ) ) {
                        echo "Two routes have been detected.\n";
                        echo "Latest Route.\n";
                        print_r( end( $routes ) );
                        echo "Other Route.\n";
                        print_r( $uris[$uniq_route] );
                        exit;
                    }
                    $uris[$uniq_route] = end( $routes );
                }
            }
            if ( empty( $routes ) ) {
                continue;
            }
            $apis[ $key ] = $routes;
            continue;
        }

        $data = json_encode( $apis );
        file_put_contents ( $pathToConfig, $data );
/*
            $data = array();
            foreach( $examples as $k => $example ) {
                //@note might need to be \t as well.
                list( $method, $uri) = explode( " ", $example );

                $method = strtolower( $method );
                if (in_array( $method, $methods ) ) {
                    $data[] = array(
                        'http' => array(
                            "uri" => $uri,
                            "method" => $method
                        ),
                        'controller' => array(
                            "path" => $path,
                            "className" => $className,
                            "method" => $method
                        )
                    );
                }
            }
            $apis[ $key ] = $data;
        }
        return $apis;
*/
    }

}