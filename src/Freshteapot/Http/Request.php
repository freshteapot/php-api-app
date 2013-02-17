<?php
namespace Freshteapot\Http;

use Freshteapot\Library\OneTime;

class Request extends OneTime
{
    public function __construct( Array $config=array(), $headers )
    {
        if (function_exists('apache_request_headers')) {
            $config = array_merge(apache_request_headers(), $config);
        } else {
            //@TODO More of a concept for handling data when not using apache.
            if (!empty($headers)) {
                $retVal = array();
                $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $headers));
                foreach( $fields as $field ) {
                    if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
                        $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
                        if( isset($retVal[$match[1]]) ) {
                            if ( is_array( $retVal[$match[1]] ) ) {
                                $i = count($retVal[$match[1]]);
                                $retVal[$match[1]][$i] = $match[2];
                            } else {
                                $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
                            }
                        } else {
                            $retVal[$match[1]] = trim($match[2]);
                        }
                    }
                }
                $config = array_merge($retVal, $config);
            }
        }
        parent::__construct($config);
    }
}