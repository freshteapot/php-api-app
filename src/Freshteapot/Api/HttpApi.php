<?php
namespace Freshteapot\Api;

class HttpApi
{
    private
    /**
     * Freshteapot\Http\Response
     */
    $response
    ;

    protected
        $router,
        $route,
        $request,
        $uri,
        $params
    ;
    function __construct() {}

    function setRouter ( Router $router )
    {
        $this->router = $router;
        return $this;
    }

    function setRoute ( $route )
    {
        $this->route = $route;
        if (isset($this->route["http"]["params"])) {
            $this->params = $this->route["http"]["params"];
        } else {
            $this->params = null;
        }
        return $this;
    }

    function setRequest ( $request )
    {
        $this->request = $request;
        return $this;
    }

    function setUri ( $uri )
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Handles GET requests.
     * When you override this:
     * @return Freshteapot\Http\Response;
     * @throws Exception
     */
    public function get ()
    {
        throw new Exception(__METHOD__);
    }

    /**
     * Handles POST requests.
     * When you override this:
     * @return Freshteapot\Http\Response;
     * @throws Exception
     */
    public function post ()
    {
        throw new Exception(__METHOD__);
    }

    /**
     * Handles PUT requests.
     * When you override this:
     * @return Freshteapot\Http\Response;
     * @throws Exception
     */
    public function put ()
    {
        throw new Exception(__METHOD__);
    }

    /**
     * Handles DELETE requests.
     * When you override this:
     * @return Freshteapot\Http\Response;
     * @throws Exception
     */
    public function delete ()
    {
        throw new Exception(__METHOD__);
    }

    /**
     * Handles PATCH requests.
     * When you override this:
     * @return Freshteapot\Http\Response;
     * @throws Exception
     */
    public function patch ()
    {
        throw new Exception(__METHOD__);
    }

    protected function jsonHeaders ()
    {
        return array(
                "Content-Type" => "application/json",
                //@todo change this to the day learnalist.net was born.
                //@todo make it a shared class, could be where traits become nice.
                "Expires" => "Mon, 26 Jul 1997 05:00:00 GMT",
                "Last-Modified" => $this->httpDate(),
                "Cache-Control" => "no-store, no-cache, must-revalidate, post-check=0, pre-check=0",
                "Pragma" => "no-cache",
        );
    }

    protected function httpDate ()
    {
        return gmdate(\DateTime::RFC1123);
    }

    /**
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
     *
     * @param unknown $code
     */
    final function getStatus ($code)
    {
        $status = "HTTP/1.1 ";
        switch($code) {
            case "200":
                $status .= "200 OK";
                break;
            default:
            case "400":
                $status .= "400 Bad Request";
                break;
            case "422":
                $status .= "422 Unprocessable Entity";
                break;
        }
        return $status;
    }

    /**
     * //@TODO - How to make this handle streaming.
     * @param unknown $method
     * @return NULL|mixed
     */
    final public function getUserInput ($method)
    {
        if ($method === "patch") {
            $input = file_get_contents('php://input');
        } else if ($method === "post") {
            $input = file_get_contents('php://input');
            //$input = $_POST;
        } else {
            //@todo throw exception
            return null;
        }
        $input = json_decode($input, true);
        return $input;
    }
}