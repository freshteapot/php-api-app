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
        try {
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
                $resource = $this->parseCommentBlockForResource($commentBlock);
                $paramData = $this->parseCommentBlockForParameters($commentBlock);

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
                        $data = array(
                                'http' => array(
                                    "resource" => (!empty($resource) ? $resource : "" ),
                                    "uri" => $route,
                                    "method" => $method
                                ),
                                'controller' => array(
                                    "path" => $path,
                                    "className" => $className,
                                    "method" => $method
                            ));

                        if (!empty($paramData)) {
                            $routeParams = explode("/", $route);
                            foreach( $routeParams as $routeParam) {
                                if (empty($routeParam)) continue;
                                if (substr($routeParam, 0, 1) === ":") {
                                    $paramKey = substr($routeParam, 1);
                                    if (isset($paramData[$paramKey])) {
                                        $data["http"]["params"][$paramKey] = $paramData[$paramKey];
                                    }
                                }
                            }
                        }

                        $routes[] = $data;
                        $uniq_route = "{$method}:{$route}";
                        echo $uniq_route . "\n";
                        if ( isset( $uris[ $uniq_route ] ) ) {
                            echo "Two routes have been detected.\n";
                            echo "Latest Route.\n";
                            print_r( end( $routes ) );
                            echo "Other Route.\n";
                            print_r( $uris[$uniq_route] );
                            throw new \Exception("Missing");
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
        } catch( \Exception $e) {
            echo $e->getMessage() . "\n";
            exit(1);
        }
    }
}