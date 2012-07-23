<?php
namespace Freshteapot\Http;

class Response
{
    public $code;
    public $message;
    public $headers;

    CONST ACCESS_GRANTED = 'allow';
    CONST ACCESS_DENIED='access.denied';

    CONST SAVE_FAIL='insert.failed';
    CONST SAVE_BADDATA = 'missing.data';

    CONST DELETE_SUCCESS = 'delete.success';
    CONST DELETE_NOTHING = 'delete.nothing';
    CONST DELETE_FAIL = 'delete.failed';
    CONST NOTHING_TODO = 'No change';
    CONST MISSING_DATA = 'Data expected but missing';

    CONST ERROR_LOG = 'log.it';
    CONST SUCCESS = 'ok';

    /**
     * Message
     * - headers
     * - data
     * 
     * - array
     * - string
     * 
     * Enter description here ...
     * @param unknown_type $code
     * @param unknown_type $message
     */
    public function __construct( $code, $message, $headers = array() )
    {
        $this->code = $code;
        $this->message = $message;
        $this->headers = $headers;
    }

    /**
     * Return the message.
     * @return mixed string|boolean|array
     */
    public function message ()
    {
        return $this->message;
    }

    public function __toString()
    {
        return json_encode(array(
            "code" => $this->code,
            "message" => $this->message,
            "headers" => $this->headers)
        );
    }
}