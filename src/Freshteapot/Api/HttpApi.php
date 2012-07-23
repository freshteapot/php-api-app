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
}