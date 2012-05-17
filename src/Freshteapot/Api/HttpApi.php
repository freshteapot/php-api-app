<?php
namespace Freshteapot\Api;

class HttpApi
{
    private
    /**
     * Freshteapot\Http\Response
     */
    $response,

    $uri
    ;

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