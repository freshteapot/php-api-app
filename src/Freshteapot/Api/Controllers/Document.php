<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\Server;
use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

class Document extends HttpApi
{
    /**
     * First attempt at reading over the api controllers to gather api information.
     * @return Freshteapot\Http\Response;
     */
    public function get ()
    {
        //@todo removing hardcode dependency.
        $Directory = new \RecursiveDirectoryIterator( APP_DIRECTORY . "/");
        $Iterator = new \RecursiveIteratorIterator($Directory);
        $files = new \RegexIterator($Iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        $apis = array();
        foreach( $files as $file )
        {
            $pathName = $file['0'];
            $pathName = str_replace( APP_DIRECTORY, "", $pathName );

            $className = dirname( $pathName ) . DIRECTORY_SEPARATOR . basename( $pathName, ".php" );
            $className = str_replace( "/", "\\", $className );

            $refClass = new \ReflectionClass( $className );
            $commentBlock = $refClass->getDocComment();
            //Does this controller work with the api? @api = yes.
            if ( strpos( $commentBlock, ' @api') !== false ) {
                $lines = explode ("\n", $commentBlock );
                $key = $refClass->getName();

                $apis[$key] = array(
                "examples" => array(),
                "methods" => array(),
                );

                //Get all examples
                foreach ( $lines as $line ) {
                    $pos = strpos ( $line, '@api.example');

                    if ( $pos !== false ) {
                        $example = substr( $line, ( $pos + strlen('@api.example') ) );
                        $example = trim( $example );
                        $apis[$key]['examples'][] = $example;
                    }
                }

                //Get all methods
                foreach ( $refClass->getMethods() as $object ) {
                    if ( $object->name !== "__construct" && $object->class === $refClass->getName() ) {
                        $apis[$key]['methods'][] = $object->name;
                    }
                }
            }
        }

        return new Response("200", $apis );
    }
}
