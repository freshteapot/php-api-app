<?php

namespace Freshteapot\Library\Api;

class Builder
{
    public
    $pathToControllers,
    $pathToApp;

    function __construct ( )
    {

    }

    function setPathToAppDirectory ( $path )
    {
        $this->pathToApp = $path;
    }

    function setPathToControllers ( $path )
    {
        $this->pathToControllers = $path;
    }

    function getFiles ()
    {
        $Directory = new \RecursiveDirectoryIterator( $this->pathToControllers );
        $Iterator = new \RecursiveIteratorIterator( $Directory );
        return new \RegexIterator($Iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH );
    }

    public function commentBlockisApi ( $commentBlock )
    {
        if ( strpos( $commentBlock, ' @api') !== false ) {
            return true;
        }
        return false;
    }

    /**
    * Given a comment block, look for @api.route and return rest of line.
    * This format METHOD URI is required.
    *
    * @param string $commentBlock
    * @return array
    */
    function parseCommentBlockForRoutes( $commentBlock )
    {
        $data = array();
        $lines = explode ("\n", $commentBlock );

        $lookFor = "@api.route";
        $lookForLen = strlen( $lookFor );
        foreach ( $lines as $line ) {
            $pos = strpos ( $line, $lookFor );
            if ( $pos !== false ) {
                $found = substr( $line, ( $pos + $lookForLen ) );
                $found = trim( $found );
                $data[] = $found;
            }
        }
        return $data;
    }

    /**
     * Given a comment block, look for @api.example and return rest of line.
     * This format METHOD URI is required.
     * 
     * @param string $commentBlock
     * @return array
     */
    function parseCommentBlockForExamples ( $commentBlock )
    {
        $data = array();
        $lines = explode ("\n", $commentBlock );

        $lookFor = "@api.example";
        $lookForLen = strlen( $lookFor );
        foreach ( $lines as $line ) {
            $pos = strpos ( $line, $lookFor );
            if ( $pos !== false ) {
                $found = substr( $line, ( $pos + $lookForLen ) );
                $found = trim( $found );
                $data[] = $found;
            }
        }
        return $data;
    }

    public function parseMethods ( $reflectionClass )
    {
        $data = array();
        foreach ( $reflectionClass->getMethods() as $object ) {
            if ( $object->name !== "__construct" && $object->class === $reflectionClass->getName() ) {
                $data[] = $object->name;
            }
        }
        return $data;
    }
}