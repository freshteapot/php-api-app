<?php
namespace Freshteapot\Library;


$_SERVER['REQUEST_DATETIME'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
$_SERVER["ENV_URI_BASEURL"] = 'http://learnalist.net/';
$_SERVER["ENV_URI_BASEFOLDER"] = 'http://cdn.learnalist.net/';

/**
* Quick wrapper to convert an object to string.
* Mostly used to get a string from an object.

* @param array|object $o
* @return string ideally via __toString()
*/
function s($o)
{
    return(string)$o;
}

function o($s)
{
    return json_decode($s);
}

function a($s)
{
    return json_decode($s, true);
}


function sendHeader($number, $exit=true)
{
    /*
     100 Continue
    101 Switching Protocols
    200 OK
    201 Created
    202 Accepted
    203 Non-Authoritative Information
    204 No Content
    205 Reset Content
    206 Partial Content
    300 Multiple Choices
    301 Moved Permanently
    302 Found
    303 See Other
    304 Not Modified
    305 Use Proxy
    307 Temporary Redirect
    400 Bad Request
    401 Unauthorized
    402 Payment Required
    403 Forbidden
    404 Not Found
    405 Method Not Allowed
    406 Not Acceptable
    */
    $str ='200 OK:404 Not Found:304 Not Modified:403 Forbidden:400 Bad Request:401 Unauthorized';
    $pos = strpos( $str, $number );

    $string = $_SERVER['SERVER_PROTOCOL'] . " ";
    if( $pos !== false )
    {
        $end = strpos($str,":",$pos);
        $string .= substr($str,$pos, ( $end-$pos) );

    }else{
        $number = 400;
        $string .= "400 Bad Request";
    }

    ob_end_clean();
    header( $string, true, $number );
    if( $exit )
    {
        exit();
    }
}

function header200()
{
    ob_end_clean();
    header("HTTP/1.0 200 OK");
    exit();
}

function header404()
{
    ob_end_clean();
    header("HTTP/1.0 404 Not Found");
    echo '<p>Today learnalist is not helping you</p>';
    //	include("/var/www/html/site.domain.com/err/404.php");
    exit();
}

function simpleUriAdmin($str='', $seperator=".")
{
    $str = 'admin' . $seperator . $str;
    return simpleUri( $str, $seperator );
}

function simpleUri($str, $seperator=".")
{
    $str = str_replace($seperator, "/", $str);
    $str = rtrim($str, "/");
    //$str .= "/";
    return $_SERVER["ENV_URI_BASEURL"] . $str;
}

function simpleUriFolder($str, $seperator=".")
{
    $str = $_SERVER["ENV_URI_BASEFOLDER"] . $seperator . $str;
    return simpleUri( $str, $seperator ) . "/";
}

function redirect($url="", $code="")
{
    //codes - http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
    switch ($code) {
        case "";
            $status = "";
        break;
        case "404":
            $status = "404 Not Found";
            $url = "";
            break;
        case "204":
            $status = "204 No Content";
            $url = "";
            break;
        case "301":
            $status = "301 Moved Permanently";
            break;
    }

    if ( !empty( $status ) ) {
        header("HTTP/1.1 {$status}");
    }

    if (!empty($url)) {
        header("location: {$url}");
    }
    exit();
}

/**
 * @TODO Should this be in its own class?
 * 
 * @param string $v
 * @return number
 */
function milli_to_seconds( $v )
{
    return round( (float)$v/1000, 2 );
}


function kisset( $name, $data )
{
    //	ftp_debug( $data,'kisset -' . $name,true,false );
    if( is_object( $data ) )
    {
        if( isset( $data->$name ) || property_exists( $data, $name ) )
        {
            return true;
        }
        return false;
    }

    if( is_array( $data ) )
    {
        if( isset( $data[ $name ] ) || array_key_exists( $name, $data ) )
        {

            return true;
        }
        return false;
    }
}