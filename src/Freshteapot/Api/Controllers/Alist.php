<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\Server;
use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

/**
 * @api
 *
 */
class Alist extends HttpApi
{
	public function __construct ()
	{
		
	}

	public function get ()
	{
		return new Response( "200", "get aList" );
	}
}
