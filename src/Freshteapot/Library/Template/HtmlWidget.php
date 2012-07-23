<?php
namespace Freshteapot\Library\Template;

use Freshteapot\Library\Template;
/**
 * We do assume the view your trying to use does exist.
 * Enter description here ...
 * @author freshteapot
 *
 */
class HtmlWidget
{
    const MISSING_VIEW = "0001";
    const MISSING_PARAMETER = "0002";

    private $_html = '';
    private $_data = array();

    /**
     * By default we will always try and include whatever is added.
     * 
     * $pathToView
     * - Try and use full path.
     * - It should be a php file.
     * 
     * $data
     * -array of values used in the view
     * 
     * @param string $pathToView
     * @param array $data
     */
    function __construct( $pathToView, $data = array() )
    {
        $this->_data = $data;
        try {
            Output::buffer('on');
            if (file_exists($pathToView) ) {
                include $pathToView;
            } else {
                throw new \Exception("", self::MISSING_VIEW);
            }
            
            $this->_html = Output::buffer('get');
        } catch (\Exception $e) {
            if ($e->getCode() !== self::MISSING_PARAMETER) {
                $this->_html = '<pre>Missing template view</pre>';
            } else {
                //Add a way to log this.
                $this->_html = '<pre>Missing template parameter</pre>';
            }
        }
    }

    function render()
    {
        return $this->_html;
    }

    function __toString()
    {
        return $this->render();
    }


    public function __isset( $name )
    {
        return isset( $this->_data[$name] );
    }

    function __get( $name )
    {
        if( !isset( $this->_data[ $name ] ) )
        {
            throw new \Exception("", self::MISSING_PARAMETER);
        }

        $v = $this->_data[ $name ];
        if( !is_string( $v ) )
        {
            return $v;
        }
        if( is_string( $v ) )
        {
            return htmlentities( $v, ENT_QUOTES, 'utf-8' );
        }

        if( is_string( $v ) && substr( $v, 0,1 ) === '{' && substr( $v, -1 ) === '}' )
        {
            return htmlentities( $v, ENT_QUOTES, 'utf-8' ) ;
        }
        throw new \Exception("", self::MISSING_PARAMETER);
    }
}
