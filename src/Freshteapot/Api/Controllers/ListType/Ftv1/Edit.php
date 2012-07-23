<?php
namespace Freshteapot\Api\Controllers\ListType\Ftv1;

use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 * @api.example GET /list/1/edit/ftv1
 */
class Edit extends HttpApi
{
    /**
     * @api.internal
     * @api.route /list/:id/edit/ftv1
     */
    public function get ( )
    {
        $id = $this->route['http']['params']['id'];
        return new Response("200", "Edit List for {$id}. Type: ftv1" );
    }
}
