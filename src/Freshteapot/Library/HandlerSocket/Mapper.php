<?php
namespace Freshteapot\Library\HandlerSocket;

class Mapper
{
    static private $db;

    function __construct()
    {
        self::setUpConnection();
    }

    public function getConnection()
    {
        return self::$db;
    }

    private function setUpConnection()
    {
        if (self::$db == null) {
            $host = 'localhost';
            $port = 9998;
            $port_wr = 9999;
            try {
                self::$db = new \HandlerSocket($host, $port, array('timeout' => 5));
            } catch (\HandlerSocketException $exception) {
                var_dump($exception->getMessage());
                die();
            }
        }
    }
} /*** end of class ***/