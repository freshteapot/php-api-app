<?php
namespace Freshteapot\Library\Template;

class Output
{
    static $data;

    //NOTE was outputBufffer
    public static function buffer($option)
    {
        if (!isset(self::$data['ob'])) {
            self::$data['ob']='';
        }

        switch($option)
        {
            case 'on':
                if( empty( self::$data['ob'] ) )
                {
                    ob_start();
                    self::$data['ob']='on';
                }
                break;
            case 'clean':
                if( self::$data['ob'] === 'on' )
                {
                    ob_end_clean();
                    self::$data['ob']='';
                }
                break;
            case 'get':
                if( self::$data['ob'] === 'on' )
                {
                    $html = ob_get_contents();
                    ob_clean();
                    return $html;
                }
                return '';
                break;
            case 'status':
                return ( self::$data['ob'] === 'on' ) ? true : false;
                break;
        }
    }
}