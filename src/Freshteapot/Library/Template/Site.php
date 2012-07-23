<?php
namespace Freshteapot\Library\Template;

use Freshteapot\Library\Template;

class Site
{
    private static $instance;
    private static $data;
    public function __construct()
    {

    }

    public function getInstance ()
    {
        if (self::$instance === null) {
            
        }
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static $pattern = "/((#\{(.*?)\}))/";

    public static $prefix = "#{";
    public static $suffix = "}";

    public static function load( $path_to_file='' )
    {
        if( !empty($path_to_file) && file_exists( $path_to_file ) )
        {
            self::$data['org'] = file_get_contents( $path_to_file );
            self::_load();
        }
    }


    public static function reset()
    {
        self::$data=array();
    }


    public static function loadHtml( $str )
    {
        self::$data['org'] = $str;
        self::_load();
    }


    protected static function _load( $str='')
    {
        if( !empty( $str ) )
        {
            $temp['org'] = self::$data['org'];
            $temp['parsed'] = self::$data['parsed'];
            self::$data['org']=$str;
            self::$data['parsed']='';
        }
        self::$data['parsed']=self::$data['org'];
        preg_match_all( self::$pattern, self::$data['org'], $m );
        if( isset( $m['2'] ) )
        {
            $keys = $m['2'];
            foreach( $keys as $key )
            {
                if( !isset( self::$data['data'][$key] ) )
                {
                    $value='';
                    self::$data['data'][$key]=$value;
                }
            }
        }

        //Is used interanlly via append
        if( !empty( $str ) )
        {
            $str = self::$data['parsed'];
            self::$data['org'] = $temp['org'];
            self::$data['parsed'] = $temp['parsed'];
            return $str;
        }else{

        }
    }



    /*
     * @id string
    * $value string
    */
    public static function set( $id, $value, $override=true )
    {
        $id = self::_key($id);

        if( isset( self::$data['data'][$id] ) )
        {
            if( $override === false )
            {
                self::$data['data'][$id] .= $value;
            }else{
                self::$data['data'][$id] = $value;
            }
        }else{
            self::$data['data'][$id] = $value;
        }
    }

    protected function _key($id)
    {
        return self::$prefix . $id . self::$suffix;
    }

    public static function blank( $id )
    {
        $id = self::_key($id);
        return empty( self::$data[ 'data' ][ $id ] );
    }

    /*
     * @id string
    * $value string
    */
    public static function get( $id )
    {
        $id = self::_key($id);
        return ( isset( self::$data['data'][$id] ) ) ? self::$data['data'][$id] : false;
    }



    /*
     * @id string
    * $value string
    */
    public static function addTo( $id, $value )
    {
        $id = self::_key($id);
        if( isset( self::$data['data'][$id] ) )
        {
            if( strpos(self::$data['data'][$id], $value ) !== false )
            {
                return;
            }
            self::$data['data'][$id] .= $value;
        }else{
            self::$data['data'][$id] = $value;
        }
    }



    /*
     *  This will fill an id with content and at the same time add new variables to the overall system. Equally the variables can be loaded in prior.
    */
    public static function render( $id, $html, $set=false )
    {
        $id_org = $id;
        $id = self::_key($id);
        if(  !isset( self::$data['data'][$id] ) )
        {
            return false;
        }
        $html = self::_load( $html );
        $html = self::html( $html );

        if( $set === false )
        {
            $replace=$id;
            $with=$html;
            self::$data['parsed'] = str_replace( $replace,$with,self::$data['parsed'] );
        }else{
            self::set( $id_org, $html );
        }
    }


    public static function replace( $id, $html )
    {
        $id = self::_key($id);
        if(  !isset( self::$data['data'][$id] ) )
        {
            return false;
        }
        $html = self::_load( $html );
        $replace=$id;
        $with=$html;
        self::$data['parsed'] = str_replace( $replace,$with,self::$data['parsed'] );
    }

    public static function make( $html )
    {
        ftp_debug($_SERVER,'server');
        $html = self::_load( $html );
        return $html;
    }



    public static function html( $html = "" )
    {
        $replace = array();
        $with = array();
        foreach( self::$data['data'] as $k=>$v )
        {
            $replace[]=$k;
            $with[]=$v;
        }
        $html = ( empty( $html ) ) ? self::$data['parsed'] : $html;
        $html = str_replace( $replace, $with, $html );

        return $html;
    }

    public static function dumpKeys()
    {
        return array_keys( self::$data['data'] );
    }

    public static function dump()
    {
        echo '<pre>' . print_r( self::$data, true ) . '</pre>';
    }
}